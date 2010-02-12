<?php

class Rakuun_Intern_GUI_Panel_Map extends GUI_Panel {
	const MAP_WIDTH = 100;
	const MAP_HEIGHT = 100;
	const MAP_RECT_SIZE = 10;

	private $zoom = 1;
	private $viewRectSize = 60;
	private $viewRectX = 0;
	private $viewRectY = 0;
	private $scrollItems = array();
	private $target = null;
	private $cityX = 0;
	private $cityY = 0;
	
	public function __construct($name, Rakuun_DB_User $target = null, $cityX = 0, $cityY = 0) {
		parent::__construct($name);
		$this->target = $target;
		$this->cityX = $cityX;
		$this->cityY = $cityY;
		$this->viewRectX = $cityX;
		$this->viewRectY = $cityY;
		if ($this->viewRectX == 0 && $this->viewRectY == 0) {
			$this->viewRectX = Rakuun_User_Manager::getCurrentUser()->cityX;
			$this->viewRectY = Rakuun_User_Manager::getCurrentUser()->cityY;
		}
		$this->viewRectX -= $this->viewRectSize / 2;
		if ($this->viewRectX < 0)
			$this->viewRectX = 0;
		if ($this->viewRectX > self::MAP_WIDTH - $this->viewRectSize)
			$this->viewRectX = self::MAP_WIDTH - $this->viewRectSize;
		$this->viewRectY -= $this->viewRectSize / 2;
		if ($this->viewRectY < 0)
			$this->viewRectY = 0;
		if ($this->viewRectY > self::MAP_HEIGHT - $this->viewRectSize)
			$this->viewRectY = self::MAP_HEIGHT - $this->viewRectSize;
	}
	
	public function init() {
		parent::init();
		
		$this->setTemplate(dirname(__FILE__).'/map.tpl');
		
		if (Rakuun_User_Manager::getCurrentUser()->alliance && Rakuun_User_Manager::getCurrentUser()->alliance->buildings->databaseDetector > 0) {
			$visibleDatabases = Rakuun_User_Specials_Database::getVisibleDatabasesForAlliance(Rakuun_User_Manager::getCurrentUser()->alliance);
			if (!empty($visibleDatabases)) {
				$options = array();
				$options['conditions'][] = array('identifier IN ('.implode(', ', $visibleDatabases).')');
				foreach (Rakuun_DB_Containers::getDatabasesStartpositionsContainer()->select($options) as $databasePosition) {
					$this->addPanel($databasePanel = new Rakuun_Intern_GUI_Panel_Map_Database($this, $databasePosition->identifier, $databasePosition->positionX, $databasePosition->positionY));
					$this->scrollItems[] = $databasePanel;
				}
			}
		}
		
		foreach ($this->getCities() as $city) {
			$this->addPanel($cityPanel = new Rakuun_Intern_GUI_Panel_Map_City($city, $this));
			$this->scrollItems[] = $cityPanel;
		}
		
		$this->addPanel(new Rakuun_Intern_GUI_Panel_Map_Path('path', $this));
		$this->addPanel(new Rakuun_GUI_Panel_Box('target', new Rakuun_Intern_GUI_Panel_Map_Target('target', $this->target, $this->cityX, $this->cityY), 'Zielauswahl'));
		$this->addPanel(new Rakuun_Intern_GUI_Panel_Map_ScrollButton_Left('scroll_left', ''));
		$this->addPanel(new Rakuun_Intern_GUI_Panel_Map_ScrollButton_Right('scroll_right', ''));
		$this->addPanel(new Rakuun_Intern_GUI_Panel_Map_ScrollButton_Up('scroll_up', ''));
		$this->addPanel(new Rakuun_Intern_GUI_Panel_Map_ScrollButton_Down('scroll_down', ''));
	}
	
