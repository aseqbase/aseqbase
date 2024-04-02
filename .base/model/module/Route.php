<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
/**
 * Automatic crate route map through url or item iterations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Modules See the Documentation
 */
class Route extends Module{
	public $Capturable = true;
	/**
	 * The source of items
     * @var array<string,string>
	 */
	public $Items = array();
	/**
     * A simple Path to show routing
	 * @var string
	 */
	public $Path = null;
	/**
     * The label to show for the root
     * @var bool
     */
	public $SeparatorSymbol = "&#8702;";
	/**
     * The label to show for the root
     * @var bool
     */
	public $RootLabel = null;
	/**
     * Show current document name in route
     * @var bool
     */
	public $ShowCurrent = false;
	/**
     * Convert the first character of each word in a string to uppercase
     * @var bool
     */
	public $AllowUpperCase = true;

	public function __construct($itemsOrpath = null){
		parent::__construct();
		$this->SetValue($itemsOrpath);
	}

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
			.{$this->Name}{
				padding: var(--Size-1);
				width: 100%;
				text-align: unset;
			}
			.{$this->Name} a,.{$this->Name} a:visited{
				color: ".\_::$TEMPLATE->ForeColor(1)."88;
			}
		");
	}

	public function Get(){
		return parent::Get().join(PHP_EOL, iterator_to_array((function(){
			yield parent::Get();
			$this->Items = getValid($this->Items,null,array());
			if(count($this->Items)<1 && isValid($this->Path)){
				$host = "";
				$paths = preg_split("/(?<=[^\\/\\\])\\//i",$this->Path);
				$host = $paths[0];
				$this->Items[trim($host,"/\\")] = $host;
				foreach (array_slice($paths,1) as $value)
					$this->Items[trim($value,"/\\")] = $host.= "/".$value;
			}
			if(count($this->Items) > 1){
				$route = "<a href='".array_values($this->Items)[0]."'>".__($this->RootLabel??array_keys($this->Items)[0],true,false)."</a>";
				if($this->AllowUpperCase)
					foreach (array_slice($this->Items,1, $this->ShowCurrent?null:(count($this->Items)-2)) as $key=>$value)
						$route .= $this->SeparatorSymbol."<a href='".$value."'>".ucwords(__($key,true,false))."</a>";
				else
					foreach (array_slice($this->Items,1, $this->ShowCurrent?null:(count($this->Items)-2)) as $key=>$value)
						$route .= $this->SeparatorSymbol."<a href='".$value."'>".__($key,true,false)."</a>";
				yield $route;
			}
        })()));
	}

	public function SetValue($itemsOrpath = null){
		if(is_null($itemsOrpath)){
            $this->Path = getValid("/".getDirection(\_::$URL),null,"/".\_::$DIRECTION);
            $this->Items = null;
        }elseif(is_array($itemsOrpath)){
            $this->Path = null;
            $this->Items = $itemsOrpath;
        }else{
			$this->Path = $itemsOrpath;
            $this->Items = null;
        }
    }
}
?>