<?php
$path = implode("/", array_slice(explode("/", \Req::$Direction), 1));
if (!isValid($path) && (\Req::$Direction === "home" || isEmpty(\Req::$Direction)))
    page("home");
else {
    $doc = logic("content/get", [ "Name" => \Req::$Direction ]);
    if(isEmpty($doc)) page(between($path, \Req::$Direction));
    else view("value" , $doc);
}
?>