<?php
namespace MiMFa\Module;
use MiMFa\Library\Struct;
use MiMFa\Library\Convert;
class PeriodPicker extends Module
{
      public $FromTimeLable = "From Time";
      public $FromTimeRequest = "fromTime";
      public static $FromTimeValue = null;
      public $FromTime = null;
      public $ToTimeLable = "To Time";
      public $ToTimeRequest = "toTime";
      public $ToTimeValue = null;
      public static $ToTime = null;

      /**
       * Create the module
       * @param string|null $source The module source
       */
      public function __construct($setDefault = false, $fromTime = "today 00:00:00", $toTime = "today 23:59:59")
      {
            parent::__construct();
            if ($setDefault) {
                  if (!receiveGet($this->FromTimeRequest) && isValid($fromTime))
                        self::$FromTime = $this->FromTimeValue = Convert::ToDateTime($fromTime)->format('Y-m-d H:i:s');
                  if (!receiveGet($this->ToTimeRequest) && isValid($toTime))
                        self::$ToTime = $this->ToTimeValue = Convert::ToDateTime($toTime)->format('Y-m-d H:i:s');
            }
            $fromId = $this->MainClass . "_" . $this->FromTimeRequest;
            $toId = $this->MainClass . "_" . $this->ToTimeRequest;
            $queries = preg_replace("/(^|&)({$this->FromTimeRequest}|{$this->ToTimeRequest}|&+)\=[^&]*(?=$|&)/i", "", "" . \_::$Address->UrlQuery);
            $this->Items = [
                  Struct::Field(type: "Calendar", title: $this->FromTimeLable, value: receiveGet($this->FromTimeRequest, Convert::ToDateTimeString($fromTime)), key: $this->FromTimeRequest, attributes: ["Id" => $fromId]) .
                  Struct::Field(type: "Calendar", title: $this->ToTimeLable, value: receiveGet($this->ToTimeRequest, Convert::ToDateTimeString($toTime)), key: $this->ToTimeRequest, attributes: ["Id" => $toId]) .
                  Struct::Button("Show", "load(`" . \_::$Address->UrlBase . "?" . (isEmpty($queries) ? "" : "{$queries}&") . "{$this->FromTimeRequest}=`+(document.getElementById(`$fromId`).value+'').replace('T',' ')+`&{$this->ToTimeRequest}=`+(document.getElementById(`$toId`).value+'').replace('T',' '));")
            ];
      }

      public function GetStyle()
      {
        yield parent::GetStyle();
        yield Struct::Style("
		.{$this->MainClass}{
            display: block;
            text-align: center;
		}
		.{$this->MainClass} .field{
            display: inline-block;
            margin: 1vmin 1vmax;
		}
		.{$this->MainClass} .field .input{
            background-color: var(--back-color-input);
            color: var(--fore-color-input);
		}
		.{$this->MainClass} .field .button{
            display: inline-block;
		}
		");
      }
}