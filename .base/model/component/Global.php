<?php

use MiMFa\Library\Html;
\_::$Front->Libraries[] = Html::Style(null, forceFullUrl('/view/style/reset.css'));
\_::$Front->Libraries[] = Html::Style(null, forceFullUrl('/view/style/general.css'));
\_::$Front->Libraries[] = Html::Style(null, forceFullUrl('/view/style/be.css'));
\_::$Front->Libraries[] = Html::Style("
:root{
	--color-0: " . \_::$Front->Color(0) . ";
	--color-1: " . \_::$Front->Color(1) . ";
	--color-2: " . \_::$Front->Color(2) . ";
	--color-3: " . \_::$Front->Color(3) . ";
	--color-4: " . \_::$Front->Color(4) . ";
	--color-5: " . \_::$Front->Color(5) . ";
	--color-6: " . \_::$Front->Color(6) . ";
	--color-7: " . \_::$Front->Color(7) . ";
	--fore-color-0: " . \_::$Front->ForeColor(0) . ";
	--fore-color-1: " . \_::$Front->ForeColor(1) . ";
	--fore-color-2: " . \_::$Front->ForeColor(2) . ";
	--fore-color-3: " . \_::$Front->ForeColor(3) . ";
	--fore-color-4: " . \_::$Front->ForeColor(4) . ";
	--fore-color-5: " . \_::$Front->ForeColor(5) . ";
	--back-color-0: " . \_::$Front->BackColor(0) . ";
	--back-color-1: " . \_::$Front->BackColor(1) . ";
	--back-color-2: " . \_::$Front->BackColor(2) . ";
	--back-color-3: " . \_::$Front->BackColor(3) . ";
	--back-color-4: " . \_::$Front->BackColor(4) . ";
	--back-color-5: " . \_::$Front->BackColor(5) . ";
	--font-0: " . \_::$Front->Font(0) . ";
	--font-1: " . \_::$Front->Font(1) . ";
	--font-2: " . \_::$Front->Font(2) . ";
	--font-3: " . \_::$Front->Font(3) . ";
	--font-4: " . \_::$Front->Font(4) . ";
	--font-5: " . \_::$Front->Font(5) . ";
	--size-0: " . \_::$Front->Size(0) . ";
	--size-1: " . \_::$Front->Size(1) . ";
	--size-2: " . \_::$Front->Size(2) . ";
	--size-3: " . \_::$Front->Size(3) . ";
	--size-4: " . \_::$Front->Size(4) . ";
	--size-5: " . \_::$Front->Size(5) . ";
	--shadow-0: " . \_::$Front->Shadow(0) . ";
	--shadow-1: " . \_::$Front->Shadow(1) . ";
	--shadow-2: " . \_::$Front->Shadow(2) . ";
	--shadow-3: " . \_::$Front->Shadow(3) . ";
	--shadow-4: " . \_::$Front->Shadow(4) . ";
	--shadow-5: " . \_::$Front->Shadow(5) . ";
	--border-0: " . \_::$Front->Border(0) . ";
	--border-1: " . \_::$Front->Border(1) . ";
	--border-2: " . \_::$Front->Border(2) . ";
	--border-3: " . \_::$Front->Border(3) . ";
	--border-4: " . \_::$Front->Border(4) . ";
	--border-5: " . \_::$Front->Border(5) . ";
	--radius-0: " . \_::$Front->Radius(0) . ";
	--radius-1: " . \_::$Front->Radius(1) . ";
	--radius-2: " . \_::$Front->Radius(2) . ";
	--radius-3: " . \_::$Front->Radius(3) . ";
	--radius-4: " . \_::$Front->Radius(4) . ";
	--radius-5: " . \_::$Front->Radius(5) . ";
	--transition-0: " . \_::$Front->Transition(0) . ";
	--transition-1: " . \_::$Front->Transition(1) . ";
	--transition-2: " . \_::$Front->Transition(2) . ";
	--transition-3: " . \_::$Front->Transition(3) . ";
	--transition-4: " . \_::$Front->Transition(4) . ";
	--transition-5: " . \_::$Front->Transition(5) . ";
	--overlay-0: \"" . \_::$Front->Overlay(0) . "\";
	--overlay-1: \"" . \_::$Front->Overlay(1) . "\";
	--overlay-2: \"" . \_::$Front->Overlay(2) . "\";
	--overlay-3: \"" . \_::$Front->Overlay(3) . "\";
	--overlay-4: \"" . \_::$Front->Overlay(4) . "\";
	--overlay-5: \"" . \_::$Front->Overlay(5) . "\";
	--pattern-0: \"" . \_::$Front->Pattern(0) . "\";
	--pattern-1: \"" . \_::$Front->Pattern(1) . "\";
	--pattern-2: \"" . \_::$Front->Pattern(2) . "\";
	--pattern-3: \"" . \_::$Front->Pattern(3) . "\";
	--pattern-4: \"" . \_::$Front->Pattern(4) . "\";
	--pattern-5: \"" . \_::$Front->Pattern(5) . "\";
	--overlay-url-0: URL(\"" . \_::$Front->Overlay(0) . "\");
	--overlay-url-1: URL(\"" . \_::$Front->Overlay(1) . "\");
	--overlay-url-2: URL(\"" . \_::$Front->Overlay(2) . "\");
	--overlay-url-3: URL(\"" . \_::$Front->Overlay(3) . "\");
	--overlay-url-4: URL(\"" . \_::$Front->Overlay(4) . "\");
	--overlay-url-5: URL(\"" . \_::$Front->Overlay(5) . "\");
	--pattern-url-0: URL(\"" . \_::$Front->Pattern(0) . "\");
	--pattern-url-1: URL(\"" . \_::$Front->Pattern(1) . "\");
	--pattern-url-2: URL(\"" . \_::$Front->Pattern(2) . "\");
	--pattern-url-3: URL(\"" . \_::$Front->Pattern(3) . "\");
	--pattern-url-4: URL(\"" . \_::$Front->Pattern(4) . "\");
	--pattern-url-5: URL(\"" . \_::$Front->Pattern(5) . "\");

	--owner: \"" . __(\_::$Info->Owner, true, false) . "\";
	--full-owner: \"" . __(\_::$Info->FullOwner, true, false) . "\";
	--owner-description: \"" . __(\_::$Info->OwnerDescription, true, false) . "\";
	--product: \"" . __(\_::$Info->Product, true, false) . "\";
	--full-product: \"" . __(\_::$Info->FullProduct, true, false) . "\";
	--name: \"" . __(\_::$Info->Name, true, false) . "\";
	--full-name: \"" . __(\_::$Info->FullName, true, false) . "\";
	--slogan: \"" . __(\_::$Info->Slogan, true, false) . "\";
	--full-slogan: \"" . __(\_::$Info->FullSlogan, true, false) . "\";
	--description: \"" . __(\_::$Info->Description, true, false) . "\";
	--full-description: \"" . __(\_::$Info->FullDescription, true, false) . "\";

	--path: \"" . \_::$Info->Path . "\";
	--home-path: \"" . \_::$Info->HomePath . "\";
	--logo-path: \"" . \MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath) . "\";
	--full-logo-path: \"" . \MiMFa\Library\Local::GetUrl(\_::$Info->FullLogoPath) . "\";
	--banner-path: \"" . \MiMFa\Library\Local::GetUrl(\_::$Info->BannerPath) . "\";
	--full-banner-path: \"" . \MiMFa\Library\Local::GetUrl(\_::$Info->FullBannerPath) . "\";
	--wait-symbol-path: \"" . \MiMFa\Library\Local::GetUrl(\_::$Info->WaitSymbolPath) . "\";
	--process-symbol-path: \"" . \MiMFa\Library\Local::GetUrl(\_::$Info->ProcessSymbolPath) . "\";
	--error-symbol-path: \"" . \MiMFa\Library\Local::GetUrl(\_::$Info->ErrorSymbolPath) . "\";

	--logo-path-url: URL(\"" . \MiMFa\Library\Local::GetUrl(\_::$Info->LogoPath) . "\");
	--full-logo-path-url: URL(\"" . \MiMFa\Library\Local::GetUrl(\_::$Info->FullLogoPath) . "\");
	--banner-path-url: URL(\"" . \MiMFa\Library\Local::GetUrl(\_::$Info->BannerPath) . "\");
	--full-banner-path-url: URL(\"" . \MiMFa\Library\Local::GetUrl(\_::$Info->FullBannerPath) . "\");
	--wait-symbol-path-url: URL(\"" . \MiMFa\Library\Local::GetUrl(\_::$Info->WaitSymbolPath) . "\");
	--process-symbol-path-url: URL(\"" . \MiMFa\Library\Local::GetUrl(\_::$Info->ProcessSymbolPath) . "\");
	--error-symbol-path-url: URL(\"" . \MiMFa\Library\Local::GetUrl(\_::$Info->ErrorSymbolPath) . "\");
}
");
\_::$Front->Libraries[] = Html::Script(null, forceFullUrl('/view/script/general.js'));
\_::$Front->Libraries[] = Html::Script(null, forceFullUrl('/view/script/Math.js'));
\_::$Front->Libraries[] = Html::Script(null, forceFullUrl('/view/script/Array.js'));
\_::$Front->Libraries[] = Html::Script(null, forceFullUrl('/view/script/Evaluate.js'));
\_::$Front->Libraries[] = Html::Script(null, forceFullUrl('/view/script/Html.js'));
?>