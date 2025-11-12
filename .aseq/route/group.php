<?php

use MiMFa\Module\Profile;
$path = \_::$Address->Page;
template("Main");
$templ = new \MiMFa\Template\Main();
if(!isValid($path))
    $templ->Content = function(){
        page("home");
    };
else
    $templ->Content = function() use($path){
        auth(\_::$User->UserAccess);
        module("Profile");
        $module = new Profile(\_::$User->GroupDataTable);
        $module->KeyColumn = "Name";
        $module->Updatable = false;
        $access = $module->UpdateAccess = \_::$User->AdminAccess;
        $module->CellsTypes = [
            "Id" =>false,
            "Status" =>$access,
            "Path" =>$access,
            "MetaData" =>$access,
            "CreateTime" =>$access,
            "UpdateTime" =>$access,
            "Access" =>function(){
                $std = new stdClass();
                $std->Type="number";
                $std->Attributes=["min"=>\_::$User->BanAccess,"max"=>\_::$User->SuperAccess];
                return $std;
            },
            "Image" =>"Image" ,
            "Description" =>"strings"
        ];
        $module->SelectCondition = "`Id`=:Id";
        $module->SelectParameters = [":Id"=>$path];
        $module->Render();
    };
$templ->Render();
?>