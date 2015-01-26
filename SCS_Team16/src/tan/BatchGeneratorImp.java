package tan;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import ui.ITanGenerator;
import ui.LoginImp;
import ui.TanGeneratorImp;

public class BatchGeneratorImp implements IBatchGenerator {

	private File file; // file uploaded from client

	private File tansFile;
	private ITanGenerator tanController;

	public BatchGeneratorImp(File file) {
		this.file = file;
		tanController = new TanGeneratorImp();
	}

	@Override
	public String generateTan(String token) {

		return generateTansFile(token);
	}

	private String generateTansFile(String token) {
		try {
			List<TransactionEntry> entries = readFileLines(file);
			for (TransactionEntry entry : entries) {
				if ( entry.isComment)
					continue;
				// TODO: @Alba check the entry.token you should
				String tan = tanController.generateTan(LoginImp.pin, token,
						entry.destinationAccount, entry.amount + "");
				System.out.println("generated tan: " + tan + " for "
						+ entry.sourceAccount + " " + entry.amount);
				entry.tan = tan;
			}
			long currentTime = System.currentTimeMillis();
			tansFile = new File("Tans" + currentTime + ".txt");
			writeFileLines(entries, tansFile);

		} catch (IOException e) {
			e.printStackTrace();
		}

		return tansFile.getAbsolutePath();
	}

	private void writeFileLines(List<TransactionEntry> entries, File file)
			throws IOException {
		FileWriter fw = new FileWriter(file, false);
		BufferedWriter bw = new BufferedWriter(fw);
		for (TransactionEntry transactionEntry : entries) {
			bw.write(transactionEntry.toString() + "\n");
		}
		bw.flush();
		bw.close();
	}

	private List<TransactionEntry> readFileLines(File inputFile)
			throws IOException {
		BufferedReader br = new BufferedReader(new FileReader(inputFile));
		String strLine;
		List<TransactionEntry> entries = new ArrayList<>();
		while ((strLine = br.readLine()) != null) {
			if (strLine.length() < 3)
				continue; // ignore irelevant lines
			if (strLine.startsWith("#"))
				continue; // ignore comments
			String[] elements = strLine.split(",");
			if (elements.length < 4) {
				// invalid line
				TransactionEntry comment = new TransactionEntry(strLine);
				System.out.println(comment);
				entries.add(comment);
				continue;
			} else {
				try {
					TransactionEntry comment = new TransactionEntry(strLine);
					entries.add(comment);
					TransactionEntry entry = new TransactionEntry();
					// entry.tan = elements[0];
					entry.sourceAccount = elements[0];
					entry.destinationAccount = elements[1];
					entry.amount = Integer.parseInt(elements[2]);
					entry.description = elements[3];
					entries.add(entry);
				} catch (NumberFormatException ex) {
					TransactionEntry comment = new TransactionEntry(strLine
							+ " - number format exception");
					System.out.println(comment);
					entries.add(comment);
				}
			}
		}

		br.close();

		return entries;
	}

}

class TransactionEntry {
	public String tan;
	public String sourceAccount;
	public String token;
	public String destinationAccount;
	public int amount;
	public String description;
	public boolean isComment;
	public String comment;

	public TransactionEntry(String tan, String sourceAcc, String destAcc,
			int amount, String description) {
		this.tan = tan;
		this.sourceAccount = sourceAcc;
		this.destinationAccount = destAcc;
		this.amount = amount;
		this.description = description;
		this.isComment = false;
		this.comment = "";
	}

	public TransactionEntry(String comment) {
		this.isComment = true;
		this.comment = comment;
	}

	public TransactionEntry() {
		this.tan = "";
		this.sourceAccount = "";
		this.destinationAccount = "";
		this.amount = 0;
		this.description = "";
		this.isComment = false;
		this.comment = "";
	}

	public String toString() {
		if (isComment) {
			return "#" + comment;
		}
		return tan + "," + sourceAccount + "," + destinationAccount + ","
				+ amount + "," + description;
	}

	public void setGeneratedTan(String tan) {
		this.tan = tan;
	}

}
