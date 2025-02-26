<?php
return logic("content/all",[...$data, "Order"=>"`UpdateTime` DESC"]);
?>