<?php

class Rakuun_Intern_GUI_Panel_Tutor_Level_Tipp_Irc extends Rakuun_Intern_GUI_Panel_Tutor_Tipp {
	public function __construct() {
		$this->internal = 'irc';
	}
	
	public function getDescription() {
		return '
			TIPP: Besitzt du einen IRC-Client?! Dann besuche doch mal unseren offiziellen IRC Channel
			Dort kann über Rakuun und Gott und die Welt geplaudert werden. Auch gibt es dort spielerische Hilfe.
			</br>Und hier die Daten:
			</br><b>Server:</b>irc.gamesurge.net
			</br><b>Channel:</b>#rakuun
		';
	}
}

?>