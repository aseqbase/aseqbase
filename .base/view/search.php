<?php
use MiMFa\Library\DataBase;
$paths = explode("/",trim(\_::$DIRECTION,"/\\"));
$parentID = "%";
$len = count($paths);
$acc = getAccess();
for ($i = 0; $i < $len && !isEmpty($parentID); $i++)
    $parentID = DataBase::DoSelectValue(
        \_::$CONFIG->DataBasePrefix."Group",
        "`ID`",
        "`Name`=:Name AND `ID` LIKE '".$parentID."%' AND `Access`<=".$acc." ORDER BY `ID` LIMIT 0,1;",
        array(":Name"=>$paths[$i]),
        $parentID
    );
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = __("Results")." - ".\_::$INFO->Name;
$templ->Content = function() use($parentID,$acc){
    $params = array();
    $condit = "";
    if(!isEmpty($parentID) && $parentID !== "%") {
        $condit .= " AND GroupIDs LIKE \"%|".$parentID."%\"";
    }
    if(isValid($_GET,"type")){
        $params[":Type"] = $_GET["type"];
        $condit .= " AND `Type`=:Type";
    }
    if(isValid($_GET,"q")){
        $qs = " REGEXP '".preg_replace("/\W+/","|",$_GET["q"])."'";
        $condit .= " AND (`Title` $qs OR `Description` $qs OR `Content` $qs)";
    }
    MODULE("PostCollection");
    $module = new \MiMFa\Module\PostCollection();
    $module->ShowRoute = false;
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content","*",
        "`Access`<=".$acc." $condit", $params);
    $module->Draw();
};
$templ->Draw();
?>