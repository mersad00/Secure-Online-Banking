package tan;

import java.util.Date;
import java.net.InetAddress;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.TimeZone;


import repository.ISafeRepository;
import repository.RepositoryContent;
import cryto.ICryptoManager;
import cryto.SecureKey;
import cryto.SecureKey128bit;

public class GeneratorImp implements IGenerator {

	ISafeRepository safe;
	ICryptoManager crypto;
	@Override
	public String generateTan(String pin,String token, String account, String amount,
			ISafeRepository repo, ICryptoManager crypto) {
		this.crypto = crypto;
		this.safe = repo;
		
		try {
			RepositoryContent rc = repo.retrieveRepoContents(pin);
			SecureKey sessionKey = new SecureKey128bit(rc.getSessionKey());
			String DecryptedToken= null;
			///validate token
			try{
				DecryptedToken = crypto.decrypt(token, sessionKey);
			}catch(Exception ex){
				DecryptedToken = null;
			}
			
			if(DecryptedToken == null) return null;

			String rawTan = account
					+ ";"
					+ amount
					+ ";"
					+DecryptedToken ;

			
			String encTan = crypto.encrypt(rawTan, sessionKey);
			return encTan;
		} catch (Exception e) {
			return null;
		}
	}

	private Date getCurrentUTC() {

		SimpleDateFormat dateFormatGmt = new SimpleDateFormat(
				"yyyy-MMM-dd HH:mm:ss");
		dateFormatGmt.setTimeZone(TimeZone.getTimeZone("GMT"));

		// Local time zone
		SimpleDateFormat dateFormatLocal = new SimpleDateFormat(
				"yyyy-MMM-dd HH:mm:ss");

		// Time in GMT
		// try {
		// return
		// dateFormatLocal.parse(dateFormatGmt.format(getCurrentTimeFromTimeServer()));
		// } catch (ParseException e) {
		try {
			return dateFormatLocal.parse(dateFormatGmt.format(new Date()));
		} catch (ParseException e1) {
			return null;
		}
		// }

	}


}
