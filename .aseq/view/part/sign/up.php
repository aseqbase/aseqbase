<?php
module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
$module->SignatureValue = get($data, "Signature");
$module->PasswordValue =  get($data, "Password");
$module->GroupValue =  get($data, "Group");
$module->GroupOptions = table("UserGroup")->SelectPairs("`Id`", "`Title`", "`Id`>=".\_::$User->MinimumGroupId . " AND `Id`<=".\_::$User->MaximumGroupId);
pod($module, $data);
$module->Render();