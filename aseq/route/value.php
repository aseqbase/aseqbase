<?php
$viewData = pop($data, "View");
$computeData = pop($data, "Compute");
$path = implode("/", array_slice(explode("/", \_::$User->Direction), 1));
if (!isValid($path) && (\_::$User->Direction === "home" || isEmpty(\_::$User->Direction)))
    page("home");
else {
    $doc = compute(get($computeData, "ComputeName")??"content/get", [ "Name" => get($computeData, "Name")??\_::$User->Direction,...($computeData??[]) ]);
    if(isEmpty($doc)) page(between($path, \_::$User->Direction));
    else view(get($viewData, "ViewName")??"value" , $doc);
}
?>