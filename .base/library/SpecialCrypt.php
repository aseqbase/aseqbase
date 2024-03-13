<?php
namespace MiMFa\Library;
require_once "HashCrypt.php";
class SpecialCrypt extends HashCrypt
{
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
        $ciphertext = SimpleCrypt::Encrypt(self::AddSampleChars($message), $encKey);

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
                throw new \Exception('Decoding failure');
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
            throw new \Exception('Decryption failure');
        }

        // Pass to SimpleCrypt::decrypt
        return self::RemoveSampleChars(SimpleCrypt::Decrypt($ciphertext, $encKey));
    }

    protected static function AddSampleChars($text)
    {
        $sampler = \_::$CONFIG->EncryptSampler;
        $samplechars = \_::$CONFIG->EncryptSampleChars;
        $samplelen = strlen($samplechars);
        $indexer = \_::$CONFIG->EncryptIndexer;
        for (; $indexer < strlen($text); $indexer+=\_::$CONFIG->EncryptIndexer){
            $text = insertToString($text, substr($samplechars, $sampler%$samplelen,1), $indexer);
            $sampler+=\_::$CONFIG->EncryptSampler;
        }
        return $text;
    }
    protected static function RemoveSampleChars($text)
    {
        $indexer = strlen($text) - strlen($text)%\_::$CONFIG->EncryptIndexer;
        for (; $indexer > 0 ; $indexer-=\_::$CONFIG->EncryptIndexer)
            $text = deleteFromString($text, $indexer, 1);
        return $text;
    }
}
?>