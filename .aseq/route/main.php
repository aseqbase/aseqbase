<?php
if (!isValid(\_::$Back->Router->Direction) && (\Req::$Direction === "home" || isEmpty(\Req::$Direction)))
    view(\_::$Config->DefaultViewName, ["Name" => "home", "Title"=>\_::$Info->FullName]);
else {
    $doc = logic( "content/get", ["Name" =>\Req::$Direction] );
    if (isEmpty($doc))
        view(\_::$Config->DefaultViewName, ["Name" => between(\_::$Back->Router->Direction, \Req::$Direction)]);
    else
        view("content", $doc);
}
?>