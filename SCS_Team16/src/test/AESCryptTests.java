package test;

import static org.junit.Assert.*;
import org.junit.Test;
import cryto.AESCrypt;
import cryto.SecureKey;
import cryto.SecureKey128bit;

public class AESCryptTests {

	@Test
	public void testDecrypt() {
		try {
			String plainText ="123456";
			String key = "Servus!";
			AESCrypt aes = new AESCrypt();
			SecureKey sec= new SecureKey128bit(key);
			String cipherText = aes.encrypt(plainText,sec);
			String decipherText = aes.decrypt(cipherText,sec);
			//assertTrue(cipherText.equals("tpyxISJ83dqEs3uw8bN/+w=="));
			assertTrue(plainText.equals(decipherText));
			
		} catch (Exception e) {
			e.printStackTrace();
			fail();
		}
	}
	@Test
	public void testHMAC(){
		try {
			String plainText = "2;2006456456;Tue Jan 20 13:54:34 CET 2015";
			String cipherText ="M2KVhZJM4ZX4TvAIf8fgkteoI/rmHfPYtHlcy+svAgHvAiChtmv1MtkP6/uUJ9mbrNj5qHbNGjA8wPZNHYQEOZV0TwyLlFWytqvcRcZySLTWCh0l9hTvai9z2gNFshTV";
			String key = "5UvSoqvtVVtrV2ZW";
			AESCrypt aes = new AESCrypt();
			SecureKey sec= new SecureKey128bit(key);
			String decipherText = aes.decrypt(cipherText,sec);
			assertTrue(plainText.equals(decipherText));
			
		} catch (Exception e) {
			e.printStackTrace();
			fail();
		}
	}

}
