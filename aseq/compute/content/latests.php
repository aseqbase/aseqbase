<?php
pop($data, "Order");
return compute("content/all",["Order"=>"`UpdateTime` DESC", ...$data]);
?>