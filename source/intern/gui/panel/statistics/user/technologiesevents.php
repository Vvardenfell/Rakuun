<?php

class Rakuun_Intern_GUI_Panel_Statistics_User_TechnologiesEvents extends GUI_Panel_PageView {
	public function __construct($name, $title = '') {
		$options = array();
		$options['conditions'][] = array('user = ?', Rakuun_User_Manager::getCurrentUser());
		$options['order'] = 'ID DESC';
		parent::__construct($name, Rakuun_DB_Containers::getLogTechnologiesContainer()->getFilteredContainer($options), $title);
	}
	
	public function init() {
		parent::init();
		$this->setTemplate(dirname(__FILE__).'/events.tpl');
		
		$this->params->events = $this->getContainer()->select($this->getOptions());
	}
}

?>