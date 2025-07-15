<?php
namespace MiMFa\Library;
require_once "Cryptograph.php";
class HashCrypt extends Cryptograph
{
    public $Algorithm = 'sha256';

    /**
     * Encrypts then MACs a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded string
     * @return string (raw binary)
     */
    public function Encrypt($message, $key, $encode = false)
    {
        if(!isValid($message)) return $message;
        list($encKey, $authKey) = $this->SplitKeys($key);

        // Pass to Cryptograph::encrypt
        $ciphertext = parent::Encrypt($message, $encKey);

        // Calculate a MAC of the IV and ciphertext
        $mac = hash_hmac($this->Algorithm, $ciphertext, $authKey, true);

        if ($encode) return base64_encode($mac.$ciphertext);
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
    public function Decrypt($message, $key, $decode = false)
    {
        if(!isValid($message)) return $message;
        list($encKey, $authKey) = $this->SplitKeys($key);
        if ($decode) {
            $message = base64_decode($message, true);
            if ($message === false)
                throw new \SilentException('Encryption failure');
        }

        // Hash Size -- in case HASH_ALGO is changed
        $hs = mb_strlen(hash($this->Algorithm, '', true), '8bit');
        $mac = mb_substr($message, 0, $hs, '8bit');

        $ciphertext = mb_substr($message, $hs, null, '8bit');

        $calculated = hash_hmac(
            $this->Algorithm,
            $ciphertext,
            $authKey,
            true
        );

        if (!$this->HashEquals($mac, $calculated)) {
            throw new \SilentException('Encryption failure');
        }

        // Pass to Cryptograph::decrypt
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
    protected function SplitKeys($masterKey)
    {
        if(empty($masterKey)) return ["",""];
        // You really want to implement HKDF here instead!
        return [
            hash_hmac($this->Algorithm, 'ENCRYPTION', $masterKey, true),
            hash_hmac($this->Algorithm, 'AUTHENTICATION', $masterKey, true)
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
    protected function HashEquals($a, $b)
    {
        if (function_exists('hash_equals')) {
            return hash_equals($a, $b);
        }
        $nonce = openssl_random_pseudo_bytes(32);
        return hash_hmac($this->Algorithm, $a, $nonce) === hash_hmac($this->Algorithm, $b, $nonce);
    }
}