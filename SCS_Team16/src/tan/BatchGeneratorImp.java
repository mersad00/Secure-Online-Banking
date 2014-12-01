package tan;

import java.io.File;

public class BatchGeneratorImp implements IBatchGenerator{
	
	private File file; // file uploaded from client
	
	private File tansFile;
	
	public BatchGeneratorImp(File file){
		this.file = file;
		tansFile = new File("Tans.txt");
	}

	@Override
	public String generateTan() {
		//TODO: generate tan/tans and write them to the tansFile created in the constructor
		return null;
	}

}
