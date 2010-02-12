<?php

class Rakuun_Intern_Production_Building_House extends Rakuun_Intern_Production_Building_Store {
	public function __construct(DB_Record $dataSource = null) {
		parent::__construct($dataSource);
		
		$this->setInternalName('house');
		$this->setName('Wohnhaus');
		$this->setBaseIronCosts(2250);
		$this->setBaseBerylliumCosts(750);
		$this->setBasePeopleCosts(150);
		$this->setBaseTimeCosts(15*60);
		$this->setBaseCapacity(2000);
		$this->setMinimumLevel(1);
		$this->setShortDescription('Jedes Wohnhaus erhöht die Anzahl der Bürger, die in der Stadt wohnen können.');
		$this->setLongDescription('Wohnhäuser sind größere Gebäude mit einer großen Anzahl Standard-Wohneinheiten.
			<br/>
			Da sie für die arbeitende Bevölkerung vorgesehen sind, sind es eher einfache aber dennoch recht passable Wohnungen.');
		$this->setPoints(5);
	}
	
	protected function defineEffects() {
		$this->addEffect('Erhöht die Menge der Leute die in der Stadt wohnen können um '.GUI_Panel_Number::formatNumber($this->getCapacity($this->getLevel() + $this->getFutureLevels() + 1) - $this->getCapacity($this->getLevel() + $this->getFutureLevels())));
	}
}

?>