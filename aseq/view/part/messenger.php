<?php
use MiMFa\Module\Messenger\MessageCollection;
use MiMFa\Module\Messenger\MessageForm;
$action = get($data, "Action");
$rel = get($data, "Relation") ?? \_::$Address->UrlRoute;
module("Messenger\MessageCollection");
$colModule = new MessageCollection(table("Message")->Select("*", "Relation IS NULL OR Relation=:Rel", [":Rel" => $rel]));
$colModule->Action = $action;
$colModule->Render();
if (get($data, "Active") !== false) {
    module("Messenger\MessageForm");
    $module = new MessageForm($rel);
    $module->Action = $action;
    $module->From = get($data, "From");
    $module->To = get($data, "To");
    $module->Target = get($data, "Target") ?? $colModule->MainClass;
    pod($module, $data);
    $module->Render();
}