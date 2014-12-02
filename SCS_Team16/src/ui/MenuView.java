package ui;

import java.awt.Cursor;
import java.awt.Image;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.BorderFactory;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JPanel;

public class MenuView extends UIView implements ActionListener {

	public MenuView(JPanel panel) {
		super(panel);

		JLabel userLabel = new JLabel("Please choose one of the options");
		userLabel.setBounds(90, 10, 250, 25);

		panel.setLayout(null);

		panel.add(userLabel);

		Image generateButtonImage;
		try {
			generateButtonImage = ImageIO.read(new File("icons/forms.png"));
			JButton generateButton = new JButton("TAN generation form",
					new ImageIcon(generateButtonImage));
			generateButton.setBounds(20, 50, 170, 25);
			generateButton.setBorder(BorderFactory.createEmptyBorder());
			generateButton.setContentAreaFilled(false);
			generateButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			generateButton.addActionListener(this);
			panel.add(generateButton);
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		Image batchFileGenerationButtonImage;
		try {
			batchFileGenerationButtonImage = ImageIO.read(new File(
					"icons/batch.png"));
			JButton batchFileGenerationButton = new JButton("TAN batch upload",
					new ImageIcon(batchFileGenerationButtonImage));
			batchFileGenerationButton.setBounds(200, 50, 150, 25);
			batchFileGenerationButton.setBorder(BorderFactory
					.createEmptyBorder());
			batchFileGenerationButton.setContentAreaFilled(false);
			batchFileGenerationButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			batchFileGenerationButton.addActionListener(this);
			panel.add(batchFileGenerationButton);
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
