<?php
use MiMFa\Module\Profile;
$path = \Req::$Page;
template("Main");
$templ = new \MiMFa\Template\Main();
if(!isValid($path))
    $templ->Content = function() {
        page("home");
    };
else $templ->Content = function() use($path) {
        module("Profile");
        $module = new Profile(\_::$Back->User->DataTable);
        $module->KeyColumn = "Signature";
        $table1 = \_::$Back->User->GroupDataTable->Name;
        $module->SelectQuery = "
            SELECT A.{$module->KeyColumn}, B.Title AS 'Group', A.Image, A.Name, A.Bio, A.Signature, A.Email, A.Status, A.CreateTime
            FROM {$module->DataTable->Name} AS A
            WHERE A.Id=:Id OR A.Signature=:Signature
            LEFT OUTER JOIN `$table1` AS B ON A.GroupId=B.Id;
        ";
        $module->SelectParameters = [":Id"=>$path, ":Signature"=>$path];
        $module->Updatable = false;
        $access = $module->UpdateAccess = \_::$Config->AdminAccess;
        $module->CellsTypes = [
            "Id" =>false,
            "FirstName"=>$access,
            "MiddleName"=>$access,
            "LastName"=>$access,
            "Organization"=>$access,
            "Status" =>$access,
            "Contact"=>$access,
            "Address" =>$access,
            "Path" =>$access,
            "Email"=>$access,
            "Password" =>false,
            "GroupId" =>function() {
                $std = new stdClass();
                $std->Title = "Group";
                $std->Type = "select";
                $std->Options = table("UserGroup")->DoSelectPairs("Id" , "Title" );
                return $std;
            },
            "Gender" =>["Male"=>"Male","Female"=>"Female","X"=>"X"],
            "Image" =>"Image" ,
            "Bio" =>"strings",
            "UpdateTime" =>$access,
            "CreateTime" =>$access,
            "MetaData" =>$access
        ];
        $module->Render();
    };
$templ->Render();
?>