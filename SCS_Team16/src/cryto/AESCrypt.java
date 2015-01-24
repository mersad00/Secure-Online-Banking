package cryto;

import java.io.UnsupportedEncodingException;
import java.security.GeneralSecurityException;
import java.security.MessageDigest;
import java.security.SecureRandom;
import java.security.spec.AlgorithmParameterSpec;
import java.util.Arrays;

import javax.crypto.Cipher;
import javax.crypto.Mac;
import javax.crypto.SecretKey;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;

import org.apache.commons.codec.binary.Base64;

public class AESCrypt implements ICryptoManager {

	private/* final */Cipher cipher;
	private/* final */SecretKeySpec key;
	private AlgorithmParameterSpec spec;
	public static final String SEED_16_CHARACTER = "U1MjU1M0FDOUZ.Qz";

	public AESCrypt() /* throws Exception */{
		/*
		 * // hash password with SHA-256 and crop the output to 128-bit for key
		 * MessageDigest digest = MessageDigest.getInstance("SHA-256");
		 * digest.update(SEED_16_CHARACTER.getBytes("UTF-8")); byte[] keyBytes =
		 * new byte[32]; System.arraycopy(digest.digest(), 0, keyBytes, 0,
		 * keyBytes.length);
		 * 
		 * cipher = Cipher.getInstance("AES/CBC/PKCS5Padding"); key = new
		 * SecretKeySpec(keyBytes, "AES"); spec = getIV();
		 */
	}

	public AlgorithmParameterSpec getConstantIV() {
		byte[] iv = { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, };
		IvParameterSpec ivParameterSpec;
		ivParameterSpec = new IvParameterSpec(iv);

		return ivParameterSpec;
	}

	public AlgorithmParameterSpec getRandomIV() {
		// build the initialization vector (randomly).
		SecureRandom random = new SecureRandom();
		byte iv[] = new byte[16];// generate random 16 byte IV AES is always
									// 16bytes
		random.nextBytes(iv);
		IvParameterSpec ivspec = new IvParameterSpec(iv);
		return ivspec;
	}

	Mac sha256_HMAC;
	SecretKeySpec secret_key_mac;

	private byte[] computeSignature(byte[] cipherBytes)
			throws GeneralSecurityException, UnsupportedEncodingException {

		sha256_HMAC.init(secret_key_mac);
		return sha256_HMAC.doFinal(cipherBytes);
	}

	private String encrypt(String plainText) throws Exception {
        cipher.init(Cipher.ENCRYPT_MODE, key, spec);
        byte[] iv = ((IvParameterSpec)spec).getIV();
        byte[] encrypted = cipher.doFinal(plainText.getBytes("UTF-8"));
        byte[] signature = computeSignature(encrypted);
        byte[] combinedcipher = new byte[iv.length + encrypted.length+signature.length];

        //src, index, dest , index , length
        System.arraycopy(iv,0,combinedcipher,0,iv.length);
        System.arraycopy(signature,0,combinedcipher,iv.length,signature.length);
        System.arraycopy(encrypted,0,combinedcipher,signature.length+iv.length,encrypted.length);
        
        String encryptedText = new Base64().encodeAsString(combinedcipher);
        return encryptedText;
    }

	private String decrypt(String cryptedText) throws Exception {
		
		byte[] bytes = new Base64().decode(cryptedText);
		byte[] iv = Arrays.copyOfRange(bytes, 0, 16);
		byte[] hmac= Arrays.copyOfRange(bytes, 16, 48);
		byte[] crypted = Arrays.copyOfRange(bytes, 48,bytes.length);
		///compute hmac
		byte[] computedhmac = computeSignature(crypted);
		
		String hmacb64 = new Base64().encodeAsString(hmac);
		
		///if hmac is not equal tampering happened
		if(!Arrays.equals(hmac, computedhmac)) return "";
		
		spec = new IvParameterSpec(iv);
		cipher.init(Cipher.DECRYPT_MODE, key, spec);
		byte[] decrypted = cipher.doFinal(crypted);
		
		String decryptedText = new String(decrypted, "UTF-8");

		return decryptedText;
	}

	private void InitCipher(SecureKey seckey) throws Exception {
		// hash password with SHA-256 and crop the output to 128-bit for key
		MessageDigest digest = MessageDigest.getInstance("SHA-256");
		digest.update(seckey.getKey().getBytes("UTF-8"));
		byte[] keyBytes = new byte[32];
		System.arraycopy(digest.digest(), 0, keyBytes, 0, keyBytes.length);

		cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
		key = new SecretKeySpec(keyBytes, "AES");
		spec = getRandomIV();

		sha256_HMAC = Mac.getInstance("HmacSHA256");
		secret_key_mac = new SecretKeySpec(keyBytes, "HmacSHA256");
	}

	@Override
	public String encrypt(String data, SecureKey seckey) {
		try {
			InitCipher(seckey);
			return encrypt(data);
		} catch (Exception exc) {
			return null;
		}
	}

	@Override
	public String decrypt(String cipherText, SecureKey seckey) {
		try {
			InitCipher(seckey);
			return decrypt(cipherText);
		} catch (Exception exc) {
			return null;
		}
	}

}
