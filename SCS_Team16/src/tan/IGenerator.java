package tan;


import cryto.ICryptoManager;
import repository.ISafeRepository;

public interface IGenerator {
	String generateTan(String pin,String token,String account,String amount, ISafeRepository repo, ICryptoManager crypto);
}
