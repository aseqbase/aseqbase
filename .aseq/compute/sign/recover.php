<?php
module("SignRecoverForm");
$module = new \MiMFa\Module\SignRecoverForm();
pod($module, $data);
$module->Render();
return $module->Result;