	public function afterInit() {
		parent::afterInit();
		
		$style = array(
			'cursor:move',
			'overflow:scroll',	// for disabled JS
			'position:relative',
			'float:left',
			'height:'.$this->viewRectSize * self::MAP_RECT_SIZE.'px',
			'width:'.$this->viewRectSize * self::MAP_RECT_SIZE.'px'
		);
		$this->setAttribute('style', implode(';', $style));
		
		/*
		 * TODO meh, IE knows only background-position-x/y instead of background-position...
		 * (only for reading, writing works perfectly!)
		 * check if background-position works with jquery 1.4
		 */
		$this->addJS(
			sprintf('
				function bgX(element) {
					if($.browser.msie)
						return parseInt($(element).css("background-position-x"));
					else
						return parseInt($(element).css("background-position").split(" ")[0]);
				}
				
				function bgY(element) {
					if($.browser.msie)
						return parseInt($(element).css("background-position-y"));
					else
						return parseInt($(element).css("background-position").split(" ")[1]);
				}
				
				function scroll(dx, dy) {
					globalX -= dx;
					globalY -= dy;
					if (globalX > 0) {
						dx = dx + globalX;
						globalX = 0;
					}
					if (globalX < -%4$d) {
						dx = dx + globalX + %4$d;
						globalX = -%4$d;
					}
					if (globalY > 0) {
						dy = dy + globalY;
						globalY = 0;
					}
					if (globalY < -%3$d) {
						dy = dy + globalY + %3$d;
						globalY = -%3$d;
					}
				
					$(".scroll_bg").each(function() {
						x = bgX(this);
						y = bgY(this);
						//if (globalX < 0 && globalX > -%4$d)
							x -= dx;
						//if (globalY < 0 && globalY > -%3$d)
							y -= dy;
						$(this).css("background-position", x + "px " + y + "px");
					});
					$(".scroll_item").each(function() {
						x = parseInt($(this).css("left"));
						y = parseInt($(this).css("top"));
						//if (globalX < 0 && globalX > -%4$d)
							x -= dx;
						//if (globalY < 0 && globalY > -%3$d)
							y -= dy;
						x = $(this).css("left", x + "px");
						y = $(this).css("top", y + "px");
					});
				}
				
				$("#%1$s")
				.css("overflow", "hidden")
				.mousedown(function(e) {
					pan = true;
					oldPageX = e.pageX;
					oldPageY = e.pageY;
					return false;
				}).mouseup(function() {
					pan = false;
					return false;
				}).mouseout(function() {
					pan = false;
					return false;
				}).mousemove(function(e) {
					if (pan) {
						scroll(oldPageX - e.pageX, oldPageY - e.pageY);
						oldPageX = e.pageX;
						oldPageY = e.pageY;
					}
					return false;
				});
				
				var scrollTimer;
				var globalX = %5$d;
				var globalY = %6$d;
				var pan = false;
				var oldPageX = 0;
				var oldPageY = 0;
			', $this->getID(), $this->path->getID(), (self::MAP_HEIGHT - $this->viewRectSize) * self::MAP_RECT_SIZE, (self::MAP_WIDTH - $this->viewRectSize) * self::MAP_RECT_SIZE, - $this->viewRectX * self::MAP_RECT_SIZE, - $this->viewRectY * self::MAP_RECT_SIZE)
		);
	}
	
	public function setViewCenterX($centerX) {
		$this->viewRectX = $centerX - round($this->viewRectSize / 2);
	}
	
	public function getViewCenterY($centerY) {
		$this->viewRectY = $centerY - round($this->viewRectSize / 2);
	}
	
	public function getCities() {
		$options = array();
//		$options['conditions'][] = array('city_x >= ?', $this->getViewRectX());
//		$options['conditions'][] = array('city_x <= ?', $this->getViewRectX() + $this->viewRectSize);
//		$options['conditions'][] = array('city_y >= ?', $this->getViewRectY());
//		$options['conditions'][] = array('city_y <= ?', $this->getViewRectY() + $this->viewRectSize);
		return Rakuun_DB_Containers::getUserContainer()->select($options);
	}
	
	public function realToViewPositionX($x) {
		return ($x - $this->getViewRectX()) * self::MAP_RECT_SIZE;
	}
	
	public function realToViewPositionY($y) {
		return ($y - $this->getViewRectY()) * self::MAP_RECT_SIZE;
	}
	
	// GETTERS / SETTERS -------------------------------------------------------
	public function getZoom() {
		return $this->zoom;
	}
	
	public function setZoom($zoom) {
		$this->zoom = $zoom;
	}
	
	public function getViewRectX() {
		return $this->viewRectX;
	}
	
	public function getViewRectY() {
		return $this->viewRectY;
	}
	
	public function getViewRectSize() {
		return $this->viewRectSize;
	}
	
	public function getScrollItems() {
		return $this->scrollItems;
	}
	
	public function getMapLayer() {
		return '<div style="width:'.(self::MAP_WIDTH * self::MAP_RECT_SIZE).'px; height:'.(self::MAP_HEIGHT * self::MAP_RECT_SIZE).'px; background:#2D78BE url('.Router::get()->getStaticRoute('images', 'map_large.png').') no-repeat left top; position:absolute; left:'.(-$this->getViewRectX() * self::MAP_RECT_SIZE).'px; top:'.(-$this->getViewRectY() * self::MAP_RECT_SIZE).'px;" class="scroll_item"></div>';
	}
}

?>