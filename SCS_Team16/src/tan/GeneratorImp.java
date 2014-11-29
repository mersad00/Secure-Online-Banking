package tan;
import java.util.Date;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.TimeZone;

import repository.ISafeRepository;
import repository.RepositoryContent;
import cryto.ICryptoManager;
import cryto.SecureKey;
import cryto.SecureKey128bit;

public class GeneratorImp implements IGenerator{

	@Override
	public String generateTan(String pin,String account, String amount,
			ISafeRepository repo, ICryptoManager crypto) {
		try{
		RepositoryContent rc = repo.retrieveRepoContents(pin);
		
		Date currentUTC = getCurrentUTC();
		if(currentUTC == null)return null;
		
		String rawTan = account+";"+amount;//+";"+currentUTC.getTime();
		
		SecureKey sessionKey = new SecureKey128bit(rc.getSessionKey());
		String encTan = crypto.encrypt(rawTan, sessionKey);
		return encTan;
		}
		catch (Exception e) {
			return null;
		}
	}
private Date getCurrentUTC(){


SimpleDateFormat dateFormatGmt = new SimpleDateFormat("yyyy-MMM-dd HH:mm:ss");
dateFormatGmt.setTimeZone(TimeZone.getTimeZone("GMT"));

//Local time zone   
SimpleDateFormat dateFormatLocal = new SimpleDateFormat("yyyy-MMM-dd HH:mm:ss");

//Time in GMT
try {
	return dateFormatLocal.parse( dateFormatGmt.format(new Date()) );
} catch (ParseException e) {
	return null;
}


}
}
