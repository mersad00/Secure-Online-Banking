package ui;

import host.DeviceManagerImp;
import host.IDeviceManager;
import repository.ISafeRepository;
import repository.SafeRepositoryImp;
import tan.GeneratorImp;
import tan.IGenerator;
import cryto.AESCrypt;
import cryto.CryptoManagerImp;
import cryto.ICryptoManager;

public class TanGeneratorImp implements ITanGenerator
{
	IGenerator generator;
	IDeviceManager deviceManger;
	ISafeRepository repo;
	ICryptoManager cryptoManager;
	public TanGeneratorImp() {
		deviceManger = new DeviceManagerImp();
		cryptoManager = new AESCrypt();
		repo = new SafeRepositoryImp(deviceManger, cryptoManager);
		generator = new GeneratorImp();
}
	@Override
	public String generateTan(String pin, String account, String amount) {
		return generator.generateTan(pin, account, amount, repo, cryptoManager);
	}
}
