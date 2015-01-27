package ui;

import java.awt.Cursor;
import java.awt.Image;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.beans.PropertyChangeEvent;
import java.beans.PropertyChangeListener;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JFileChooser;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.filechooser.FileNameExtensionFilter;

public class BatchUploadView extends UIView implements ActionListener {

	File file;
	JFileChooser fc;
	JButton openButton, tanGeneration;

	JLabel fileLabel, tansFileLabel, userLabel;
	IBatchUpload bachTanController;

	JTextArea tokenText;
	JScrollPane scrollPane;

	public BatchUploadView(JPanel panel) {
		super(panel);

		userLabel = new JLabel("Please choose the batch file (.txt)");
		panel.add(userLabel, "cell 0 0 1 1");
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
		fc.setVisible(false);
		panel.add(fc, "cell 2 0 1 1");

		Image uploadImage;
		try {

			tokenText = new JTextArea(4, 30);
			tokenText.setText("Enter token here...");
			scrollPane = new JScrollPane(tokenText);
			panel.add(scrollPane, "cell 0 1");
			scrollPane.setVisible(false);

			uploadImage = ImageIO.read(new File("icons/upload124.png"));
			JButton openButton = new JButton("Upload file", new ImageIcon(
					uploadImage));
			openButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			openButton.addActionListener(this);
			panel.add(openButton, "cell 1 0");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		fileLabel = new JLabel();
		fileLabel.setVisible(false);
		panel.add(fileLabel, "cell 2 1");

		// Tan generation button
		tanGeneration = new JButton("Generate Tans");
		tanGeneration.addActionListener(this);
		panel.add(tanGeneration, "cell 1 1");
		tanGeneration.setVisible(false);

		tansFileLabel = new JLabel("Download the file with generated TANs");

	}

	@Override
	public void actionPerformed(ActionEvent arg0) {
		String buttonText = ((JButton) arg0.getSource()).getText();
		switch (buttonText) {
		case "Upload file":
			// this.hideMe(true);
			fc.setVisible(true);
			int returnVal = fc.showDialog(panel, "Upload");
			if (returnVal == JFileChooser.APPROVE_OPTION) {
				file = fc.getSelectedFile();
				fileLabel.setText(fc.getSelectedFile().getName());
				fileLabel.setVisible(true);
				tanGeneration.setVisible(true);
			}
			scrollPane.setVisible(true);
			break;
		case "Generate Tans":
			if (tokenText.getText().length() == 0) {
				JOptionPane.showMessageDialog(null, "Invalid input!");
				return;
			}
			scrollPane.setVisible(false);
			if (file.isFile()) {
				// this.hideMe(true);
				bachTanController = new BatchUploadImp(file);
				tanGeneration.setVisible(false);
				fileLabel.setVisible(false);
				userLabel.setVisible(false);
				panel.removeAll();
				Image downloadImage;
				try {
					downloadImage = ImageIO
							.read(new File("icons/download.png"));
					JButton downloadButton = new JButton("Download TANs",
							new ImageIcon(downloadImage));

					downloadButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
					downloadButton.addActionListener(this);
					panel.add(downloadButton, "cell 1 0 1 1");
					panel.add(tansFileLabel, "cell 0 0 1 1");
					downloadButton.addActionListener(new ActionListener() {

						@Override
						public void actionPerformed(ActionEvent e) {
							String buttonText = ((JButton) e.getSource())
									.getText();
							switch (buttonText) {
							case "Download TANs":
								// save the generated file
								JOptionPane
										.showMessageDialog(
												null,
												"File successfully downloaded: "
														+ bachTanController
																.generateTansFile(tokenText
																		.getText()));
							}

						}
					});
					panel.revalidate();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}

			} else {
				JOptionPane.showMessageDialog(null, "Invalid file selected!");
			}

			break;

		}
	}
}
