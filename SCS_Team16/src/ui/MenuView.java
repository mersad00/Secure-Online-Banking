package ui;

import java.awt.Cursor;
import java.awt.Image;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.Box;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPanel;

public class MenuView extends UIView implements ActionListener {

	public MenuView(JPanel panel) {
		super(panel);

		JLabel userLabel = new JLabel(
				"Please choose one of the following options:");
		// userLabel.setBounds(90, 10, 250, 25);
		panel.add(userLabel, "cell 2 0 3 1");
		panel.add(Box.createVerticalStrut(40));

		Image generateButtonImage;
		try {
			generateButtonImage = ImageIO.read(new File("icons/forms.png"));
			JButton generateButton = new JButton("TAN generation form",
					new ImageIcon(generateButtonImage));
			generateButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			generateButton.addActionListener(this);
			panel.add(generateButton, "cell 2 2 1 1");
		} catch (IOException e) {
			// TODO Auto-generated catch block111
			e.printStackTrace();
		}

		Image batchFileGenerationButtonImage;
		try {
			batchFileGenerationButtonImage = ImageIO.read(new File(
					"icons/batch.png"));
			JButton batchFileGenerationButton = new JButton("TAN batch upload",
					new ImageIcon(batchFileGenerationButtonImage));
			batchFileGenerationButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			batchFileGenerationButton.addActionListener(this);
			panel.add(batchFileGenerationButton, "cell 3 2 1 1");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

	@Override
	public void actionPerformed(ActionEvent arg0) {
		String buttonText = ((JButton) arg0.getSource()).getText();
		switch (buttonText) {
		case "TAN generation form":
			this.hideMe(false);
			// /Go to tan generator
			ShowTanGenerator();
			break;
		case "TAN batch upload":
			this.hideMe(false);
			// /Go to register
			ShowBatchUpload();
			break;
		}

	}

}
