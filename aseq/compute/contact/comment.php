<?php
module("CommentForm");
$module = new \MiMFa\Module\CommentForm();
$module->Relation = get($data??null, "Id");
$module->Render();
return $module->Result;