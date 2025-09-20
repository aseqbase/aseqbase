<?php

use MiMFa\Library\Html;
if(auth(\_::$Config->GuestAccess)){
    if(auth(\_::$Config->UserAccess))
        echo Html::Center([
                Html::Button("Dashboard", \User::$DashboardHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"100" ]),
                Html::Button("View Profile", \User::$RouteHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"200" ]),
                Html::Button("Edit Profile", \User::$EditHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"300" ]),
                Html::Button("Reset Password", \User::$RecoverHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"400" ]),
                Html::Button("Sign Out", \User::$OutHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"500" ])
            ]);
    else
        echo Html::Center([
                Html::Button("Sign In", \User::$InHandlerPath, ["data-aos"=>"zoom-left", "data-aos-duration"=>"600" ]),
                Html::Button("Sign Up", \User::$UpHandlerPath, ["data-aos"=>"zoom-righ", "data-aos-duration"=>"600" ])
            ]);
}
?>