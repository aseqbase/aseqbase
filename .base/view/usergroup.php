<?php
use MiMFa\Module\Table;
$path = \_::$PAGE;
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
if(!isValid($path))
    $templ->Content = function(){
        PAGE("home");
    };
else
    $templ->Content = function() use($path){
        ACCESS(\_::$CONFIG->UserAccess);
        MODULE("Table");
        $mod = new Table(\_::$CONFIG->DataBasePrefix."UserGroup");
        $mod->KeyColumn = "Name";
        $mod->Updatable = false;
        $access = $mod->UpdateAccess = \_::$CONFIG->AdminAccess;
        $mod->CellsTypes = [
            "ID"=>false,
            "Status"=>$access,
            "Path"=>$access,
            "MetaData"=>$access,
            "CreateTime"=>$access,
            "UpdateTime"=>$access,
            "Access"=>function(){
                $std = new stdClass();
                $std->Type="number";
                $std->Attributes=["min"=>\_::$CONFIG->BanAccess,"max"=>\_::$CONFIG->UserAccess];
                return $std;
            },
            "Image"=>"image",
            "Description"=>"strings"
        ];
        echo $mod->DoAction($path);
    };
$templ->Draw();
?>