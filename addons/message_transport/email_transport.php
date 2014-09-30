<?php

	require_once Application::getSitePath() . '/packages/mail/includes/phpmailer/class.phpmailer.php';

	class mailPkgMessageTransportAddonEmailTransport extends PHPMailer {
	
		public function __construct($exceptions = false) {
			parent::__construct($exceptions);

			$this->IsHTML(true);
			$this->CharSet = 'utf-8';
			$this->fromRobot();
			$this->SetLanguage('ru');
			
			
			$transport = coreSettingsLibrary::get('mailer/transport');
			switch ($transport) {
				case 'smtp':
					$this->IsSMTP();
					$this->Host = coreSettingsLibrary::get('mailer/smtp_host');
					$this->Port = coreSettingsLibrary::get('mailer/smtp_port');;
					$this->SMTPAuth = coreSettingsLibrary::get('mailer/smtp_auth');
					if ($this->SMTPAuth) {
						$this->Username = coreSettingsLibrary::get('mailer/smtp_username');
						$this->Password = coreSettingsLibrary::get('mailer/smtp_password');
						$this->SMTPSecure = coreSettingsLibrary::get('mailer/smtp_secure');
					}
					break;				
			}
			
		}
		
		public function setSubject($subject) {
			$this->Subject = $subject;
		}
		
		public function setBody($body) {
			$this->Body = $body;
		}
		
		public function fromRobot() {
			$default_from_email = coreSettingsLibrary::get('mailer/default_from_email');
			$this->From = $default_from_email ? $default_from_email : 'no-reply@' . Application::getHost();
			$this->FromName = coreSettingsLibrary::get('mailer/default_from_name');
		}
		
		
		public function getAdminEmails() {
			$user = Application::getEntityInstance('user');			
			$users_table = $user->getTableName();
			$roles_coupling_table = $user->getRolesCouplingTableName();
			
			$db = Application::getDb();
			$admin_role_id = USER_ROLE_ADMIN;
			
			$sql = "
				SELECT 
					u.email, u.name
				FROM 
					$users_table u 
					INNER JOIN $roles_coupling_table rc ON rc.user_id=u.id
				WHERE 
					rc.role_id=$admin_role_id AND
					u.active=1				
			";
					
			return $db->executeSelectAllObjects($sql);			
		}
		
		
		public function Send() {
			$wrap_template = coreResourceLibrary::getTemplatePath('email_wrap');
					
			if ($wrap_template) {
				$original_body = $this->Body;
				$smarty = Application::getSmarty('email_wrap');
				$smarty->assign('content', $this->Body);
				$smarty->assign('subject', $this->Subject);
				
				$this->MsgHTML($smarty->fetch($wrap_template));
				$this->AltBody = $this->NormalizeBreaks($this->html2text($original_body));
			}

			return parent::Send();			
		}	
	} 