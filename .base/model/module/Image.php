<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class Image extends Module{
	public $Source = null;
	public $Image = null;
	public $Tag = null;
	public $AllowOriginal = false;

	/**
     * The Width of Image
     * @var string
     */
	public $Width = "inherit";
	/**
     * The Height of thumbnail preshow
     * @var string
     */
	public $Height = "inherit";
	/**
     * The Minimum Width of Image
     * @var string
     */
	public $MinWidth = "1vw";
	/**
     * The Minimum Height of Image
     * @var string
     */
	public $MinHeight = "1vh";
    /**
     * The Maximum Width of Image
     * @var string
     */
	public $MaxWidth = "100vw";
	/**
     * The Maximum Height of thumbnail preshow
     * @var string
     */
	public $MaxHeight = "100vh";

	/**
     * Create the module
     * @param string|null $source The module source
     */
	public function __construct($source =  null){
        parent::__construct();
		$this->Set($source);
    }
	/**
     * Set the main properties of module
     * @param string|null $source The module source
     */
	public function Set($source =  null){
		$this->Source = $source;
		return $this;
    }

	public function GetStyle(){
		return parent::GetStyle().Html::Style("
		.{$this->Name}{
			min-width:  {$this->MinWidth};
			min-height:  {$this->MinHeight};
			max-width:  {$this->MaxWidth};
			max-height:  {$this->MaxHeight};
			width:  {$this->Width};
			height: {$this->Height};
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
		}
		");
	}

	public function Get(){
		$src = $this->Source??$this->Image??$this->Content;
		return parent::Get().
			(
				isValid($src)? (
					preg_match('/^\s*<\w+/', $src)? preg_replace("/(^\s*<\w+)/", "$1 ".$this->GetDefaultAttributes(), $src):
					(
						$this->AllowOriginal? (
								isFormat($src,".svg")? "<embed ".$this->GetDefaultAttributes()." src=\"".\MiMFa\Library\Local::GetUrl($src)."\"></embed>"
									:"<img ".$this->GetDefaultAttributes()." src=\"".\MiMFa\Library\Local::GetUrl($src)."\"/>"
						) :"<div ".$this->GetDefaultAttributes()." style=\"background-image: url('".\MiMFa\Library\Local::GetUrl($src)."');\"></div>"
					)
				) :null
			);
	}
}
?>
