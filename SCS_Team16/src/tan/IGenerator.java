package tan;

import java.util.Date;

import cryto.ICryptoManager;
import repository.ISafeRepository;

public interface IGenerator {

	Date getCurrentTimeFromTimeServer();
	String generateTan(String pin,String token,String account,String amount, ISafeRepository repo, ICryptoManager crypto);
}
