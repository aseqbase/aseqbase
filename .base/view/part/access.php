<?php
use MiMFa\Library\User;
use MiMFa\Library\HTML;
if(getAccess(\_::$CONFIG->GuestAccess)){
    if(getAccess(\_::$CONFIG->UserAccess))
        echo HTML::Center([
                HTML::Button("Dashboard", User::$DashboardHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"100" ]),
                HTML::Button("View Profile", User::$ViewHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"200" ]),
                HTML::Button("Edit Profile", User::$EditHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"300" ]),
                HTML::Button("Reset Password", User::$RecoverHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"400" ]),
                HTML::Button("Sign Out", User::$OutHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"500" ])
            ]);
    else
        echo HTML::Center([
                HTML::Button("Sign In", User::$InHandlerPath, ["data-aos"=>"zoom-left", "data-aos-duration"=>"600" ]),
                HTML::Button("Sign Up", User::$UpHandlerPath, ["data-aos"=>"zoom-righ", "data-aos-duration"=>"600" ])
            ]);
}
?>