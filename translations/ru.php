<?php

	class mailPkgRuTranslation extends langPkgRuTranslation {
	
		public function getTranslations() {
			return array(
				'Mail sending' => 'Отправка почты',
				'Sender default name (FROM)' => 'Имя отправителя по умолчанию (поле FROM)',
				'Sender default email (FROM)' => 'Email отправителя по умолчанию (поле FROM)',
				'Send method' => 'Метод отправки',
				'SMTP: host' => 'SMTP: хост',
				'SMTP: port' => 'SMTP: порт',
				'SMTP: use authirization' => 'SMTP: использовать авторизацию',
				'SMTP: username' => 'SMTP: пользователь',
				'SMTP: pasword' => 'SMTP: пароль',
				'SMTP: security' => 'SMTP: способ шифрования',
				'Enable email logging' => 'Логгировать отправку Email'
			);	
		}
		
	}
	
	
	