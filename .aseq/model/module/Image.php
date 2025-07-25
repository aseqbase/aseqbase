<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
class Image extends Module{
	public $Source = null;
	public $Tag = null;
	public $AllowOrigin = true;

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
			min-width: {$this->MinWidth};
			min-height: {$this->MinHeight};
			max-width: {$this->MaxWidth};
			max-height: {$this->MaxHeight};
			width: {$this->Width};
			height: {$this->Height};
			background-position: center;
			background-repeat: no-repeat;
			background-size: contain;
		}
		");
	}

	public function Get(){
		$src = $this->Source??$this->Content;
		return parent::GetTitle().
			(
				isValid($src)? (
					preg_match('/^\s*<\w+/', $src)? preg_replace("/(^\s*<\w+)/", "$1 ".$this->GetDefaultAttributes(), $src):
					(
						$this->AllowOrigin? (
								isFormat($src,".svg")? Html::Embed($this->Content, $src, $this->GetDefaultAttributes())
									:Html::Image($this->Content, $src, $this->GetDefaultAttributes())
						) :Html::Media($this->Content, $src, $this->GetDefaultAttributes())
					)
				) :null
			).parent::GetDescription();
	}
}
?>
