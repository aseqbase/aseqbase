<?php
$viewData = grab($data, "View");
$computeData = grab($data, "Compute");
if (\_::$Address->Direction === "home" || isEmpty(\_::$Address->Direction))
    view(grab($viewData, "ViewName")??\_::$Front->DefaultViewName, ["Name" => grab($viewData, "Name")??"home", "Title"=>grab($viewData,  "Title")??\_::$Info->FullName,...($viewData??[])]);
else {
    $doc = compute( grab($computeData, "ComputeName")??"content/get", ["Name" =>grab($computeData, "Name")??\_::$Address->Direction, ...($computeData??[])] );
    if (isEmpty($doc))
        view(grab($viewData, "ViewName")??\_::$Front->DefaultViewName, ["Name" => grab($viewData, "Name")??between(\_::$Address->Direction, \_::$Address->Direction),...($viewData??[])]);
    else
        view(grab($viewData, "ViewName")??"content", $doc);
}
?>