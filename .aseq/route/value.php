<?php
$viewData = grab($data, "View");
$computeData = grab($data, "Compute");
$path = implode("/", array_slice(explode("/", \_::$Base->Direction), 1));
if (!isValid($path) && (\_::$Base->Direction === "home" || isEmpty(\_::$Base->Direction)))
    page("home");
else {
    $doc = compute(get($computeData, "ComputeName")??"content/get", [ "Name" => get($computeData, "Name")??\_::$Base->Direction,...($computeData??[]) ]);
    if(isEmpty($doc)) page(between($path, \_::$Base->Direction));
    else view(get($viewData, "ViewName")??"value" , $doc);
}
?>