<?php

/**
 * @package Rakuun Browsergame
 * @copyright Copyright (C) 2012 Sebastian Mayer, Andreas Sicking, Andre Jährling
 * @license GNU/GPL, see license.txt
 * This file is part of Rakuun.
 *
 * Rakuun is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later version.
 *
 * Rakuun is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Rakuun. If not, see <http://www.gnu.org/licenses/>.
 */

class Rakuun_Intern_GUI_Panel_Tutor_Level_Profile extends Rakuun_Intern_GUI_Panel_Tutor_Level {
	public function completed() {
		return Router::get()->getCurrentModule() instanceof Rakuun_Intern_Module_Profile;
	}
	
	public function getDescription() {
		if ($module = App::get()->getInternModule()->getSubmodule('profile'))
			$profilLink = new GUI_Control_Link('profillink', 'Profilseite ("Infos -&gt; Profil")', $module->getUrl());
		else
			$profilLink = new GUI_Panel_Text('profillink', 'Profilseite (nicht verfügbar)');
		return '
			Begib dich jetzt zuerst auf deine '.$profilLink->render().'. Hier kannst du deine persönlichen
			Einstellungen, wie e-Mail Adresse und Passwort ändern.<br />
			Vielleicht möchtest du deiner Stadt auch einen persönlicheren Namen geben?<br />
			<b>Besuche die Profilseite!</b>
		';
	}
}
?>