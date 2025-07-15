<?php
namespace MiMFa\Library;
require_once "HashCrypt.php";
class SpecialCrypt extends HashCrypt
{
    public Cryptograph $Cryptograph;

    function __construct(){
        $this->Cryptograph = new Cryptograph();
    }

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
        $ciphertext = $this->Cryptograph->Encrypt($this->AddSampleChars($message), $encKey);

        // Calculate a MAC of the IV and ciphertext
        $mac = hash_hmac($this->Algorithm, $ciphertext, $authKey, true);

        if ($encode)
            return base64_encode($mac.$ciphertext);
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
                throw new \SilentException('Decoding failure');
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
            throw new \SilentException('Decryption failure');
        }

        // Pass to Cryptograph::decrypt
        return $this->RemoveSampleChars( $this->Cryptograph->Decrypt($ciphertext, $encKey));
    }

    protected function AddSampleChars($text)
    {
        $sampler = \_::$Config->EncryptSampler;
        $samplechars = \_::$Config->EncryptSampleChars;
        $samplelen = strlen($samplechars);
        $indexer = \_::$Config->EncryptIndexer;
        for (; $indexer < strlen($text); $indexer+=\_::$Config->EncryptIndexer){
            $text = insertToString($text, substr($samplechars, $sampler%$samplelen,1), $indexer);
            $sampler+=\_::$Config->EncryptSampler;
        }
        return $text;
    }
    protected function RemoveSampleChars($text)
    {
        $indexer = strlen($text) - strlen($text)%\_::$Config->EncryptIndexer;
        for (; $indexer > 0 ; $indexer-=\_::$Config->EncryptIndexer)
            $text = deleteFromString($text, $indexer, 1);
        return $text;
    }
}