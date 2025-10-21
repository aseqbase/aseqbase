<?php

use MiMFa\Library\Html;
$user = \_::$User->Get();
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
				background-color: var(--back-color-input);
				aspect-ratio: 1;
				width: 50%;
				max-width: 300px;
				border: var(--border-4) var(--back-color-input);
				border-radius: var(--radius-max);
				box-shadow: var(--shadow-0);
				display: inline-flex;
			}
			.page .container {
				gap: var(--size-2);
			}
	").
	Html::Center(
		Html::Header(
			Html::Media(\_::$User->Image).
			Html::Heading1(\_::$User->Name).
			Html::Paragraph(\_::$User->GetValue("Bio" )).
            Html::$BreakLine
		,["class"=>"introduction"]).
        Html::Container([
            [
                Html::Button("Show Profile", \_::$User->ProfileHandlerPath),
                Html::Button("Edit Profile", \_::$User->EditHandlerPath),
                Html::Button("Reset Password", \_::$User->RecoverHandlerPath),
                Html::Button("Sign Out", \_::$User->OutHandlerPath)
            ]
        ]),["class"=>"page"]
    );
?>