<?php
grab($data, "Order");
return compute("content/all",["Order"=>"`Priority` DESC", ...$data]);
?>