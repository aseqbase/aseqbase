<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
class Image extends Module{
	public $Capturable = true;
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
		return parent::GetStyle().HTML::Style("
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
		$src = \MiMFa\Library\Local::GetUrl($this->Source??$this->Image);
		return parent::Get().
			(
				isValid($src)? (
					$this->AllowOriginal? (
							isFormat($src,".svg")? "<embed ".$this->GetDefaultAttributes()." src=\"".$src."\"></embed>"
								:"<img ".$this->GetDefaultAttributes()." src=\"$src\"/>"
					) :"<div ".$this->GetDefaultAttributes()." style=\"background-image: url('$src');\"></div>"
				) :null
			);
	}
}
?>
