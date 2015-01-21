package repository;

import host.IDeviceManager;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.file.FileSystems;
import java.nio.file.Files;
import java.nio.file.Path;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

import org.apache.commons.codec.binary.Base64;

import com.sun.istack.internal.Nullable;

import cryto.ICryptoManager;
import cryto.SecureKey;
import cryto.SecureKey128bit;

public class SafeRepositoryImp implements ISafeRepository {

	IDeviceManager deviceManager;
	ICryptoManager cryptoManager;
	public SafeRepositoryImp(IDeviceManager deviceManager,ICryptoManager cryptoManager){
		this.deviceManager = deviceManager;
		this.cryptoManager = cryptoManager;
	}
	@Override
	@Nullable public RepositoryContent retrieveRepoContents(String pin) {
		try{
		if(this.doesSessionExist()){
			String repoFile = getRepoFile();
			String repoKeyVal = getRepoKey(pin);
			if(repoKeyVal==null){
				return new RepositoryContent(true);
			}
			SecureKey repoKey = new SecureKey128bit(repoKeyVal);
			Path path = FileSystems.getDefault().getPath(repoFile);
			byte[] cipherByte = Files.readAllBytes(path);
			String encryptedContent = new String(cipherByte);

			
			String[] content = cryptoManager.decrypt(encryptedContent,repoKey).split(";");
			return new RepositoryContent(content[0],content[1]);
		}
		else
			return new RepositoryContent(true);}
		catch(Exception exc){
			return new RepositoryContent(true);
		}
	}

	private String getRepoFile(){
		return System.getProperty("user.dir") + "/repo.g16";
	}
	
	@Override
	public boolean storeSessionKey(String pin, String account, String SessionKey) {
		try{
		String repoFilePath = getRepoFile();
		
		String repoKeyVal = getRepoKey(pin);
		if(repoKeyVal==null){
			return false;
		}
		SecureKey repoKey = new SecureKey128bit(repoKeyVal);
		String content = account + ";"+SessionKey;
		String encryptedContent = cryptoManager.encrypt(content, repoKey);
		
		FileOutputStream fos = new FileOutputStream(repoFilePath);
		fos.write(encryptedContent.getBytes());
		fos.close();
		
		return true;
		}
		catch(Exception exc){
			return false;
		}
	}

	@Override
	public boolean resetSession() {
		File f = new File(getRepoFile());
		return f.delete();
	}

	@Override
	public boolean doesSessionExist() {
		File f = new File(getRepoFile());
		return f.exists();
	}
	
	private String getRepoKey(String pin) {
		try {
			String seckey = this.deviceManager.getDeviceUniquieIdentifier().substring(0,5)+ pin;
			MessageDigest digest = MessageDigest.getInstance("SHA-256");
			digest.update(seckey.getBytes("UTF-8"));
			byte[] keyBytes = new byte[32];
			System.arraycopy(digest.digest(), 0, keyBytes, 0, keyBytes.length);
			return new Base64().encodeAsString(keyBytes);
			
		} catch (Exception e) {
			return null;
		}
	}

}
