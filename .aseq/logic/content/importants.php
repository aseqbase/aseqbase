<?php
grab($data, "Order");
return logic("content/all",["Order"=>"`Priority` DESC", ...$data]);
?>