package repository;

public class RepositoryContent {

	String account;
	String sessionKey;
	boolean failed;
	public RepositoryContent(boolean failed){
		this.failed =true;
	}
	public RepositoryContent(String account,String sessionKey) {
		this.account = account;
		this.sessionKey = sessionKey;
		this.failed =false;
	}
	
	public String getSessionKey(){
		return this.sessionKey;
	}
	
	public String getAccount(){
		return this.account;
	}
	
	public boolean didFailed(){
		return this.failed;
	}
}
