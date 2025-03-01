<?php
module("Forum");
$module = new \MiMFa\Module\Forum();
grab($data, "Name");// To do not change the name of module
$module->Item = $data;
set($module, $data);
$module->Render();
?>