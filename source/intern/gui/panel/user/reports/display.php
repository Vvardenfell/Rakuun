<?php

abstract class Rakuun_Intern_GUI_Panel_User_Reports_Display extends Rakuun_Intern_GUI_Panel_User_Reports {
	private $data = array();
	
	public function init() {
		parent::init();
		
		$this->setTemplate(dirname(__FILE__).'/display.tpl');
		
		if (!empty($this->data)) {
			$graph = new GUI_Panel_Plot_Lines('graph_'.$this->getName());
			$graph->setLegendPosition(GUI_Panel_Plot::LEGEND_POSITION_EAST);
			foreach ($this->data['reports'] as $name => $set) {
				$graph->addLine($set, $name);
			}
			$graph->setXNames($this->data['date']);
			$graph->getGraph()->img->setMargin(30, 110, 10, 70);
			if (count(reset($this->data['reports'])) > 1)
				$this->addPanel($graph);
		}
	}
	
	protected function setData(array $data) {
		$this->data = $data;
	}
}
?>