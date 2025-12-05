<?php
$viewData = pop($data, "View");
if (\_::$User->Direction === "home" || isEmpty(\_::$User->Direction))
    view(pop($viewData, "ViewName") ?? \_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name") ?? "home", "Title" => pop($viewData, "Title") ?? \_::$Info->FullName, ...($viewData ?? [])]);
else {
    $computeData = pop($data, "Compute");
    $doc = compute(pop($computeData, "ComputeName") ?? "content/get", ["Name" => pop($computeData, "Name") ?? \_::$User->Direction, ...($computeData ?? [])]);
    if (isEmpty($doc))
        view(pop($viewData, "ViewName") ?? \_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name") ?? between(\_::$User->Direction, \_::$User->Direction), ...($viewData ?? [])]);
    else
        view(pop($viewData, "ViewName") ?? "content", $doc);
}
?>