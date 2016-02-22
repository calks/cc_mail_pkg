<?php

	class mailPkgMailingSettingGroup extends coreBaseSettingGroup {
		
		public function getGroupNames() {
			return array(
				'mailer' => 'Mail sending'
			);
		}
		
		public function getParamsTree() {
			$out = array(
				'default_from_name' => array(
					'type' => 'string',
					'displayed_name' => 'Sender default name (FROM)'
				),
			
				'default_from_email' => array(
					'type' => 'string',
					'displayed_name' => 'Sender default email (FROM)'
				),
			
				'transport' => array(
					'type' => 'select',
					'displayed_name' => 'Send method',
					'value' => 'mail',
					'constraints' => array(
						'options' => array(
							'smtp' => 'SMTP', 
							'mail' => 'mail() function', 
							'sendmail' => 'Sendmail'
						)
					)
				),
				'smtp_host' => array(
					'type' => 'string',
					'displayed_name' => 'SMTP: host'					
				),
				'smtp_port' => array(
					'type' => 'integer',
					'displayed_name' => 'SMTP: port',
					'constraints' => array(
						'min' => 1,
						'max' => 65535				
					)										
				),
				'smtp_auth' => array(
					'type' => 'checkbox',
					'displayed_name' => 'SMTP: use authirization'					
				),
				'smtp_username' => array(
					'type' => 'string',
					'displayed_name' => 'SMTP: username'					
				),
				'smtp_password' => array(
					'type' => 'string',
					'displayed_name' => 'SMTP: pasword'					
				),
				'smtp_secure' => array(
					'type' => 'select',
					'displayed_name' => 'SMTP: security',
					'constraints' => array(
						'options' => array(
							null => 'not set', 
							'ssl' => 'ssl',
							'tls' => 'tls'
						)
					)
				),
				'enable_logging' => array(
					'type' => 'checkbox',
					'displayed_name' => 'Enable email logging'					
				)
				
			);
			
			return array('mailer' => $out);
		
		}
	}