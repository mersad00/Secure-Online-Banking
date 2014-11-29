package ui;

public interface ILogin {
	boolean authenticate(String pin);
	boolean resetData();
	boolean register(String pin,String account, String sessionKey);
	boolean isUserDefined();
}
