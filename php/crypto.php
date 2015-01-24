<?php

class MCrypt {

	private $hex_iv = '00000000000000000000000000000000'; # converted JAVA byte code in to HEX and placed it here
	private $key = 'U1MjU1M0FDOUZ.Qz'; #Same as in JAVA

	function __construct($passedKey) {
		$this->key = $passedKey;
		$this->key = hash('sha256', $this->key, true);
		//echo $this->key.'<br/>';
	}

	function encrypt($str) {
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		mcrypt_generic_init($td, $this->key, $this->hexToStr($this->hex_iv));
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$pad = $block - (strlen($str) % $block);
		$str .= str_repeat(chr($pad), $pad);
		$encrypted = mcrypt_generic($td, $str);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		return base64_encode($encrypted);
	}

	function checkSigniture($hmac,$crypted){
		$hmacNew = hash_hmac('sha256', $crypted,$this->key,true);
		///prevent timing attack
		if (!$this->compareStrings($hmac, $hmacNew)) {
			return false;
		}
		return true;
	}
	function compareStrings($expected, $actual)
	{
		$expected    = (string) $expected;
		$actual      = (string) $actual;
		$lenExpected = strlen($expected);
		$lenActual   = strlen($actual);
		$len         = min($lenExpected, $lenActual);
	
		$result = 0;
		for ($i = 0; $i < $len; $i++) {
			$result |= ord($expected[$i]) ^ ord($actual[$i]);
		}
		$result |= $lenExpected ^ $lenActual;
	
		return ($result === 0);
	}
	function decrypt($code) {
		try {
			$ciphertext = base64_decode($code);
			$iv = substr($ciphertext,0,16);
			$hmac = substr($ciphertext,16,32);
			$crypted = substr($ciphertext,48);
			//if ciphertext has been tampered silently return to avoid padding oracle attack
			if(!$this->checkSigniture($hmac,$crypted)) return false;
			
			$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
			mcrypt_generic_init($td, $this->key, $iv);
			$str = mdecrypt_generic($td, $crypted);
			$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			$lll =  $this->strippadding($str);
			return $lll;
			
		} catch (Exception $e) {
			return false;
		}
		
	}
	/*
	 For PKCS7 padding
	*/

	private function addpadding($string, $blocksize = 16) {
		$len = strlen($string);
		$pad = $blocksize - ($len % $blocksize);
		$string .= str_repeat(chr($pad), $pad);
		return $string;
	}

	private function strippadding($string) {
		$slast = ord(substr($string, -1));
		$slastc = chr($slast);
		$pcheck = substr($string, -$slast);
		if (preg_match("/$slastc{" . $slast . "}/", $string)) {
			$string = substr($string, 0, strlen($string) - $slast);
			return $string;
		} else {
			return false;
		}
	}
	function hexToStr($hex)
	{
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2)
		{
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
}

$sSecretKey = "AESPassword";

function fnEncrypt($sValue)
{
	global $sSecretKey;
    return rtrim(
        base64_encode(
            mcrypt_encrypt(
                MCRYPT_RIJNDAEL_256,
                $sSecretKey, $sValue, 
                MCRYPT_MODE_ECB, 
                mcrypt_create_iv(
                    mcrypt_get_iv_size(
                        MCRYPT_RIJNDAEL_256, 
                        MCRYPT_MODE_ECB
                    ), 
                    MCRYPT_RAND)
                )
            ), "\0"
        );
}

function fnDecrypt($sValue)
{
	global $sSecretKey;
    return rtrim(
        mcrypt_decrypt(
            MCRYPT_RIJNDAEL_256, 
            $sSecretKey, 
            base64_decode($sValue), 
            MCRYPT_MODE_ECB,
            mcrypt_create_iv(
                mcrypt_get_iv_size(
                    MCRYPT_RIJNDAEL_256,
                    MCRYPT_MODE_ECB
                ), 
                MCRYPT_RAND
            )
        ), "\0"
    );
}function fnDecryptWithKey($sValue,$secret)
{
	global $sSecretKey;
    return rtrim(
        mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128, 
            substr(sha1($secret),0,16), 
            base64_decode($sValue), 
            MCRYPT_MODE_ECB,
            mcrypt_create_iv(
                mcrypt_get_iv_size(
                    MCRYPT_RIJNDAEL_128,
                    MCRYPT_MODE_ECB
                ), 
                MCRYPT_RAND
            )
        ), "\0"
    );
}

function crypto_rand_secure($min, $max) {
	$range = $max - $min;
	if ($range < 0) return $min; // not so random...
	$log = log($range, 2);
	$bytes = (int) ($log / 8) + 1; // length in bytes
	$bits = (int) $log + 1; // length in bits
	$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
	do {
		$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
		$rnd = $rnd & $filter; // discard irrelevant bits
	} while ($rnd >= $range);
	return $min + $rnd;
}

function getToken($length){
	$token = "";
	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet.= "0123456789";
	for($i=0;$i<$length;$i++){
		$token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
	}
	return $token;
}
?>
