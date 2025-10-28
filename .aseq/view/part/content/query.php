<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
$name = $module->Name;
$module->CheckAccess = fn($item)=>\_::$User->GetAccess(\_::$User->AdminAccess) || \_::$User->GetAccess(\MiMFa\Library\Convert::ToSequence(\MiMFa\Library\Convert::FromJson(getValid($item, 'Access' , \_::$User->VisitAccess))));
$module->Item = $data;
$module->CommentForm->SubjectLabel = null;
$module->Root = "/query/";
pod($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
?>