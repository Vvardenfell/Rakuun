<?php

class Rakuun_Intern_GUI_Panel_Tutor_Level_People1 extends Rakuun_Intern_GUI_Panel_Tutor_Level {
	public function completed() {
		$user = Rakuun_User_Manager::getCurrentUser();
		return Rakuun_Intern_Production_Factory::getBuilding('ironmine', $user)->getWorkers() >= 100
			&& Rakuun_Intern_Production_Factory::getBuilding('berylliummine', $user)->getWorkers() >= 100;
	}
	
	public function getDescription() {
		$buildLink = new GUI_Control_Link('buildlink', 'Baumenü ("Produktion -&gt; Gebäude")', App::get()->getInternModule()->getSubmodule('build')->getUrl());
		return '
			Deine Ressourcenproduktion ist noch auf dem selben Stand wie zu Beginn, denn du musst
			noch Leute einstellen, damit deine Minen Rohstoffe produzieren.
			Dazu gehst du wieder ins '.$buildLink->render().' und klickst auf "Arbeiter verwalten".
			Je mehr Arbeiter du einstellst, desto mehr Rohstoffe wirst du produzieren.<br />
			In der Ressourcenübersicht kannst du nicht nur die Produktionsrate deiner Minen, sondern auch
			die Zufriedenheit deiner Bürger überprüfen. Je zufriedener diese sind, desto mehr
			Ressourcen bauen sie ab. Du kannst die Zufriedenheit steigern, indem du Freizeitparks baust.<br />
			<b>Stelle so viele Arbeiter ein, wie möglich.</b>
		';
	}
}
?>