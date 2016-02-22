<?php

	class mailPkgEmailTemplateEntity extends coreBaseEntity {
		
		public $name;
		public $body_content;
		public $subject_content;
		public $legend;		
		public $changed;
		
		public function __construct() {
			$this->legend = array();
		}
		
		public function getTableName() {
			return "email_template";
		}
		
        function order_by() {
            return " name ";
        }		
		
		public function make_form(&$form) {
            $form->addField(new THiddenField("id"));            
            $form->addField(coreFormElementsLibrary::get('text', 'body_content')->attr('style', 'height:400px;width: 680px')->allowHtml());
            $form->addField(coreFormElementsLibrary::get('edit', 'subject_content')->attr('style', 'width: 680px'));
            return $form;
		}
		
		public function save() {
			$this->changed = (int)$this->changed;			
			$legend = $this->legend;
			$this->legend = serialize($this->legend);
			$r = parent::save();
			$this->legend = $legend;
			return $r;
		}
		
		public function load_list($params=array()) {
			$list = parent::load_list($params);
			foreach ($list as $item) {
				$item->legend = unserialize($item->legend);
			}
			
			return $list;
		}

		
	}