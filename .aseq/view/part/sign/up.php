<?php
module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
$module->SignatureValue = get($data, "Signature");
$module->PasswordValue =  get($data, "Password");
$module->GroupValue =  get($data, "Group");
$module->GroupOptions = table("UserGroup")->SelectPairs("`Id`", "`Title`", "`Id`>=".\_::$Config->MinimumGroupId . " AND `Id`<=".\_::$Config->MaximumGroupId);
swap($module, $data);
$module->Render();