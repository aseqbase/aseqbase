<?php
ACCESS(\_::$CONFIG->AdminAccess);
use MiMFa\Module\Table;
use MiMFa\Library\DataBase;
MODULE("Table");
$mod = new Table(\_::$CONFIG->DataBasePrefix."UserGroup");
PART("dbfilters.php");
$mod->SelectQuery = Units_Create_Select_Query();
$mod->ColumnsKeys = ["Title"];
$mod->ExcludeColumnKeys = ["ID", "UnitName", "MetaData"];
$mod->Updatable = true;
$mod->AllowServerSide = true;
$mod->UpdateAccess = \_::$CONFIG->AdminAccess;
$mod->CellTypes = [
    "ID"=>"number",
    "TargetIDs"=>function($t, $v){
        $std = new stdClass();
        $std->Title="Destination Units";
        $std->Type="array";
        $std->Options = ["type"=>"select", "key"=>"TargetIDs", "options"=> DataBase::DoSelectPairs(\_::$CONFIG->DataBasePrefix."UserGroup", "ID", "Title")];
        return $std;
    },
    "Access"=>function(){
        $std = new stdClass();
        $std->Type="number";
        $std->Attributes=["min"=>\_::$CONFIG->BanAccess,"max"=>\_::$CONFIG->UserAccess];
        return $std;
    },
    "WeightUpTolerance"=>function(){
        $std = new stdClass();
        $std->Type="float";
        $std->Description="Roof of the accepted added weight factor!";
        $std->Attributes=["min"=>0,"max"=>1,"step"=>"".(1/pow(10,\_::$INFO->DecimalPrecision))];
        return $std;
    },
    "WeightDownTolerance"=>function(){
        $std = new stdClass();
        $std->Type="float";
        $std->Description="Floor of the accepted subtracted weight factor!";
        $std->Attributes=["min"=>-1,"max"=>0,"step"=>"".(1/pow(10,\_::$INFO->DecimalPrecision))];
        return $std;
    },
    "WorkMaxTolerance"=>function(){
        $std = new stdClass();
        $std->Type="float";
        $std->Description="Maximum weight, which can works per each one time unit!";
        $std->Attributes=["min"=>0,"max"=>999999999];
        return $std;
    },
    "WorkMinTolerance"=>function(){
        $std = new stdClass();
        $std->Type="float";
        $std->Description="Minimum weight, which can works per each one time unit!";
        $std->Attributes=["min"=>0,"max"=>999999999];
        return $std;
    },
    "HasCountError"=>function(){
        $std = new stdClass();
        $std->Type="checkbox";
        $std->Description="Show count errors!";
        return $std;
    },
    "HasWeightError"=>function(){
        $std = new stdClass();
        $std->Type="checkbox";
        $std->Description="Show weight errors!";
        return $std;
    },
    "IsActive"=>function(){
        $std = new stdClass();
        $std->Type="checkbox";
        $std->Description="This unit is effective on timing and working or not!";
        return $std;
    },
    "Status"=>[-1=>"Blocked",0=>"Undifined",1=>"Activated"],
    "MetaData"=> getAccess(\_::$CONFIG->AdminAccess)?function(){
        $std = new stdClass();
        $std->Type = "json";
        return $std;
    }:false
    ];
$mod->CellValues = [
    "Title"=>function($v, $k, $r){ return \_::$INFO->GetUnitValue($v, $k, $r);},
    "CreateTime"=>function($v, $k, $r){ return \_::$CONFIG->ToShownFormattedDateTime($v);},
    "UpdateTime"=>function($v, $k, $r){ return \_::$CONFIG->ToShownFormattedDateTime($v);}
];
$mod->Draw();
?>