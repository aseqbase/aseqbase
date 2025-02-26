<?php
module("CommentForm");
$mod = new \MiMFa\Module\CommentForm();
$mod->Relation = get($data, "Id");
$mod->Render();
return $mod->Result;
?>