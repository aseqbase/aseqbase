<?php namespace MiMFa\Module;
/**
 * Automatic crate route map through url or item iterations
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/mimfa/aseqbase
 *@link https://github.com/mimfa/aseqbase/wiki/Modules See the Documentation
 */
class Route extends Module{
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
     * Convert the first character of each word in a string to uppercase
     * @var bool
     */
	public $AllowUpperCase = true;

	public function __construct($itemsOrpath = null){
		parent::__construct();
		$this->SetValue($itemsOrpath);
	}

	public function EchoStyle(){
		parent::EchoStyle(); ?>
		<style>
			.<?php echo $this->Name; ?>{
				padding: var(--Size-1);
				width: 100%;
				text-align: unset;
			}
			.<?php echo $this->Name; ?> a,.<?php echo $this->Name; ?> a:visited{
				color: <?php echo \_::$TEMPLATE->ForeColor(1)."88";?>;
			}

		</style>
		<?php
	}

	public function Echo(){
		parent::Echo();
		$this->Items = getValid($this->Items,null,array());
		if(count($this->Items)<1 && isValid($this->Path)){
            $host = "";
            $paths = preg_split("/(?<=[^\\/\\\])\\//i",$this->Path);
			foreach ($paths as $value)
                $this->Items[trim($value,"/\\")] = $host.= $value."/";
        }
		if(count($this->Items) > 0){
			$route = "<a href='".array_values($this->Items)[0]."'>".__($this->RootLabel??array_keys($this->Items)[0],true,false)."</a>";
			if($this->AllowUpperCase)
				foreach (array_slice($this->Items,1) as $key=>$value)
					$route .= $this->SeparatorSymbol."<a href='".$value."'>".ucwords(__($key,true,false))."</a>";
			else
				foreach (array_slice($this->Items,1) as $key=>$value)
					$route .= $this->SeparatorSymbol."<a href='".$value."'>".__($key,true,false)."</a>";
			echo $route;
        }
	}

	public function SetValue($itemsOrpath = null){
		if(is_null($itemsOrpath)){
            $this->Path = getValid(getDirection(\_::$URL),null,"/".$_GET[\_::$CONFIG->PathKey]);
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