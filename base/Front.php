<?php
class Front extends FrontBase{
	public $KeyWords = array("MiMFa","Minimal Members Factory");

	/**
     * The main menu to show on the most pages
     * @field object
	 * @category Render
     * @var array|null
	 */
	public $MainMenus = [array("Name" =>"HOME","Path"=>"/home","Image" =>"home")];
}
?>