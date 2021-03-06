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

/**
 * Panel to display the user accounts
 */
class Rakuun_Intern_GUI_Panel_Alliance_Account_UserAccounts extends GUI_Panel {
	public function init() {
		parent::init();
		
		$options = array();
		$options['order'] = 'date DESC';
		$options['conditions'][] = array('alliance = ?', Rakuun_User_Manager::getCurrentUser()->alliance);
		$options['conditions'][] = array('sender != ?', 'NULL');
		$logs = Rakuun_DB_Containers::getAlliancesAccountlogContainer()->select($options);
		$users = array();
		foreach ($logs as $log) {
			$user = isset($log->receiver) ? $log->receiver : $log->sender;
			$pk = $user ? $user->getPK() : 0;
			if (!isset($users[$pk])) {
				$users[$pk]['sum'] = array(
						'iron' => 0,
						'beryllium' => 0,
						'energy' => 0,
						'people' => 0
					);
			}
			$users[$pk]['sum']['iron'] += $log->iron * $log->type;
			$users[$pk]['sum']['beryllium'] += $log->beryllium * $log->type;
			$users[$pk]['sum']['energy'] += $log->energy * $log->type;
			$users[$pk]['sum']['people'] += $log->people * $log->type;
			$users[$pk]['user'] = $user;
		}
		$table = new GUI_Panel_Table('table');
		$table->enableSortable();
		$table->setAttribute('summary', 'Kontobewegungen');
		$table->addHeader(array('Name', 'Eisen', 'Beryllium', 'Energie', 'Leute'));
		foreach ($users as $user) {
			$pk = $user['user'] ? $user['user']->getPK() : 0;
			$userlink = new Rakuun_GUI_Control_UserLink('moves_userlink'.$pk, $user['user']);
			$iron = new GUI_Panel_Number('moves_iron'.$pk, $user['sum']['iron']);
			$beryllium = new GUI_Panel_Number('moves_beryllium'.$pk, $user['sum']['beryllium']);
			$energy = new GUI_Panel_Number('moves_energy'.$pk, $user['sum']['energy']);
			$people = new GUI_Panel_Number('moves_people'.$pk, $user['sum']['people']);
			$table->addLine(array($userlink, $iron, $beryllium, $energy, $people));
		}
		$sorterheaders = array();
		for ($i = 1; $i <= $table->getColumnCount(); $i++) {
			$sorterheaders[] = $i.': { sorter: \'separatedDigit\' }';
		}
		$table->addSorterOption('headers: { '.implode(', ', $sorterheaders).' }');
		$this->addPanel($table);
	}
}

?>