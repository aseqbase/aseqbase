<?php
$viewData = grab($data, "View");
$computeData = grab($data, "Compute");
if (\_::$Direction === "home" || isEmpty(\_::$Direction))
    view(grab($viewData, "ViewName")??\_::$Config->DefaultViewName, ["Name" => grab($viewData, "Name")??"home", "Title"=>grab($viewData,  "Title")??\_::$Info->FullName,...($viewData??[])]);
else {
    $doc = compute( grab($computeData, "ComputeName")??"content/get", ["Name" =>grab($computeData, "Name")??\_::$Direction, ...($computeData??[])] );
    if (isEmpty($doc))
        view(grab($viewData, "ViewName")??\_::$Config->DefaultViewName, ["Name" => grab($viewData, "Name")??between(\_::$Back->Router->Direction, \_::$Direction),...($viewData??[])]);
    else
        view(grab($viewData, "ViewName")??"content", $doc);
}
?>