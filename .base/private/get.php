<?php
$requests = $_POST;
if(($source = getValid($requests,"view")) != null){
    $doc = VIEW($source = NormalizePath($source), print:false);
} elseif(($source = getValid($requests,"page")) != null){
    $doc = PAGE($source = NormalizePath($source), print:false);
} elseif(($source = getValid($requests,"part")) != null){
    $doc = PART($source = NormalizePath($source), print:false);
} elseif(($source = getValid($requests,"region")) != null){
    $doc = REGION($source = NormalizePath($source), print:false);
} elseif(($source = getValid($requests,"virtual")) != null){
    $doc = VIRTUAL($source = NormalizePath($source));
}
if(isValid($doc)){
    //if(($query = getValid($requests,"query")) != null){
    //    //Select slice of $doc
    //}
    //else
        echo $doc;
}
?>