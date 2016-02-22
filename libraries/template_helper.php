<?php

	class mailPkgTemplateHelperLibrary {
		
		protected $replacements = array();
		protected $template;
		
		public static function get($name) {
			$template = Application::getEntityInstance('email_template');
			$table = $template->getTableName();
			$name = addslashes($name);
			$params['where'][] = "$table.name='$name'";
			$list = $template->load_list($params);
			
			if ($list) {
				$template = array_shift($list);				
			}
			else {
				$template = Application::getEntityInstance('email_template');
				$template->name = $name; 
				$template->save();
			}

			$classname = __CLASS__;
			$out = new $classname();
			$out->template = $template;
			return $out;
		}
		
		
		public function setDefaultContent($default_subject, $default_body) {
			if (!$this->template->changed) {
				$this->template->subject_content = $default_subject;
				$this->template->body_content = $default_body;
				$this->template->save();
			}
			return $this;
		}
		
		public function setLegend($legend) {
			$this->template->legend = $legend;
			$this->template->save();
			return $this;
		}
		
		public function setReplacements($replacements) {
			$this->replacements = $replacements;
			return $this;
		}
		
		
		public function getFilledBody() {
			return $this->getFilledContent('body_content');			
		}
		
		
		public function getFilledSubject() {
			return $this->getFilledContent('subject_content');			
		}
		
		
		public function getFilledContent($field) {
			$replacements = $this->getReplacements();
			
			ksort($replacements, SORT_STRING);

			$replacements_ps = array();
			foreach ($replacements as $s=>$r) {
				$replacements_ps['%' . $s . '%'] = $r;
			}
			
			return str_replace(array_keys($replacements_ps), $replacements_ps, $this->template->$field);
		}
		
		
		protected function getReplacements() {
			$replacements = array();
			
			$replacements['site_domain'] = $_SERVER['HTTP_HOST']; 
			$replacements['site_link'] = 'http://' . $_SERVER['HTTP_HOST'] . '/';
			
			$user_session = Application::getUserSession();
			$user_logged = $user_session->getUserAccount();
			$replacements['user_name'] = $user_logged ? $user_logged->name : 'Аноним';
			$replacements['user_email'] = $user_logged ? $user_logged->email : '';
			
			foreach ($this->replacements as $s=>$r) {
				$replacements[$s] = $r;
			}
			
			return $replacements;
		}
		
		
		public function getLegend() {
			$legend = array();
			$legend['site_domain'] = 'Домен сайта';
			$legend['site_link'] = 'Ссылка на сайт';
			$legend['user_name'] = 'ФИО пользователя';
			$legend['user_email'] = 'Email пользователя';
			
			foreach ($this->template->legend as $code => $name) {
				$legend[$code] = $name;	
			}
			
			return $legend;
		}
		

		
		
		
		
	}