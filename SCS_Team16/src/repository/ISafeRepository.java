package repository;

public interface ISafeRepository {
	
	String retrieveSessionKey(String pin);
	boolean storeSessionKey(String pin, String account,String SessionKey);
	boolean resetSession();
	boolean doesSessionExist();
	boolean getCurrentSafeRepository();
}
