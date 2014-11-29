package cryto;

import java.security.MessageDigest;
import java.security.spec.AlgorithmParameterSpec;

import javax.crypto.Cipher;
import javax.crypto.spec.IvParameterSpec;
import javax.crypto.spec.SecretKeySpec;

import org.apache.commons.codec.binary.Base64;

public class AESCrypt implements ICryptoManager{

    private /*final*/ Cipher cipher;
    private /*final*/ SecretKeySpec key;
    private AlgorithmParameterSpec spec;
    public static final String SEED_16_CHARACTER = "U1MjU1M0FDOUZ.Qz";

    public AESCrypt() /*throws Exception*/ {
        /*// hash password with SHA-256 and crop the output to 128-bit for key
        MessageDigest digest = MessageDigest.getInstance("SHA-256");
        digest.update(SEED_16_CHARACTER.getBytes("UTF-8"));
        byte[] keyBytes = new byte[32];
        System.arraycopy(digest.digest(), 0, keyBytes, 0, keyBytes.length);

        cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
        key = new SecretKeySpec(keyBytes, "AES");
        spec = getIV();*/
    }

    public AlgorithmParameterSpec getIV() {
        byte[] iv = { 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, };
        IvParameterSpec ivParameterSpec;
        ivParameterSpec = new IvParameterSpec(iv);

        return ivParameterSpec;
    }

    private String encrypt(String plainText) throws Exception {
        cipher.init(Cipher.ENCRYPT_MODE, key, spec);
        byte[] encrypted = cipher.doFinal(plainText.getBytes("UTF-8"));
        String encryptedText = new Base64().encodeAsString(encrypted);

        return encryptedText;
    }

    private String decrypt(String cryptedText) throws Exception {
        cipher.init(Cipher.DECRYPT_MODE, key, spec);
        byte[] bytes =new Base64().decode(cryptedText);
        byte[] decrypted = cipher.doFinal(bytes);
        String decryptedText = new String(decrypted, "UTF-8");

        return decryptedText;
    }
    
    
    private void InitCipher(SecureKey seckey) throws Exception{
  		  // hash password with SHA-256 and crop the output to 128-bit for key
          MessageDigest digest = MessageDigest.getInstance("SHA-256");
          digest.update(seckey.getKey().getBytes("UTF-8"));
          byte[] keyBytes = new byte[32];
          System.arraycopy(digest.digest(), 0, keyBytes, 0, keyBytes.length);

          cipher = Cipher.getInstance("AES/CBC/PKCS5Padding");
          key = new SecretKeySpec(keyBytes, "AES");
          spec = getIV();
    }

	@Override
	public String encrypt(String data, SecureKey seckey) {
		try{
			InitCipher(seckey);
			return encrypt(data);
		}catch(Exception exc){
			return null;
		}
	}

	@Override
	public String decrypt(String cipherText, SecureKey seckey) {
		try{
			InitCipher(seckey);
			return decrypt(cipherText);
		}catch(Exception exc){
			return null;
		}
	}

}
