<?php
grab($data, "Order");
return logic("content/all",["Order"=>"`UpdateTime` DESC", ...$data]);
?>