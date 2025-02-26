<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
set($module, $data);
$module->Item = $data;
$module->Render();
?>