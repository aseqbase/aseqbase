<?php
$viewData = grab($data, "View");
$computeData = grab($data, "Compute");
$path = implode("/", array_slice(explode("/", \_::$Direction), 1));
if (!isValid($path) && (\_::$Direction === "home" || isEmpty(\_::$Direction)))
    page("home");
else {
    $doc = compute(get($computeData, "ComputeName")??"content/get", [ "Name" => get($computeData, "Name")??\_::$Direction,...($computeData??[]) ]);
    if(isEmpty($doc)) page(between($path, \_::$Direction));
    else view(get($viewData, "ViewName")??"value" , $doc);
}
?>