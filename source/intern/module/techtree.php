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

class Rakuun_Intern_Module_Techtree extends Rakuun_Intern_Module {
	public function init() {
		parent::init();
		
		$this->setPageTitle('Techtree');
		$this->contentPanel->setTemplate(dirname(__FILE__).'/techtree.tpl');

		$buildingsPanel = new Rakuun_Intern_GUI_Panel_Techtree_Category('buildings');
		$this->contentPanel->addPanel(new Rakuun_GUI_Panel_Box_Collapsible('buildings', $buildingsPanel, 'Gebäude'));
		foreach (Rakuun_Intern_Production_Factory::getAllBuildings() as $building) {
			$buildingsPanel->addPanel(new Rakuun_Intern_GUI_Panel_Techtree_Item('item_'.$building->getInternalName(), $building));
		}
		
		$technologiesPanel = new Rakuun_Intern_GUI_Panel_Techtree_Category('technologies');
		$this->contentPanel->addPanel(new Rakuun_GUI_Panel_Box_Collapsible('technologies', $technologiesPanel, 'Forschungen'));
		foreach (Rakuun_Intern_Production_Factory::getAllTechnologies() as $technology) {
			$technologiesPanel->addPanel(new Rakuun_Intern_GUI_Panel_Techtree_Item('item_'.$technology->getInternalName(), $technology));
		}
		
		$unitsPanel = new Rakuun_Intern_GUI_Panel_Techtree_Category('units');
		$this->contentPanel->addPanel(new Rakuun_GUI_Panel_Box_Collapsible('units', $unitsPanel, 'Einheiten'));
		foreach (Rakuun_Intern_Production_Factory::getAllUnits() as $unit) {
			$unitsPanel->addPanel(new Rakuun_Intern_GUI_Panel_Techtree_Item('item_'.$unit->getInternalName(), $unit));
		}
	}
}

?>