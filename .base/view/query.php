<?php
use MiMFa\Library\DataBase;
$paths = explode("/",trim($_GET[\_::$CONFIG->PathKey],"/\\"));
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
$items = $parentID === null?array():DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Group","*","`ID`=$parentID");
if(count($items)<1){
    echo $parentID.$acc.\_::$CONFIG->DataBasePrefix;
    if(isEmpty($parentID) || $parentID == "%") VIEW("404");
    else VIEW("401");
    return;
}
$doc = $items[0];
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = (getValid($doc,"Title")??getValid($doc,"Name"))." - ".\_::$INFO->Name;
$templ->Content = function() use($doc){
    MODULE("Page");
    $module = new \MiMFa\Module\Page();
    $module->Item = $doc;
    $module->Draw();
    MODULE("PostCollection");
    $params = array();
    $condit = "";
    if(isValid($_GET,"type")){
        $params[":Type"] = $_GET["type"];
        $condit .= " AND `Type`=:Type";
    }
    $module = new \MiMFa\Module\PostCollection();
    $module->ShowRoute = false;
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content","*",
        "GroupIDs LIKE \"%|".$doc["ID"]."%\" AND `Access`<=".getAccess()." $condit", $params);
    $module->Draw();
};
$templ->Draw();
?>