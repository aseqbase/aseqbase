<?php
if(isValid(\_::$Config->ReCaptchaSiteKey)) {
    library("recaptcha");
    \_::$Front->Initials[] = \MiMFa\Library\reCaptcha::GetScript(\_::$Config->ReCaptchaSiteKey);
}
?>