<?php
module("SignRecoverForm");
$module = new \MiMFa\Module\SignRecoverForm();
dip($module, $data);
$module->Render();
return $module->Result;
?>