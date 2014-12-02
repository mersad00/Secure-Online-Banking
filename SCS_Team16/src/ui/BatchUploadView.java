package ui;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.beans.PropertyChangeEvent;
import java.beans.PropertyChangeListener;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import javax.swing.JButton;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JPanel;

public class BatchUploadView extends UIView implements  ActionListener {

	File file;
	JFileChooser fc;
	JButton openButton, tanGeneration;
	JLabel fileLabel;
	
	
	
	private ITanGenerator tanController = new TanGeneratorImp();
	
	public BatchUploadView(JPanel panel) {
		super(panel);
		panel.setLayout(null);

		JLabel userLabel = new JLabel("Please choose the batch file");
		userLabel.setBounds(90, 10, 250, 25);
		panel.add(userLabel);
		
		fc = new JFileChooser();
		// Add listener on chooser to detect changes to selected file
		fc.addPropertyChangeListener(new PropertyChangeListener() {
		    public void propertyChange(PropertyChangeEvent evt) {
		        if (JFileChooser.SELECTED_FILE_CHANGED_PROPERTY
		                .equals(evt.getPropertyName())) {
		         

		            // The selected file should always be the same as newFile
		
		        } else if (JFileChooser.SELECTED_FILES_CHANGED_PROPERTY.equals(
		                evt.getPropertyName())) {
		        
		            // Get list of selected files
		            // The selected files should always be the same as newFiles
		     
		        }
		    }
		});
		panel.add(fc);
		JButton openButton = new JButton("Open a File...");
		openButton.setBounds(200, 50, 150, 25);
		openButton.addActionListener(this);
		panel.add(openButton);
		
		fileLabel = new JLabel();
        fileLabel.setBounds(50, 50, 250, 25);
        fileLabel.setVisible(false);
		panel.add(fileLabel);
		
		//Tan generation button
		tanGeneration = new JButton("Generate Tans");
		tanGeneration.setBounds(120, 100, 150, 25);
		tanGeneration.addActionListener(this);
		panel.add(tanGeneration);
		tanGeneration.setVisible(false);
		
	}

	@Override
	public void actionPerformed(ActionEvent arg0) {
		String buttonText = ((JButton) arg0.getSource()).getText();
		switch (buttonText) {
		case "Open a File...":
				//this.hideMe(true);
				int returnVal = fc.showDialog(panel, "Upload");
			      if (returnVal == JFileChooser.APPROVE_OPTION) {
			        file = fc.getSelectedFile();
			        fileLabel.setText(fc.getSelectedFile().getName());
			        fileLabel.setVisible(true);
			        tanGeneration.setVisible(true);
			      }
			break;   
		case "Generate Tans":
			
			try {
				List<TransactionEntry> entries = readFileLines(file);
				for (TransactionEntry entry : entries) {
					if(entry.isComment)
						continue;
					String tan = tanController.generateTan(LoginImp.pin, entry.sourceAccount, entry.amount+"");
					System.out.println("generated tan: " + tan +" for " + entry.sourceAccount + " "+ entry.amount);
					entry.tan = tan;
				}
				
				writeFileLines(entries, file);
				
			} catch (IOException e) {
				e.printStackTrace();
			}
			
			this.hideMe(true);
			
		break;  

		}
	}
	
	private List<TransactionEntry> readFileLines(File inputFile) throws IOException{
		BufferedReader br = new BufferedReader(new FileReader(inputFile));
		String strLine;
		List<TransactionEntry> entries = new ArrayList<>();
		while ((strLine = br.readLine()) != null) {
			if(strLine.length() < 3)
				continue; //ignore irelevant lines
			if(strLine.startsWith("#"))
				continue; //ignore comments
			String[] elements = strLine.split(",");
			if(elements.length < 5){
				//invalid line
				TransactionEntry comment = new TransactionEntry(strLine);
				System.out.println(comment);
				entries.add(comment);
				continue;
			}
			else {
				try{
					TransactionEntry entry = new TransactionEntry();
					entry.tan = elements[0];
					entry.sourceAccount = elements[1];
					entry.destinationAccount = elements[2];
					entry.amount = Integer.parseInt(elements[3]);
					entry.description = elements[4];
					entries.add(entry);
					TransactionEntry comment = new TransactionEntry(strLine);
					entries.add(comment);
				}
				catch (NumberFormatException ex){
					TransactionEntry comment = new TransactionEntry(strLine + " - number format exception");
					System.out.println(comment);
					entries.add(comment);
				}				
			}			
		}

		br.close();

		return entries;
	}
	
	private void writeFileLines(List<TransactionEntry> entries, File file) throws IOException{
		FileWriter fw = new FileWriter(file, false);
	    BufferedWriter bw = new BufferedWriter(fw);
	    for (TransactionEntry transactionEntry : entries) {
			bw.write(transactionEntry.toString()+"\n");
		}
	    bw.flush();
	    bw.close();
	}
}

class TransactionEntry {
	public String tan;
	public String sourceAccount;
	public String destinationAccount;
	public int amount;
	public String description;
	public boolean isComment;
	public String comment;
	
	public TransactionEntry(String tan, String sourceAcc, String destAcc, int amount, String description){
		this.tan = tan;
		this.sourceAccount = sourceAcc;
		this.destinationAccount = destAcc;
		this.amount = amount;
		this.description = description;
		this.isComment = false;
		this.comment = "";
	}
	
	public TransactionEntry(String comment){
		this.isComment = true;
		this.comment = comment;
	}
	
	public TransactionEntry(){
		this.tan = "";
		this.sourceAccount = "";
		this.destinationAccount = "";
		this.amount = 0;
		this.description = "";
		this.isComment = false;
		this.comment = "";
	}
	
	public String toString(){
		if(isComment){
			return "#" + comment;
		}
		return tan+","+sourceAccount+","+destinationAccount+","+amount+","+description;		
	}
	
	public void setGeneratedTan(String tan){
		this.tan = tan;		
	}
}
