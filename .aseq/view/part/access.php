<?php

use MiMFa\Library\Html;
if(\_::$User->GetAccess(\_::$User->GuestAccess)){
    if(\_::$User->GetAccess(\_::$User->UserAccess))
        echo Html::Center([
                Html::Button("Dashboard", \_::$User->DashboardHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"100" ]),
                Html::Button("View Profile", \_::$User->ProfileHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"200" ]),
                Html::Button("Edit Profile", \_::$User->EditHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"300" ]),
                Html::Button("Reset Password", \_::$User->RecoverHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"400" ]),
                Html::Button("Sign Out", \_::$User->OutHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"500" ])
            ]);
    else
        echo Html::Center([
                Html::Button("Sign In", \_::$User->InHandlerPath, ["data-aos"=>"zoom-left", "data-aos-duration"=>"600" ]),
                Html::Button("Sign Up", \_::$User->UpHandlerPath, ["data-aos"=>"zoom-righ", "data-aos-duration"=>"600" ])
            ]);
}
?>