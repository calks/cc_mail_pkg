<?php

	class mailPkgMailingSettingGroup extends coreBaseSettingGroup {
		
		public function getGroupNames() {
			return array(
				'mailer' => 'Отправка Email'
			);
		}
		
		public function getParamsTree() {
			$out = array(
				'default_from_name' => array(
					'type' => 'string',
					'displayed_name' => 'Имя по умолчанию в поле FROM'					
				),
			
				'default_from_email' => array(
					'type' => 'string',
					'displayed_name' => 'Email по умолчанию в поле FROM'					
				),
			
				'transport' => array(
					'type' => 'select',
					'displayed_name' => 'Способ отправки',
					'value' => 'mail',
					'constraints' => array(
						'options' => array(
							'smtp' => 'SMTP', 
							'mail' => 'функция mail()', 
							'sendmail' => 'Sendmail'
						)
					)
				),
				'smtp_host' => array(
					'type' => 'string',
					'displayed_name' => 'SMTP: хост'					
				),
				'smtp_port' => array(
					'type' => 'integer',
					'displayed_name' => 'SMTP: порт',
					'constraints' => array(
						'min' => 1,
						'max' => 65535				
					)										
				),
				'smtp_auth' => array(
					'type' => 'checkbox',
					'displayed_name' => 'SMTP: использовать авторизацию'					
				),
				'smtp_username' => array(
					'type' => 'string',
					'displayed_name' => 'SMTP: имя пользователя'					
				),
				'smtp_password' => array(
					'type' => 'string',
					'displayed_name' => 'SMTP: пароль'					
				),
				'smtp_secure' => array(
					'type' => 'select',
					'displayed_name' => 'SMTP: тип шифрования',
					'constraints' => array(
						'options' => array(
							null => 'не выбран', 
							'ssl' => 'ssl',
							'tls' => 'tls'
						)
					)
				),
				'enable_logging' => array(
					'type' => 'checkbox',
					'displayed_name' => 'Вести лог отправки'					
				)
				
			);
			
			return array('mailer' => $out);
		
		}
	}