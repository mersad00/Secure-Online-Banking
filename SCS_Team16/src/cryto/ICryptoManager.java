package cryto;

public interface ICryptoManager {

	String encrypt(String data, String key);
	String decrypt(String cipherText, String key);
}
