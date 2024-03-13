<?php
namespace MiMFa\Library;
/**
 * A PHP library that handles calling Google reCAPTCHA V3.
 * reCAPTCHA v3 will never interrupt your users, so you can run it whenever you like without affecting conversion.
 * reCAPTCHA works best when it has the most context about interactions with your site, which comes from seeing both legitimate and abusive behavior.
 * For this reason, we recommend including reCAPTCHA verification on forms or actions as well as in the background of pages for analytics.
 *@copyright All rights are reserved for MiMFa Development Group
 *@author Mohammad Fathi
 *@see https://aseqbase.ir, https://github.com/aseqbase/aseqbase
 *@link https://github.com/aseqbase/aseqbase/wiki/Libraries#recaptcha See the Library Documentation
*/
class reCaptcha{
    public static string $FieldName = 'g-recaptcha-response';
    public static string $SiteKey = null;

    /**
     * Gets the JavaScript libraries to add on the client page.
     * @param string $siteKey A site key for reCAPTCHA
     * @return string - The additional heads to be embedded in the client page.
     */
	public static function GetScript(string|null $siteKey){
        $siteKey = $siteKey??self::$SiteKey??\_::$CONFIG->ReCaptchaSiteKey;
        return "<script src='https://www.google.com/recaptcha/api.js?render=".\_::$CONFIG->ReCaptchaSiteKey."'></script>".
            "<script>
	            grecaptcha.ready(function() {
		            grecaptcha.execute('".\_::$CONFIG->ReCaptchaSiteKey."', {action: 'submit'}).then(function(token) {
                        document.getElementById('".self::$FieldName."').value = token;
		            });
	            });
            </script>";
	}

    /**
     * Gets the challenge HTML (javascript and non-javascript version).
     * This is called from the browser, and the resulting reCAPTCHA HTML widget
     * is embedded within the HTML form it was called from.
     * @param string $siteKey A site key for reCAPTCHA
     * @return string - The HTML to be embedded in the user's form.
     */
	public static function GetHtml(string|null $siteKey){
        $siteKey = $siteKey??self::$SiteKey??\_::$CONFIG->ReCaptchaSiteKey;
        return "<input id='".self::$FieldName."' name='".self::$FieldName."'/>";
	}

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     * @param string $siteKey A site key for reCAPTCHA
     * @param string $remoteIp
     * @return mixed
     */
	public static function GetAnswer(string|null $siteKey, $remoteIp = null){
        $remoteIp = $remoteIp??self::$SiteKey??$_SERVER['REMOTE_ADDR'];
        $siteKey = $siteKey??\_::$CONFIG->ReCaptchaSiteKey;
        $captcha = false;
        $captcha = GRAB(self::$FieldName, "POST");
        if($captcha) return json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="."$siteKey&response=$captcha&remoteip=$remoteIp"));
	    return [];
    }

    /**
     * Check an HTTP POST function to verify if the user's guess was correct
     * @param string $siteKey A site key for reCAPTCHA
     * @param string $remoteIp
     * @return mixed
     */
	public static function CheckAnswer(string|null $siteKey, $remoteIp = null){
        $response = self::GetAnswer($siteKey, $remoteIp);
        return (getValid($response,'success',false) == true && getValid($response,'score',0) >= 0.5);
	}
}
?>