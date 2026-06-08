<?php
$viewData = pop($data, "View");
if (\_::$Address->UrlRoute === "home" || isEmpty(\_::$Address->UrlRoute))
    view(pop($viewData, "ViewName") ?? \_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name") ?? "home", "Title" => pop($viewData, "Title") ?? \_::$Front->FullName, ...($viewData ?? [])]);
else {
    $computeData = pop($data, "Compute");
    $doc = compute(pop($computeData, "ComputeName") ?? "content/get", ["Name" => pop($computeData, "Name") ?? urldecode(\_::$Address->UrlRoute), ...($computeData ?? [])]);
    if (isEmpty($doc))
        view(pop($viewData, "ViewName") ?? \_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name") ?? urldecode(\_::$Address->UrlRoute), ...($viewData ?? [])]);
    else
        view(pop($viewData, "ViewName") ?? "content", $doc);
}
?>