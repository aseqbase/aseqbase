<?php
use MiMFa\Module\Table;
use MiMFa\Library\DataBase;
$path = \_::$PAGE;
TEMPLATE("Main");
$templ = new \MiMFa\Template\Main();
if(!isValid($path))
    $templ->Content = function() {
        PAGE("home");
    };
else $templ->Content = function() use($path) {
        MODULE("Table");
        $mod = new Table(\_::$CONFIG->DataBasePrefix."User");
        $mod->KeyColumn = "Signature";
        $table1 = \_::$CONFIG->DataBasePrefix."UserGroup";
        $mod->SelectQuery = "
            SELECT A.{$mod->KeyColumn}, B.Title AS 'Group', A.Image, A.Name, A.Bio, A.Signature, A.Email, A.Status, A.CreateTime
            FROM {$mod->Table} AS A
            LEFT OUTER JOIN $table1 AS B ON A.GroupID=B.ID;
        ";
        $mod->Updatable = false;
        $access = $mod->UpdateAccess = \_::$CONFIG->AdminAccess;
        $mod->CellsTypes = [
            "ID"=>false,
            "FirstName"=>$access,
            "MiddleName"=>$access,
            "LastName"=>$access,
            "Organization"=>$access,
            "Status"=>$access,
            "Contact"=>$access,
            "Address"=>$access,
            "Path"=>$access,
            "Email"=>$access,
            "Password"=>false,
            "GroupID"=>function() {
                $std = new stdClass();
                $std->Title = "Group";
                $std->Type = "select";
                $std->Options = DataBase::DoSelectPairs(\_::$CONFIG->DataBasePrefix."UserGroup", "ID", "Title");
                return $std;
            },
            "Gender"=>["Male"=>"Male","Female"=>"Female","X"=>"X"],
            "Image"=>"image",
            "Bio"=>"strings",
            "UpdateTime"=>$access,
            "CreateTime"=>$access,
            "MetaData"=>$access
        ];
        echo $mod->DoAction($path);
    };
$templ->Draw();
?>