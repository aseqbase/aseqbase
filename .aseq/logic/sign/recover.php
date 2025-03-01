<?php
module("SignRecoverForm");
$module = new \MiMFa\Module\SignRecoverForm();
swap($module, $data);
$module->Render();
return $module->Result;
?>