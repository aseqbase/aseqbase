<?php
$viewData = grab($data, "View");
$computeData = grab($data, "Compute");
$path = implode("/", array_slice(explode("/", \_::$Address->Direction), 1));
if (!isValid($path) && (\_::$Address->Direction === "home" || isEmpty(\_::$Address->Direction)))
    page("home");
else {
    $doc = compute(get($computeData, "ComputeName")??"content/get", [ "Name" => get($computeData, "Name")??\_::$Address->Direction,...($computeData??[]) ]);
    if(isEmpty($doc)) page(between($path, \_::$Address->Direction));
    else view(get($viewData, "ViewName")??"value" , $doc);
}
?>