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

class Rakuun_Intern_Production_Building_Metas_Dancertia extends Rakuun_Intern_Production_MetaBuilding {
	public function __construct(DB_Record $dataSource = null) {
		parent::__construct($dataSource);
		
		$this->setInternalName('dancertia');
		$this->setName('Dancertia');
		$this->setBaseIronCosts(500000);
		$this->setBaseBerylliumCosts(500000);
		$this->setBaseEnergyCosts(250000);
		$this->setBasePeopleCosts(50000);
		$this->setBaseTimeCosts(3*24*60*60);
		$this->setMaximumLevel(1);
		$this->addNeededBuilding('space_port', 1);
		$this->addNeededRequirement(new Rakuun_Intern_Production_Requirement_Meta_GotDatabases());
		$this->addNeededRequirement(new Rakuun_Intern_Production_Requirement_Meta_GotShieldGenerator());
		$this->setShortDescription('Die "Dancertia" - der Traum derjenigen, die nach der ultimativen Macht streben.
			<br/>
			Sie ist das größte Raumschiff aller Zeiten, mit einer Crew von 50.000 Mann, bestückt mit den unglaublichsten Waffen.
			<br/>
			Dieses gewaltige Schiff kann nur unter den größten Sicherheitsmaßnahmen gebaut werden.
			<br/>
			Zahlreiche Schildgeneratoren sollten das Baufeld beschützen!<br/>Sei dir sicher, oh Erbauer, dass du für ausreichend Schutz gesorgt hast - denn sobald das Schiff in Auftrag gegeben wurde, gibt es kein zurück.
			<br/>
			Die Schildgeneratoren werden aktiv, der Dancertia-Raumhafen wird von der Außenwelt abgeschottet. Sobald der Bau läuft, können keine weiteren Schildgeneratoren in die Reihe der Verteidiger aufgenommen werden!
			<br/>
			Die Dancertia ist so gewaltig, dass ihr Start '.Rakuun_Date::formatCountDown(RAKUUN_SPEED_DANCERTIA_STARTTIME).' Zeit in Anspruch nimmt. Bis sich das Schiff im Orbit Rakuuns befindet, muss also unbedingt für seinen Schutz gesorgt werden!
			<br/>
			<br/>
			Der Legende zufolge wurde die "Dancertia" von den Vorfahren der heutigen Rakuuraner entwickelt.');
	}
}

?>