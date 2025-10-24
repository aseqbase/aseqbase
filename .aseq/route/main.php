<?php
$viewData = pop($data, "View");
$computeData = pop($data, "Compute");
if (\_::$Address->Direction === "home" || isEmpty(\_::$Address->Direction))
    view(pop($viewData, "ViewName")??\_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name")??"home", "Title"=>pop($viewData,  "Title")??\_::$Info->FullName,...($viewData??[])]);
else {
    $doc = compute( pop($computeData, "ComputeName")??"content/get", ["Name" =>pop($computeData, "Name")??\_::$Address->Direction, ...($computeData??[])] );
    if (isEmpty($doc))
        view(pop($viewData, "ViewName")??\_::$Front->DefaultViewName, ["Name" => pop($viewData, "Name")??between(\_::$Address->Direction, \_::$Address->Direction),...($viewData??[])]);
    else
        view(pop($viewData, "ViewName")??"content", $doc);
}
?>