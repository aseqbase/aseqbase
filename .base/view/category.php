<?php
use MiMFa\Library\DataBase;
$paths = explode("/",trim($_GET[\_::$CONFIG->PathKey],"/\\"));
$acc = getAccess();
$parentID = (int)DataBase::DoSelectValue(
        \_::$CONFIG->DataBasePrefix."Category",
        "`".\_::$CONFIG->DataBasePrefix."Category`.`ID`",
        "(
            `".\_::$CONFIG->DataBasePrefix."Category`.`Name`=:Name
            OR `".\_::$CONFIG->DataBasePrefix."Category`.`ID`=:ID
        )  AND `Access`<=".$acc." ORDER BY `".\_::$CONFIG->DataBasePrefix."Category`.`ID` ASC LIMIT 0,1;",
    array(":Name"=>$paths[0], ":ID"=>$paths[0]), 0);
$len = count($paths);
for ($i = 0; $i < $len && !isEmpty($parentID); $i++)
    $parentID = (int)DataBase::DoSelectValue(
        \_::$CONFIG->DataBasePrefix."Category",
        "`".\_::$CONFIG->DataBasePrefix."Category`.`ID`",
        "(
            `".\_::$CONFIG->DataBasePrefix."Category`.`Name`=:Name
            OR `".\_::$CONFIG->DataBasePrefix."Category`.`ID`=:ID
        ) AND `Access`<=".$acc." AND `".\_::$CONFIG->DataBasePrefix."Category`.`ID`=ANY(
            SELECT `".\_::$CONFIG->DataBasePrefix."Category_Taxonomy`.`ChildID`
            FROM `".\_::$CONFIG->DataBasePrefix."Category_Taxonomy`
            WHERE `".\_::$CONFIG->DataBasePrefix."Category_Taxonomy`.`ParentID`=$parentID
        ) LIMIT 0,1;",
        array(":Name"=>$paths[$i], ":ID"=>$paths[$i]),
        $parentID
    );
$items = $parentID === null?array():DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Category","*","ID=:ID",[":ID"=>$parentID]);
if(count($items)<1){
    VIEW("404");
    return;
}
$doc = $items[0];
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = (getValid($doc,"Title")??getValid($doc,"Name"))." - ".\_::$INFO->Name;
$templ->Content = function() use($doc,$acc){
    MODULE("Page");
    $module = new \MiMFa\Module\Page();
    $module->Item = $doc;
    $module->Draw();
    MODULE("PostCollection");
    $module = new \MiMFa\Module\PostCollection();
    $module->ShowRoute = false;
    $module->DefaultImage = \_::$INFO->FullLogoPath;
    $module->Items = DataBase::DoSelect(\_::$CONFIG->DataBasePrefix."Content","*",
        "(
            CategoryID=".$doc["ID"]." OR CategoryID=ANY(
                SELECT `".\_::$CONFIG->DataBasePrefix."Category_Taxonomy`.`ChildID`
                FROM `".\_::$CONFIG->DataBasePrefix."Category_Taxonomy`
                WHERE `".\_::$CONFIG->DataBasePrefix."Category_Taxonomy`.`ParentID`=".$doc["ID"].
            ")
        ) AND `Access`<=".$acc.";");
    $module->Draw();
};
$templ->Draw();
?>