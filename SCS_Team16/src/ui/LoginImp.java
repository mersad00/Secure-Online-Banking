package ui;

import cryto.AESCrypt;
import cryto.CryptoManagerImp;
import cryto.ICryptoManager;
import host.DeviceManagerImp;
import host.IDeviceManager;
import repository.ISafeRepository;
import repository.RepositoryContent;
import repository.SafeRepositoryImp;

public class LoginImp implements ILogin {

	IDeviceManager deviceManger;
	ISafeRepository repo;
	ICryptoManager cryptoManager;

	public static String pin;

	public LoginImp() {
		deviceManger = new DeviceManagerImp();
		cryptoManager = new AESCrypt();
		repo = new SafeRepositoryImp(deviceManger, cryptoManager);
	}

	@Override
	public boolean authenticate(String pin) {
		if (repo.doesSessionExist()) {
			RepositoryContent rc = repo.retrieveRepoContents(pin);
			if (rc!=null && !rc.didFailed()) {
				LoginImp.pin = pin;
				return true;
			}
		}
		return false;
	}

	@Override
	public boolean resetData() {
		LoginImp.pin = null;
		return repo.resetSession();

	}

	@Override
	public boolean register(String pin, String account, String sessionKey) {
		LoginImp.pin = null;
		return repo.storeSessionKey(pin, account, sessionKey);
	}

	@Override
	public boolean isUserDefined() {
		return repo.doesSessionExist();
	}

}
