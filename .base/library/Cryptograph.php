<?php
namespace MiMFa\Library;
class Cryptograph
{
    public $Method = 'aes-256-ctr';


    /**
     * Encrypts (but does not authenticate) a message
     *
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded
     * @return string (raw binary)
     */
    public function Encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length($this->Method);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            $this->Method,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) return base64_encode($nonce.$ciphertext);
        return $nonce.$ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     *
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */
    public function Decrypt($message, $key, $decode = false)
    {
        if ($decode) {
            $message = base64_decode($message, true);
            if ($message === false) throw new \SilentException('Decoding failure');
        }

        $nonceSize = openssl_cipher_iv_length($this->Method);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            $this->Method,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }
}
?>