<?php

class Rakuun_Intern_GUI_Panel_Shoutbox_Alliance extends Rakuun_Intern_GUI_Panel_Shoutbox {
	public function __construct($name, $title = '') {
		$this->config = new Shoutbox_Config();
		$user = Rakuun_User_Manager::getCurrentUser();
		$options = array();
		$options['conditions'][] = array('alliance = ?', $user->alliance);
		$this->config->setShoutContainer(Rakuun_DB_Containers::getShoutboxAlliancesContainer()->getFilteredContainer($options));
		$shout = new DB_Record();
		$shout->user = $user;
		$shout->alliance = $user->alliance;
		$this->config->setShoutRecord($shout);
		$this->config->setShoutMaxLength(1000);
		$this->config->setDeleteQuery('
			DELETE FROM '.$this->config->getShoutContainer()->getTable().'
			WHERE ID <= (
				SELECT MIN(ID)
				FROM(
					SELECT ID
					FROM '.$this->config->getShoutContainer()->getTable().'
					WHERE alliance = '.$user->alliance->getPK().'
					ORDER BY date DESC
					LIMIT 1
					OFFSET 100
				) as temp
			) AND alliance = '.$user->alliance->getPK().'
		');
		$this->config->setUserIsMod(Rakuun_Intern_Alliance_Security::get()->hasPrivilege($user, Rakuun_Intern_Alliance_Security::PRIVILEGE_MODERATION));
		$this->config->setIsGlobal(false);
		
		parent::__construct($name, $title);
	}
}
?>