<?php
use MiMFa\Module\MessageCollection;
use MiMFa\Module\MessageForm;
$action = get($data, "Action");
$rel = get($data, "Relation") ?? \_::$Address->UrlRoute;
module("MessageCollection");
$colModule = new MessageCollection(table("Message")->Select("*", "Relation IS NULL OR Relation=:Rel", [":Rel" => $rel]));
$colModule->Action = $action;
$colModule->Render();
if (get($data, "Active") !== false) {
    module("MessageForm");
    $module = new MessageForm($rel);
    $module->Action = $action;
    $module->From = get($data, "From");
    $module->To = get($data, "To");
    $module->Target = get($data, "Target") ?? $colModule->Name;
    pod($module, $data);
    $module->Render();
}