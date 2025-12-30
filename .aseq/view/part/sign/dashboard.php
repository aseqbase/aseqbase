<?php

use MiMFa\Library\Struct;
$user = \_::$User->Get();
if ($user)
	render(
		Struct::Style("
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
			.page .buttons * {
				gap: var(--size-2);
				text-align: center;
			}
			.page .buttons .button {
				width: 100%;
			}
	") .
		Struct::Center(
			Struct::Header(
				Struct::Media(\_::$User->Image) .
				Struct::Heading1(\_::$User->Name) .
				Struct::Paragraph(\_::$User->GetValue("Bio")) .
				Struct::$BreakLine
				,
				["class" => "introduction"]
			) .
			Struct::Container(
				[
					[
						Struct::Button("Show Profile", \_::$User->ProfileHandlerPath),
						Struct::Button("Edit Profile", \_::$User->EditHandlerPath),
						Struct::Button("Reset Password", \_::$User->RecoverHandlerPath),
						Struct::Button("Sign Out", \_::$User->OutHandlerPath)
					]
				],
				["class" => "buttons"]
			),
			["class" => "center"]
		)
	);
else
	part(\_::$User->InHandlerPath);