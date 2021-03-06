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
 * A panel displaying costs of a technology and allowing to build/remove levels
 * of it.
 */
class Rakuun_Intern_GUI_Panel_Production_Research extends Rakuun_Intern_GUI_Panel_Production_Production {
	const WIP_ITEM_MAXAMOUNT = 5; // maximum allowed amount of items in the wip list
	
	public function init() {
		parent::init();
		
		$this->produce->setValue('Stufe '.$this->getProductionItem()->getNextBuildableLevel().' erforschen');
		if ($this->hasPanel('remove')) {
			$this->remove->setValue('Vernichten');
			$this->remove->setConfirmationMessage(
				'Wirklich eine Stufe dieser Forschung vernichten?\nEs werden 50% der Kosten erstattet:'.
				'\n'.round($this->getProductionItem()->getIronRepayForLevel()).' Eisen'.
				'\n'.round($this->getProductionItem()->getBerylliumRepayForLevel()).' Beryllium'.
				'\n'.round($this->getProductionItem()->getEnergyRepayForLevel()).' Energie'.
				'\n'.round($this->getProductionItem()->getPeopleRepayForLevel()).' Leute'
			);
		}
		
		$options = array();
		$options['conditions'][] = array('user = ?', $this->getProductionItem()->getUser());
		if (Rakuun_DB_Containers::getTechnologiesWIPContainer()->count($options) >= self::WIP_ITEM_MAXAMOUNT) {
			$this->removePanel($this->produce);
		}
	}
	
	public function onProduce() {
		parent::onProduce();
		
		if ($this->hasErrors()) {
			return;
		}
		
		DB_Connection::get()->beginTransaction();
		$nextBuildableLevel = $this->getProductionItem()->getNextBuildableLevel();
		$ironCosts = $this->getProductionItem()->getIronCostsForLevel($nextBuildableLevel);
		$berylliumCosts = $this->getProductionItem()->getBerylliumCostsForLevel($nextBuildableLevel);
		$energyCosts = $this->getProductionItem()->getEnergyCostsForLevel($nextBuildableLevel);
		$peopleCosts = $this->getProductionItem()->getPeopleCostsForLevel($nextBuildableLevel);
		$this->getProductionItem()->getUser()->ressources->lower($ironCosts, $berylliumCosts, $energyCosts, $peopleCosts);
		$record = new DB_Record();
		$record->user = $this->getProductionItem()->getUser();
		$record->technology = $this->getProductionItem()->getInternalName();
		$record->level = $nextBuildableLevel;
		$record->starttime = time();
		Rakuun_DB_Containers::getTechnologiesWIPContainer()->save($record);
		$record->position = $record->getPK();
		$record->save();
		DB_Connection::get()->commit();
		Router::get()->getCurrentModule()->invalidate();
	}
	
	public function onRemove() {
		parent::onRemove();
		
		if ($this->hasErrors()) {
			return;
		}
		
		DB_Connection::get()->beginTransaction();
		// repay ressources
		$technology = $this->getProductionItem();
		$iron = $technology->getIronRepayForLevel();
		$beryllium = $technology->getBerylliumRepayForLevel();
		$energy = $technology->getEnergyRepayForLevel();
		$people = $technology->getPeopleRepayForLevel();
		$technology->getUser()->ressources->raise($iron, $beryllium, $energy, $people);
		// lower technology level
		$technology->getUser()->technologies->lower($technology->getInternalName(), Rakuun_User_Manager::getCurrentUser());
		DB_Connection::get()->commit();
		Router::get()->getCurrentModule()->invalidate();
	}
}

?>