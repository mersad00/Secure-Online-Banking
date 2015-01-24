package test;

import static org.junit.Assert.*;

import org.junit.Test;

import cryto.AESCrypt;
import cryto.CryptoManagerImp;
import cryto.ICryptoManager;
import cryto.SecureKey128bit;

public class CryptoManagerTests {

	@Test
	public void testEncrypt() throws Exception {
		String k = "1234567891011111";
		ICryptoManager cm =  new AESCrypt();
		SecureKey128bit key = new SecureKey128bit(k);
		String cipherText = cm.encrypt("servus!", key);
		
		assertNotSame("servus!", cipherText);
		
	}
	
	@Test
	public void testDecrypt() throws Exception {
		String data = "servus!";
		String k = "1234567891011111";
		ICryptoManager cm =  new AESCrypt();
		SecureKey128bit key = new SecureKey128bit(k);
		String cipherText = cm.encrypt(data, key);
		String returneddata = cm.decrypt(cipherText, key);
		assertTrue(returneddata.equals(data));
		
	}
	@Test
	public void testFailDecrypt() throws Exception {
		String data = "servus!";
		String k1 = "1234567891011111";
		String k2 = "1234567891011112";
		ICryptoManager cm =  new AESCrypt();
		SecureKey128bit key1 = new SecureKey128bit(k1);
		SecureKey128bit key2 = new SecureKey128bit(k2);
		String cipherText="";
		String returneddata ="";
		cipherText = cm.encrypt(data, key1);
		returneddata = cm.decrypt(cipherText, key2);
		assertTrue(returneddata == null || !returneddata.equals(data));
	}

}
