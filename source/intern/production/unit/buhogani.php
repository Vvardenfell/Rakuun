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

class Rakuun_Intern_Production_Unit_Buhogani extends Rakuun_Intern_Production_Unit {
	public function __construct(DB_Record $dataSource = null) {
		parent::__construct($dataSource);
		
		$this->setInternalName('buhogani');
		$this->setName('Buhogani');
		$this->setNamePlural('Buhoganis');
		$this->setBaseIronCosts(160);
		$this->setBaseBerylliumCosts(640);
		$this->setBaseEnergyCosts(400);
		$this->setBasePeopleCosts(8);
		$this->setBaseTimeCosts(16*60);
		$this->setBaseAttackValue(22);
		$this->setBaseDefenseValue(8);
		$this->setBaseSpeed(110);
		$this->setRessourceTransportCapacity(50);
		$this->setUnitType(Rakuun_Intern_Production_Unit::TYPE_VEHICLE);
		$this->addNeededBuilding('tank_factory', 1);
		$this->addNeededTechnology('light_weaponry', 1);
		$this->addNeededRequirement(new Rakuun_Intern_Production_Requirement_NotInNoobProtection());
		$this->setShortDescription('Buhogani');
		$this->setLongDescription('Der Buhogani ist um einiges härter gepanzert als sein kleiner Bruder, der Minigani, und besitzt mit 76 mm Kanonen eine bei weitem durchschlagendere Kraft.
			<br/>
			Die schweren Ketten und das ultraharte Chassis behindern die Agilität des Koloss allerdings stark, sodass er eher kriecht als fährt und sogar von Fusstruppen überholt wird.
			<br/>
			Seine Verwendung findet er daher eher in fortgesetzten Kriegen um harte Verteidigungslinien zu durchbrechen oder bereits zerstörte Städte völlig in Schlacke zu verwandeln.');
	}
}

?>