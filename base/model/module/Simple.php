<?php namespace MiMFa\Module;

class Simple extends Module{
	public function __construct($title, $content, $description){
		parent::__construct();
		$this->Title = $title;
		$this->Content = $content;
		$this->Description = $description;
	}
}