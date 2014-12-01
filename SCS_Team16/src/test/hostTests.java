package test;

import static org.junit.Assert.*;

import java.io.IOException;

import host.DeviceManagerImp;
import host.Hardware4Nix;
import host.IDeviceManager;

import org.junit.Test;

import com.sun.xml.internal.bind.v2.ClassFactory;

public class hostTests {

	@Test
	public void testGetDeviceUniqueIdentifier() throws Exception {
		
		IDeviceManager dm = new DeviceManagerImp();
		Exception exc=null;
		String serial =null;
		try {
			serial = dm.getDeviceUniquieIdentifier();
		} catch (IOException e) {
			exc = e;
			e.printStackTrace();
		}
		assertNull(exc);
		assertNotNull(serial);
		assertTrue(serial.length()>5);
		
	}
	
	@Test
	public void testGetDeviceUniqueIdentifierWithHardware4Nix() throws Exception {
		
		String serial =null;
		serial = Hardware4Nix.getSerialNumber();
	
		assertNotNull(serial);
		assertTrue(serial.length()>5);
		
	}
	


}
