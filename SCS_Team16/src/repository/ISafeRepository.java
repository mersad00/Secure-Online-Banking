package repository;

public interface ISafeRepository {
	
	RepositoryContent retrieveRepoContents(String pin);
	boolean storeSessionKey(String pin, String account,String SessionKey);
	boolean resetSession();
	boolean doesSessionExist();
}
