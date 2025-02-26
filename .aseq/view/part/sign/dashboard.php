<?php
use MiMFa\Library\User;
use MiMFa\Library\HTML;
$user = \_::$Back->User->Get();
if (isValid($user))
    echo Html::Style("
			.page header.header.introduction {
				text-align: center;
				margin: var(--size-1);
				margin-bottom: var(--size-5);
			}
			.page header.header.introduction *{
				text-align: center;
			}
			.page header.header.introduction .media {
				aspect-ratio: 1;
				width: 50%;
				max-width: 300px;
				border-radius: 100%;
				display: inline-flex;
			}
			.page .container {
				gap: var(--size-2);
			}
	").
	Html::Center(
		Html::Header(
			Html::Media(\_::$Back->User->Image).
			Html::ExternalHeading(\_::$Back->User->Name).
			Html::Paragraph(\_::$Back->User->GetValue("Bio" )).
            Html::$HorizontalBreak
		,["class"=>"introduction"]).
        Html::Container([
            [
                Html::Button("Show Profile", User::$RoutePath),
                Html::Button("Edit Profile", User::$EditHandlerPath),
                Html::Button("Reset Password", User::$RecoverHandlerPath),
                Html::Button("Sign Out", User::$OutHandlerPath)
            ]
        ]),["class"=>"page"]
    );
?>