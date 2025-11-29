<?php

use MiMFa\Library\Struct;
if(\_::$User->HasAccess(\_::$User->GuestAccess)){
    if(\_::$User->HasAccess(\_::$User->UserAccess))
        echo Struct::Center([
                Struct::Button("Dashboard", \_::$User->DashboardHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"100" ]),
                Struct::Button("View Profile", \_::$User->ProfileHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"200" ]),
                Struct::Button("Edit Profile", \_::$User->EditHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"300" ]),
                Struct::Button("Reset Password", \_::$User->RecoverHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"400" ]),
                Struct::Button("Sign Out", \_::$User->OutHandlerPath, ["data-aos"=>"zoom-up", "data-aos-duration"=>"500" ])
            ]);
    else
        echo Struct::Center([
                Struct::Button("Sign In", \_::$User->InHandlerPath, ["data-aos"=>"zoom-left", "data-aos-duration"=>"600" ]),
                Struct::Button("Sign Up", \_::$User->UpHandlerPath, ["data-aos"=>"zoom-righ", "data-aos-duration"=>"600" ])
            ]);
}
?>