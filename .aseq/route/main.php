<?php
$viewData = grab($data, "View");
$logicData = grab($data, "Compute");
if (\Req::$Direction === "home" || isEmpty(\Req::$Direction))
    view(grab($viewData, "ViewName")??\_::$Config->DefaultViewName, ["Name" => grab($viewData, "Name")??"home", "Title"=>grab($viewData,  "Title")??\_::$Info->FullName,...($viewData??[])]);
else {
    $doc = compute( grab($logicData, "ComputeName")??"content/get", ["Name" =>grab($logicData, "Name")??\Req::$Direction, ...($logicData??[])] );
    if (isEmpty($doc))
        view(grab($viewData, "ViewName")??\_::$Config->DefaultViewName, ["Name" => grab($viewData, "Name")??between(\_::$Back->Router->Direction, \Req::$Direction),...($viewData??[])]);
    else
        view(grab($viewData, "ViewName")??"content", $doc);
}
?>