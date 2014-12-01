package ui;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JTextField;
import javax.swing.JPasswordField;

public class MenuView extends UIView implements  ActionListener {

	public MenuView(JPanel panel) {
		super(panel);
		panel.setLayout(null);

		JLabel userLabel = new JLabel("Please choose one of the options");
		userLabel.setBounds(90, 10, 250, 25);
		panel.add(userLabel);
		
		JButton generateButton = new JButton("TAN generation form");
		generateButton.setBounds(20, 50, 170, 25);
		generateButton.addActionListener(this);
		panel.add(generateButton);
		
		JButton batchFileGenerationButton = new JButton("TAN batch upload");
		batchFileGenerationButton.setBounds(200, 50, 150, 25);
		batchFileGenerationButton.addActionListener(this);
		panel.add(batchFileGenerationButton);
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
