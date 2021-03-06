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

class Rakuun_Intern_Module_Profile extends Rakuun_Intern_Module implements Scriptlet_Privileged {
	public function init() {
		parent::init();
		
		$this->setPageTitle('Profil');
		$this->contentPanel->setTemplate(dirname(__FILE__).'/profile.tpl');
		// add css files for all skins
		foreach (Rakuun_GUI_Skinmanager::get()->getAllSkins() as $skin) {
			foreach ($skin->getCssRouteReferences() as $route) {
				$this->addCssRouteReference($route[0], $route[1]);
			}
		}
		
		$this->contentPanel->addPanel($editBox = new Rakuun_Intern_GUI_Panel_Profile_Edit('edit', 'Profil'));
		$editBox->addClasses('rakuun_box_profile_edit');
		$this->contentPanel->addPanel(new Rakuun_Intern_GUI_Panel_Profile_ChangePassword('change_password', 'Passwort ändern'));
		$this->contentPanel->addPanel($deleteBox = new Rakuun_GUI_Panel_Box('delete', new Rakuun_Intern_GUI_Panel_Profile_Delete('link'), 'Account löschen'));
		$deleteBox->addClasses('rakuun_box_delete');
		
		// eternal profile
		if (Rakuun_DB_Containers::getUserEternalUserAssocContainer()->selectByUserFirst(Rakuun_User_Manager::getCurrentUser())) {
			$this->contentPanel->addPanel($eternalProfileBox = new Rakuun_GUI_Panel_Box('eternal_profile', new Rakuun_Intern_GUI_Panel_Profile_EternalProfile('eternal_profile'), 'Ewiges Profil'));
		}
		else {
			$this->contentPanel->addPanel($eternalProfileBox = new Rakuun_GUI_Panel_Box('eternal_profile', new Rakuun_Intern_GUI_Panel_Profile_EternalProfileManage('eternal_profile'), 'Ewiges Profil'));
		}
		$eternalProfileBox->addClasses('rakuun_box_profile_eternal');
	}
	
	// OVERRIDES / IMPLEMENTS --------------------------------------------------
	public function checkPrivileges() {
		return (Rakuun_GameSecurity::get()->hasPrivilege(Rakuun_User_Manager::getCurrentUser(), Rakuun_GameSecurity::PRIVILEGE_MODIFY_PROFILE));
	}
}

?>