<?php

class Rakuun_Intern_GUI_Panel_User_Reports_View extends Rakuun_Intern_GUI_Panel_User_Reports {
	public function init() {
		parent::init();
		
		$this->addPanel($table = new GUI_Panel_Table('reports'));
		$spies = $this->getReports();
		$units = Rakuun_Intern_Production_Factory::getAllUnits();
		$buildings = Rakuun_Intern_Production_Factory::getAllBuildings();
		$tableHeader = array('Datum', 'Angreifer');
		foreach ($units as $unit) {
			$tableHeader[] = $unit->getName();
		}
		foreach ($buildings as $building) {
			$tableHeader[] = $building->getName();
		}
		$table->addHeader($tableHeader);
		$actualUser = Rakuun_User_Manager::getCurrentUser();
		foreach ($spies as $spy) {
			$line = array();
			$date = new GUI_Panel('date'.$spy->getPK());
			$date->addPanel(new GUI_Control_Link('warsim_link'.$spy->getPK(), date(GUI_Panel_Date::FORMAT_DATETIME, $spy->time), App::get()->getInternModule()->getSubmodule('warsim')->getUrl(array('spyreport' => $spy->getPK()))));
			if ($spy->user->getPK() == $actualUser->getPK()) {
				$img = new GUI_Panel_Image('delimg', Router::get()->getStaticRoute('images', 'cancel.gif'));
				$params = array_merge($this->getModule()->getParams(), array('delete' => $spy->getPK()));
				$date->addPanel($link = new GUI_Control_Link('delete_link'.$spy->getPK(), $img->render(), $this->getModule()->getUrl($params)));
				$link->setConfirmationMessage('Diesen Bericht wirklich löschen?'); 
			}
			$line[] = $date;
			$line[] = new Rakuun_GUI_Control_UserLink('userlink'.$spy->getPK(), $spy->user);
			foreach ($units as $unit) {
				$line[] = $spy->{Text::underscoreToCamelCase($unit->getInternalName())};
			}
			foreach ($buildings as $building) {
				$line[] = $spy->{Text::underscoreToCamelCase($building->getInternalName())};
			}
			$table->addLine($line);
		}
	}
}
?>