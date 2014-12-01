package test;

import static org.junit.Assert.*;

import org.junit.Test;

import sun.net.www.content.text.plain;

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

}
