<?php
use MiMFa\Library\User;
use MiMFa\Library\HTML;
if(ACCESS(\_::$CONFIG->UserAccess))
    echo HTML::Style("
			.page header.header.introduction {
				text-align: center;
				margin: var(--Size-1);
				margin-bottom: var(--Size-5);
			}
			.page header.header.introduction .media {
				aspect-ratio: 1;
				width: 50%;
				max-width: 300px;
				border-radius: 100%;
				display: inline-flex;
			}
	").
	HTML::Center(
		HTML::Header(
			HTML::Media(\_::$INFO->User->Image).
			HTML::ExternalHeading(\_::$INFO->User->Name)
		,["class"=>"introduction"]).
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