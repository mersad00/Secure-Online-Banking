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
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JTextField;

public class RegisterView extends UIView implements ActionListener {
	ILogin loginController = new LoginImp();
	JTextField accountText;
	JTextField sessionKeyText;
	JTextField pinText;

	public RegisterView(JPanel panel) {
		super(panel);

		panel.setLayout(null);

		JLabel accountLabel = new JLabel("Account");
		accountLabel.setBounds(10, 10, 80, 25);
		panel.add(accountLabel);

		accountText = new JTextField(100);
		accountText.setBounds(100, 10, 160, 25);
		panel.add(accountText);

		JLabel amountLabel = new JLabel("A-Key");
		amountLabel.setBounds(10, 40, 80, 25);
		panel.add(amountLabel);

		sessionKeyText = new JTextField(100);
		sessionKeyText.setBounds(100, 40, 160, 25);
		panel.add(sessionKeyText);

		JLabel pinLabel = new JLabel("Pin");
		pinLabel.setBounds(10, 70, 80, 25);
		panel.add(pinLabel);

		pinText = new JTextField(100);
		pinText.setBounds(100, 70, 160, 25);
		panel.add(pinText);

		Image saveImage;
		try {
			saveImage = ImageIO.read(new File("icons/save28.png"));
			JButton registerButton = new JButton("save", new ImageIcon(
					saveImage));
			registerButton.setBounds(100, 100, 110, 25);
			registerButton.setBorder(BorderFactory.createEmptyBorder());
			registerButton.setContentAreaFilled(false);
			registerButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			registerButton.addActionListener(this);
			panel.add(registerButton);
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

	@Override
	public void actionPerformed(ActionEvent e) {
		if (pinText.getText().length() < 4
				|| accountText.getText().length() == 0
				|| sessionKeyText.getText().length() < 5) {
			JOptionPane
					.showMessageDialog(null,
							"Invalid input!\n-Minimum pin length is 4 chars\n-Minimum A-Key is 5 chars");
			return;
		}
		if (loginController.register(pinText.getText(), accountText.getText(),
				sessionKeyText.getText())) {
			JOptionPane.showMessageDialog(null, "Successfully saved!");
			this.hideMe();
		} else {
			JOptionPane.showMessageDialog(null, "Failed to save!");
		}

	}

}
