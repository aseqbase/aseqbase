<?php
grab($data, "Order");
return compute("content/all",["Order"=>"`UpdateTime` DESC", ...$data]);
?>