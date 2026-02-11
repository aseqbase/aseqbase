<?php
$viewData = pop($data, "View");
$computeData = pop($data, "Compute");
$path = implode("/", array_slice(explode("/", \_::$Address->UrlRoute), 1));
if (!isValid($path) && (\_::$Address->UrlRoute === "home" || isEmpty(\_::$Address->UrlRoute)))
    page("home");
else {
    $doc = compute(get($computeData, "ComputeName")??"content/get", [ "Name" => get($computeData, "Name")??\_::$Address->UrlRoute,...($computeData??[]) ]);
    if(isEmpty($doc)) page(between($path, \_::$Address->UrlRoute));
    else view(get($viewData, "ViewName")??"value" , $doc);
}
?>