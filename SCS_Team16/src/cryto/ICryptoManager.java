package cryto;

public interface ICryptoManager {

	String encrypt(String data, SecureKey key);
	String decrypt(String cipherText, SecureKey key);
}

