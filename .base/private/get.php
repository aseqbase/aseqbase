<?php
$requests = \Req::Post();
if(($source = get($requests,"view")) != null){
    $doc = view($source = NormalizePath($source), print:false);
} elseif(($source = get($requests,"page")) != null){
    $doc = page($source = NormalizePath($source), print:false);
} elseif(($source = get($requests,"part")) != null){
    $doc = part($source = NormalizePath($source), print:false);
} elseif(($source = get($requests,"region")) != null){
    $doc = region($source = NormalizePath($source), print:false);
}
if(isValid($doc)){
    //if(($query = get($requests,"Query")) != null){
    //    //Select slice of $doc
    //}
    //else
        echo $doc;
}
?>