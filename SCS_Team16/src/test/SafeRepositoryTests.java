package test;

import static org.junit.Assert.*;
import host.DeviceManagerImp;
import host.IDeviceManager;

import org.junit.Test;

import cryto.AESCrypt;
import cryto.CryptoManagerImp;
import cryto.ICryptoManager;
import repository.ISafeRepository;
import repository.RepositoryContent;
import repository.SafeRepositoryImp;

public class SafeRepositoryTests {

	@Test
	public void testStoreSessionKey() {
		String pin ="1234";
		String account = "1212111";
		String SessionKey = "212121212";
		
		IDeviceManager devicemanager= new DeviceManagerImp();
		ICryptoManager cryptomanager= new AESCrypt();
		ISafeRepository repo = new SafeRepositoryImp(devicemanager,cryptomanager);
		
		boolean successful = repo.storeSessionKey(pin, account, SessionKey);
		
		assertTrue(successful);
	}
	@Test
	public void testStoreAndRetrieveSessionKey() {
		String pin ="1234";
		String account = "1212111";
		String SessionKey = "212121212";
		
		IDeviceManager devicemanager= new DeviceManagerImp();
		ICryptoManager cryptomanager= new AESCrypt();
		ISafeRepository repo = new SafeRepositoryImp(devicemanager,cryptomanager);
		
		boolean successful = repo.storeSessionKey(pin, account, SessionKey);
		assertTrue(successful);
		RepositoryContent repoContents = repo.retrieveRepoContents(pin);
		assertTrue(account.equals(repoContents.getAccount()));
		assertTrue(SessionKey.equals(repoContents.getSessionKey()));
	}

}
