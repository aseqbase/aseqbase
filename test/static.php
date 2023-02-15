<?php
     // Active Maintenace Mode
     $page  = "../aseq/static/maintenance.php";
     $value = new DateTime("tomorrow");//DateTime::createFromFormat('Y-m-d H:i', '2020-01-01 00:00');
     $title = "Under Maintenance";
     $description = "<p>Our website is currently undergoing scheduled maintenance.
                         We Should be back shortly. Thank you for your patience.</p>";
     $email = "info@mimfa.net";
     $contacts = array("<a href='mailto:$email'>$email</a>");


     $GLOBALS["StaticValue"] = $value;
     $GLOBALS["StaticTitle"] = $title;
     $GLOBALS["StaticDescription"] = $description;
     $GLOBALS["StaticEmail"] = $email;
     $GLOBALS["StaticContacts"] = $contacts;
     
     include_once($page);
?>