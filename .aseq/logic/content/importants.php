<?php
return logic("content/all",[...$data, "Order"=>"`Priority` DESC"]);
?>