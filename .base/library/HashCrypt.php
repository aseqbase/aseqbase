<?php
namespace MiMFa\Library;
require_once "SimpleCrypt.php";
class HashCrypt extends SimpleCrypt
{
    public static $Algorithm = 'sha256';

    /**
     * Encrypts then MACs a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded string
     * @return string (raw binary)
     */
    public static function Encrypt($message, $key, $encode = false)
    {
        if(!isValid($message)) return $message;
        list($encKey, $authKey) = self::SplitKeys($key);

        // Pass to SimpleCrypt::encrypt
        $ciphertext = parent::Encrypt($message, $encKey);

        // Calculate a MAC of the IV and ciphertext
        $mac = hash_hmac(self::$Algorithm, $ciphertext, $authKey, true);

        if ($encode) {
            return base64_encode($mac.$ciphertext);
        }
        // Prepend MAC to the ciphertext and return to caller
        return $mac.$ciphertext;
    }

    /**
     * Decrypts a message (after verifying integrity)
     *
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string (raw binary)
     */
    public static function Decrypt($message, $key, $encoded = false)
    {
        if(!isValid($message)) return $message;
        list($encKey, $authKey) = self::SplitKeys($key);
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new \Exception('Encryption failure');
            }
        }

        // Hash Size -- in case HASH_ALGO is changed
        $hs = mb_strlen(hash(self::$Algorithm, '', true), '8bit');
        $mac = mb_substr($message, 0, $hs, '8bit');

        $ciphertext = mb_substr($message, $hs, null, '8bit');

        $calculated = hash_hmac(
            self::$Algorithm,
            $ciphertext,
            $authKey,
            true
        );

        if (!self::HashEquals($mac, $calculated)) {
            throw new \Exception('Encryption failure');
        }

        // Pass to SimpleCrypt::decrypt
        $plaintext = parent::Decrypt($ciphertext, $encKey);

        return $plaintext;
    }

    /**
     * Splits a key into two separate keys; one for encryption
     * and the other for authenticaiton
     *
     * @param string $masterKey (raw binary)
     * @return array (two raw binary strings)
     */
    protected static function SplitKeys($masterKey)
    {
        if(empty($masterKey)) return ["",""];
        // You really want to implement HKDF here instead!
        return [
            hash_hmac(self::$Algorithm, 'ENCRYPTION', $masterKey, true),
            hash_hmac(self::$Algorithm, 'AUTHENTICATION', $masterKey, true)
        ];
    }

    /**
     * Compare two strings without leaking timing information
     *
     * @param string $a
     * @param string $b
     * @ref https://paragonie.com/b/WS1DLx6BnpsdaVQW
     * @return boolean
     */
    protected static function HashEquals($a, $b)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($a, $b);
        }
        $nonce = openssl_random_pseudo_bytes(32);
        return hash_hmac(self::$Algorithm, $a, $nonce) === hash_hmac(self::$Algorithm, $b, $nonce);
    }
}
?>