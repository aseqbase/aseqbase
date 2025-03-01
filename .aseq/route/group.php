<?php

use MiMFa\Module\Profile;
$path = \Req::$Page;
template("Main");
$templ = new \MiMFa\Template\Main();
if(!isValid($path))
    $templ->Content = function(){
        page("home");
    };
else
    $templ->Content = function() use($path){
        inspect(\_::$Config->UserAccess);
        module("Profile");
        $module = new Profile(\_::$Back->User->GroupDataTable);
        $module->KeyColumn = "Name";
        $module->Updatable = false;
        $access = $module->UpdateAccess = \_::$Config->AdminAccess;
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
                $std->Attributes=["min"=>\_::$Config->BanAccess,"max"=>\_::$Config->UserAccess];
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