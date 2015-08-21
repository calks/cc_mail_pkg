<?php


	require_once 'phpmailer/class.phpmailer.php';

	class mailPkgEmailMessageSender extends PHPMailer {
	
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
			$admin_role_id = 'admin';
			
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

			$result = parent::Send();
			
			$log_enabled = coreSettingsLibrary::get('mailer/enable_logging');
			if ($log_enabled) {
				$this->logSendResult($result);
			}
			
			return $result;
		}
		
		
		protected function logSendResult($result) {
			
			$created = date('Y-m-d H:i:s');
			$email_from = addslashes($this->From);
			$email_to = addslashes(implode(', ', array_keys($this->all_recipients)));
			$email_subject = addslashes($this->Subject);
			$email_body = addslashes($this->AltBody);
			$succeed = (int)$result;
			
			$db = Application::getDb();
			$db->execute("
				INSERT INTO `email_log` (
					`created`,
					`email_from`,
					`email_to`,
					`email_subject`,
					`email_body`,
					`succeed`	
				) VALUES (
					'$created',
					'$email_from',
					'$email_to',
					'$email_subject',
					'$email_body',
					$succeed
				)
			");
		}
		
		
		
		
	} 
	
	
	