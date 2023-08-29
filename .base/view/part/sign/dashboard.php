<?php
use MiMFa\Library\User;
use MiMFa\Library\HTML;
if(ACCESS(\_::$CONFIG->UserAccess))
    echo HTML::Center(
        HTML::Container([
            [
                HTML::Button("Show Profile", User::$ViewHandlerPath),
                HTML::Button("Edit Profile", User::$EditHandlerPath),
                HTML::Button("Reset Password", User::$RecoverHandlerPath),
                HTML::Button("Sign Out", User::$OutHandlerPath)
            ]
        ]),["class"=>"page"]
    );
?>