<?php
module("SignUpForm");
$module = new \MiMFa\Module\SignUpForm();
$module->Title = "Sign Up";
$module->Image = "user";
$module->GroupOptions = table("UserGroup")->SelectPairs("`Id`", "`Title`", "`Id`>=".\_::$Config->MinimumGroupId . " AND `Id`<=".\_::$Config->MaximumGroupId);
swap($module, $data);
$module->Render();
?>