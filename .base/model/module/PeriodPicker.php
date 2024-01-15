<?php
namespace MiMFa\Module;
use MiMFa\Library\HTML;
class PeriodPicker extends Module{
	public $Capturable = true;
	public $FromTimeLable = "From Time";
	public $FromTimeRequest = "fromTime";
	public $ToTimeLable = "To Time";
	public $ToTimeRequest = "toTime";

	/**
     * Create the module
     * @param string|null $source The module source
     */
	public function __construct($setDefault = false, $fromTime = "today 00:00:00", $toTime = "today 23:59:59"){
        parent::__construct();
        if($setDefault){
            if(!isset($_GET[$this->FromTimeRequest]) && isValid($fromTime)) $_REQUEST[$this->FromTimeRequest] = $_GET[$this->FromTimeRequest] = \_::$CONFIG->GetDateTime($fromTime)->format('Y-m-d H:i:s');
            if(!isset($_GET[$this->ToTimeRequest]) && isValid($toTime)) $_REQUEST[$this->ToTimeRequest] = $_GET[$this->ToTimeRequest] = \_::$CONFIG->GetDateTime($toTime)->format('Y-m-d H:i:s');
        }
        $fromID = $this->Name."_".$this->FromTimeRequest;
        $toID = $this->Name."_".$this->ToTimeRequest;
        $this->Children = [
                HTML::Field(type:"Calendar", title: $this->FromTimeLable, value: RECEIVE($this->FromTimeRequest, "GET", \_::$CONFIG->GetFormattedDateTime($fromTime)), key: $this->FromTimeRequest, attributes:["id"=>$fromID]).
                HTML::Field(type:"Calendar", title: $this->ToTimeLable,  value: RECEIVE($this->ToTimeRequest, "GET", \_::$CONFIG->GetFormattedDateTime($toTime)), key: $this->ToTimeRequest, attributes:["id"=>$toID]).
                HTML::Button("Show", "load(`".\_::$PATH."?{$this->FromTimeRequest}=`+(document.getElementById(`$fromID`).value+'').replace('T',' ')+`&{$this->ToTimeRequest}=`+(document.getElementById(`$toID`).value+'').replace('T',' '))")
            ];
    }

	public function GetStyle(){
		return parent::GetStyle().HTML::Style("
		.{$this->Name}{
            display: block;
            text-align: center;
		}
		.{$this->Name} .field{
            display: inline-block;
            margin: 1vmin 1vmax;
		}
		.{$this->Name} .field .input{
            background-color: var(--BackColor-1);
            color: var(--ForeColor-1);
		}
		.{$this->Name} .field .button{
            display: inline-block;
		}
		");
	}
}
?>
