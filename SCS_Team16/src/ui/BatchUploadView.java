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
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
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

		Image uploadImage;
		try {
			uploadImage = ImageIO.read(new File("icons/upload124.png"));
			JButton openButton = new JButton("Upload file", new ImageIcon(
					uploadImage));
			openButton.setBounds(100, 50, 150, 25);
			openButton.setBorder(BorderFactory.createEmptyBorder());
			openButton.setContentAreaFilled(false);
			openButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			openButton.addActionListener(this);
			panel.add(openButton);
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		fileLabel = new JLabel();
		fileLabel.setBounds(30, 50, 250, 25);
		fileLabel.setVisible(false);
		panel.add(fileLabel);

		// Tan generation button
		tanGeneration = new JButton("Generate Tans");
		tanGeneration.setBounds(120, 100, 150, 25);
		tanGeneration.addActionListener(this);
		panel.add(tanGeneration);
		tanGeneration.setVisible(false);

		tansFileLabel = new JLabel(
				"Now you can download the file with generated TANs");
		tansFileLabel.setBounds(30, 10, 420, 25);

	}

	@Override
	public void actionPerformed(ActionEvent arg0) {
		String buttonText = ((JButton) arg0.getSource()).getText();
		switch (buttonText) {
		case "Upload file":
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
					downloadButton.setBounds(100, 50, 160, 30);
					downloadButton.setBorder(BorderFactory.createEmptyBorder());
					downloadButton.setContentAreaFilled(false);
					downloadButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
					downloadButton.addActionListener(this);
					panel.add(downloadButton);
					panel.add(tansFileLabel);
					downloadButton.addActionListener(new ActionListener() {

						@Override
						public void actionPerformed(ActionEvent e) {
							String buttonText = ((JButton) e.getSource())
									.getText();
							switch (buttonText) {
							case "Download TANs":
								// save the generated file
								JOptionPane.showMessageDialog(
										null,
										"File successfully downloaded: "
												+ bachTanController
														.generateTansFile());
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
