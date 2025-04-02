<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
$name = $module->Name;
$module->CheckAccess = fn($item)=>\_::$Back->User->Access() == getValid($item, 'Access' , 0);
$module->Item = $data;
$module->CommentForm->SubjectLabel = null;
$module->RootRoute = "/query/";
set($module, $data);
$module->Name = $name;// To do not change the name of module
$module->Render();
?>