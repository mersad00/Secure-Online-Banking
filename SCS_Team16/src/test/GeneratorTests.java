package test;

import static org.junit.Assert.*;

import java.text.SimpleDateFormat;
import java.util.Date;

import host.DeviceManagerImp;
import host.IDeviceManager;

import org.junit.Before;
import org.junit.Test;

import cryto.AESCrypt;
import cryto.CryptoManagerImp;
import cryto.ICryptoManager;
import cryto.SecureKey128bit;
import repository.ISafeRepository;
import repository.SafeRepositoryImp;
import tan.GeneratorImp;
import tan.IGenerator;

public class GeneratorTests {

	@Before
	public void SetupTest(){
		//ensure the repo exists
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
	public void testTanGenerate() {
		String pin ="1234";
		String account = "1212111";
		String amount = "100";
		
		IDeviceManager devicemanager= new DeviceManagerImp();
		ICryptoManager cryptomanager= new AESCrypt();
		ISafeRepository repo = new SafeRepositoryImp(devicemanager,cryptomanager);
		
		IGenerator generator = new GeneratorImp();
		String tan = generator.generateTan(pin, account, amount, repo, cryptomanager);
		
		assertNotNull(tan);
	}
	@Test
	public void testTanDecrypt() {
		String pin ="1234";
		String account = "1212111";
		String amount = "100";
		
		try{
		IDeviceManager devicemanager= new DeviceManagerImp();
		ICryptoManager cryptomanager= new AESCrypt();
		ISafeRepository repo = new SafeRepositoryImp(devicemanager,cryptomanager);
		
		IGenerator generator = new GeneratorImp();
		String tan = generator.generateTan(pin, account, amount, repo, cryptomanager);
		assertNotNull(tan);
		
		String sessionKeyVal = repo.retrieveRepoContents(pin).getSessionKey();
		SecureKey128bit sessionkey =  new SecureKey128bit(sessionKeyVal);
		String[] decipheredTan = cryptomanager.decrypt(tan, sessionkey).split(";");
		assertTrue(decipheredTan[0].equals(account));
		assertTrue(decipheredTan[1].equals(amount));
		SimpleDateFormat formatter = new SimpleDateFormat("MM/dd/yyyy KK:mm:ss a Z");
		Date generationTime = formatter.parse(decipheredTan[2]);
		long m = generationTime.getTime() - new Date().getTime();
		long diffMinutes = m / (60 * 1000) % 60;
		assertTrue(diffMinutes<10);
		
		}
		catch(Exception exc){
			fail();
		}
		
		
		
	}
}
