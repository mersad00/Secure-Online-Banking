package test;

import static org.junit.Assert.*;

import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.Date;

import org.apache.commons.net.ntp.NTPUDPClient;
import org.apache.commons.net.ntp.TimeInfo;
import org.junit.Test;

public class TimeTests {

	@Test
	public void testTimeServerGettingTime() {
		String TIME_SERVER = "time-a.nist.gov";
		NTPUDPClient timeClient = new NTPUDPClient();
		InetAddress inetAddress;
		try {
			inetAddress = InetAddress.getByName(TIME_SERVER);
			TimeInfo timeInfo = timeClient.getTime(inetAddress);
			long returnTime = timeInfo.getReturnTime();
			Date time = new Date(returnTime);
			assertNotNull(time);
		} catch (Exception e) {
			fail(e.getMessage());
		}
	}

}
