<?php
$arr = table("Guide")->DoSelect();
if(isValid($arr))
    foreach ($arr as $k=>$value)
        echo (isValid($value,"Description" )?__($value["Description" ]):"")
        .(isValid($value,"Content" )?__($value["Content" ]):"")
        .(isValid($value,"Path" )?"<a href='".$value["Path" ]."' class='btn btn-lg btn-block ".(isValid($value,"class")?$value["class"]:"")."' data-aos='fade-left'>".__($value["Title" ])."</a><br />":"");
?>