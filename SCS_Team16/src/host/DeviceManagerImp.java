package host;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

public class DeviceManagerImp implements IDeviceManager {

	@Override
	public String getDeviceUniquieIdentifier() throws IOException {
		String serial = executeCommand("sudo dmidecode -t system");
		int uuidindex = serial.indexOf("UUID:");
		if(uuidindex <= 0 ) return "";
		uuidindex += 6;
		String UUID = serial.substring(uuidindex,uuidindex + 35);
		return UUID;
	}
	private String executeCommand(String command) {
		 
		StringBuffer output = new StringBuffer();
 
		Process p;
		try {
			p = Runtime.getRuntime().exec(command);
			p.waitFor();
			BufferedReader reader = 
                           new BufferedReader(new InputStreamReader(p.getInputStream()));
 
			String line = "";			
			while ((line = reader.readLine())!= null) {
				output.append(line + "\n");
			}
 
		} catch (Exception e) {
			e.printStackTrace();
		}
 
		return output.toString();
 
	}
	
}
