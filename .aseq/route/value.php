<?php
$viewData = grab($data, "View");
$logicData = grab($data, "Compute");
$path = implode("/", array_slice(explode("/", \Req::$Direction), 1));
if (!isValid($path) && (\Req::$Direction === "home" || isEmpty(\Req::$Direction)))
    page("home");
else {
    $doc = compute(get($logicData, "ComputeName")??"content/get", [ "Name" => get($logicData, "Name")??\Req::$Direction,...($logicData??[]) ]);
    if(isEmpty($doc)) page(between($path, \Req::$Direction));
    else view(get($viewData, "ViewName")??"value" , $doc);
}
?>