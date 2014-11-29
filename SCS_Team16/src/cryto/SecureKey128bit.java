package cryto;

import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.Arrays;

import org.apache.commons.codec.binary.Base64;

public class SecureKey128bit extends SecureKey {

	public SecureKey128bit(String key) throws Exception {
		super(key);
		//if(key.length()!=16) throw new Exception("Key lentgh must be 16 chars!");
	}
	@Override 
	public byte[] getBytes(){
		try {
			byte[] k = super.getBytes();
			MessageDigest sha;
		
			sha = MessageDigest.getInstance("SHA-1");
			byte[] digestedkey = sha.digest(k);
			digestedkey = Arrays.copyOf(digestedkey, 16); // use only first 128 bit
			String key =new String( new Base64().encode(digestedkey));
			
			return digestedkey;
		} catch (NoSuchAlgorithmException e) {
			e.printStackTrace();
		}
		return null;
	}

}
