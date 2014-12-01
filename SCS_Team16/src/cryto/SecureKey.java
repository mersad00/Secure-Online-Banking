package cryto;

import java.io.UnsupportedEncodingException;

public abstract class SecureKey {

	String key;
	SecureKey(String key)
{
	this.key = key;
	
}
	
	public String getKey(){
		return this.key;
	}
	
	public byte[] getBytes()
	{
		try {
			return this.key.getBytes("UTF-8");
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
		return null;
	}
	
	public int length()
	{
		return this.key.length();
	}
}
