<?php
$viewData = grab($data, "View");
$computeData = grab($data, "Compute");
$path = implode("/", array_slice(explode("/", \Req::$Direction), 1));
if (!isValid($path) && (\Req::$Direction === "home" || isEmpty(\Req::$Direction)))
    page("home");
else {
    $doc = compute(get($computeData, "ComputeName")??"content/get", [ "Name" => get($computeData, "Name")??\Req::$Direction,...($computeData??[]) ]);
    if(isEmpty($doc)) page(between($path, \Req::$Direction));
    else view(get($viewData, "ViewName")??"value" , $doc);
}
?>