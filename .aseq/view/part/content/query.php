<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
$name = $module->Name;
$module->CheckAccess = fn($item)=>\_::$User->Access(\_::$Config->AdminAccess) || \_::$User->Access(\MiMFa\Library\Convert::ToSequence(\MiMFa\Library\Convert::FromJson(getValid($item, 'Access' , \_::$Config->VisitAccess))));
$module->Item = $data;
$module->CommentForm->SubjectLabel = null;
$module->Root = "/query/";
swap($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
?>