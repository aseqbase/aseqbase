<?php
namespace MiMFa\Module;
use MiMFa\Library\Html;
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
                  if (!\Req::ReceiveGet($this->FromTimeRequest) && isValid($fromTime))
                        self::$FromTime = $this->FromTimeValue = Convert::ToDateTime($fromTime)->format('Y-m-d H:i:s');
                  if (!\Req::ReceiveGet($this->ToTimeRequest) && isValid($toTime))
                        self::$ToTime = $this->ToTimeValue = Convert::ToDateTime($toTime)->format('Y-m-d H:i:s');
            }
            $fromId = $this->Name . "_" . $this->FromTimeRequest;
            $toId = $this->Name . "_" . $this->ToTimeRequest;
            $queries = preg_replace("/(^|&)({$this->FromTimeRequest}|{$this->ToTimeRequest}|&+)\=[^&]*(?=$|&)/i", "", "" . \Req::$Query);
            $this->Children = [
                  Html::Field(type: "Calendar", title: $this->FromTimeLable, value: \Req::Receive($this->FromTimeRequest, "GET", Convert::ToDateTimeString($fromTime)), key: $this->FromTimeRequest, attributes: ["Id" => $fromId]) .
                  Html::Field(type: "Calendar", title: $this->ToTimeLable, value: \Req::Receive($this->ToTimeRequest, "GET", Convert::ToDateTimeString($toTime)), key: $this->ToTimeRequest, attributes: ["Id" => $toId]) .
                  Html::Button("Show", "load(`" . \Req::$Path . "?" . (isEmpty($queries) ? "" : "{$queries}&") . "{$this->FromTimeRequest}=`+(document.getElementById(`$fromId`).value+'').replace('T',' ')+`&{$this->ToTimeRequest}=`+(document.getElementById(`$toId`).value+'').replace('T',' '));")
            ];
      }

      public function GetStyle()
      {
            return parent::GetStyle() . Html::Style("
		.{$this->Name}{
            display: block;
            text-align: center;
		}
		.{$this->Name} .field{
            display: inline-block;
            margin: 1vmin 1vmax;
		}
		.{$this->Name} .field .input{
            background-color: var(--back-color-inside);
            color: var(--fore-color-inside);
		}
		.{$this->Name} .field .button{
            display: inline-block;
		}
		");
      }
}
?>