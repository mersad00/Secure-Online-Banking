package ui;

import java.io.File;

import tan.BatchGeneratorImp;
import tan.IBatchGenerator;

public class BatchUploadImp implements IBatchUpload {

	IBatchGenerator batchGenerator;

	BatchUploadImp(File file) {
		batchGenerator = new BatchGeneratorImp(file);
	}

	@Override
	public String generateTansFile(String token) {

		String resultPath = batchGenerator.generateTan(token);

		return resultPath;
	}

}