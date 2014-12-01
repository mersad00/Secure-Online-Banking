package ui;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.beans.PropertyChangeEvent;
import java.beans.PropertyChangeListener;
import java.io.File;

import javax.swing.JButton;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.filechooser.FileNameExtensionFilter;

public class BatchUploadView extends UIView implements ActionListener {

	File file;
	JFileChooser fc;
	JButton openButton, tanGeneration;
	JLabel fileLabel, tansFileLabel, userLabel;
	IBatchUpload bachTanController;

	public BatchUploadView(JPanel panel) {
		super(panel);
		panel.setLayout(null);

		userLabel = new JLabel("Please choose the batch file");
		userLabel.setBounds(90, 10, 250, 25);
		panel.add(userLabel);

		fc = new JFileChooser();
		// Add listener on chooser to detect changes to selected file
		fc.addPropertyChangeListener(new PropertyChangeListener() {
			public void propertyChange(PropertyChangeEvent evt) {
				if (JFileChooser.SELECTED_FILE_CHANGED_PROPERTY.equals(evt
						.getPropertyName())) {

					// The selected file should always be the same as newFile

				} else if (JFileChooser.SELECTED_FILES_CHANGED_PROPERTY
						.equals(evt.getPropertyName())) {

					// Get list of selected files
					// The selected files should always be the same as newFiles

				}
			}
		});
		// upload only .txt files
		FileNameExtensionFilter filter = new FileNameExtensionFilter(
				"TEXT FILES", "txt", "text");
		fc.setFileFilter(filter);
		panel.add(fc);
		JButton openButton = new JButton("Open a File...");
		openButton.setBounds(200, 50, 150, 25);
		openButton.addActionListener(this);
		panel.add(openButton);

		fileLabel = new JLabel();
		fileLabel.setBounds(50, 50, 250, 25);
		fileLabel.setVisible(false);
		panel.add(fileLabel);

		// Tan generation button
		tanGeneration = new JButton("Generate Tans");
		tanGeneration.setBounds(120, 100, 150, 25);
		tanGeneration.addActionListener(this);
		panel.add(tanGeneration);
		tanGeneration.setVisible(false);

		// Path for the created file with tans
		tansFileLabel = new JLabel();
		tansFileLabel.setBounds(100, 100, 250, 25);
		tansFileLabel.setVisible(false);
		panel.add(tansFileLabel);

	}

	@Override
	public void actionPerformed(ActionEvent arg0) {
		String buttonText = ((JButton) arg0.getSource()).getText();
		switch (buttonText) {
		case "Open a File...":
			// this.hideMe(true);
			int returnVal = fc.showDialog(panel, "Upload");
			if (returnVal == JFileChooser.APPROVE_OPTION) {
				file = fc.getSelectedFile();
				fileLabel.setText(fc.getSelectedFile().getName());
				fileLabel.setVisible(true);
				tanGeneration.setVisible(true);
			}
			break;
		case "Generate Tans":
			// this.hideMe(true);

			if (file.isFile()) {
				panel.repaint();
				bachTanController = new BatchUploadImp(file);
				fileLabel.setVisible(false);
				tanGeneration.setVisible(false);
				userLabel.setVisible(false);
				tansFileLabel.setText("Please find your tans in: "
						+ bachTanController.generateTansFile());
				tansFileLabel.setVisible(true);

			} else {
				JOptionPane.showMessageDialog(null, "Invalid file selected!");
			}

			break;

		}
	}

}
