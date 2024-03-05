<?php
use MiMFa\Library\DataBase;
use MiMFa\Library\User;
$path = implode("/", array_slice(explode("/",\_::$DIRECTION),1));
if(!isValid($path)){
    TEMPLATE("Main");
    $templ = new \MiMFa\Template\Main();
    $templ->Content = function() {
        PART("post-collection");
    };
    $templ->Draw();
    return;
}
$doc = DataBase::DoSelectRow(\_::$CONFIG->DataBasePrefix."Content","*","(Name=:Name OR ID=:ID) AND ".User::GetAccessCondition(),[":Name"=>$path,":ID"=>$path]);
if(isEmpty($doc)){
    VIEW("404");
    return;
}
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
$templ->WindowTitle = [$doc['Title']];
$templ->Content = function() use($doc){
    MODULE("Post");
    $module = new \MiMFa\Module\Post();
    $module->Item = $doc;
    $module->Draw();
};
$templ->Draw();

?>