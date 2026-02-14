<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
$name = $module->MainClass;
$module->CheckAccess = fn($item)=>\_::$User->HasAccess(\_::$User->AdminAccess) || \_::$User->HasAccess(\MiMFa\Library\Convert::ToSequence(\MiMFa\Library\Convert::FromJson(getValid($item, 'Access' , \_::$User->VisitAccess))));
$module->Item = $data;
$module->CommentForm->SubjectLabel = null;
$module->Root = "/query/";
pod($module, $data);
$module->MainClass = $name;// To do not change the name of module
$module->Render();
?>