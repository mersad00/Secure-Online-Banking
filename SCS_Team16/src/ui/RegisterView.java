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

		JLabel titleLabel = new JLabel("Setup your SCS account");
		panel.add(titleLabel, "cell 0 0 2 1");

		JLabel accountLabel = new JLabel("Account");
		panel.add(accountLabel, "cell 0 1 1 1");
		panel.add(Box.createHorizontalStrut(10));
		accountText = new JTextField(20);
		panel.add(accountText, "cell 1 1 1 1");

		panel.add(Box.createVerticalStrut(10));

		JLabel amountLabel = new JLabel("A-Key");
		panel.add(amountLabel, "cell 0 2 1 1");
		panel.add(Box.createHorizontalStrut(10));
		sessionKeyText = new JTextField(20);
		panel.add(sessionKeyText, "cell 1 2 1 1 ");

		panel.add(Box.createVerticalStrut(10));

		JLabel pinLabel = new JLabel("Pin");
		panel.add(pinLabel, "cell 0 3 1 1 ");
		panel.add(Box.createHorizontalStrut(10));
		pinText = new JTextField(20);
		panel.add(pinText, "cell 1 3 1 1 ");

		Image saveImage;
		try {
			saveImage = ImageIO.read(new File("icons/save28.png"));
			JButton registerButton = new JButton("save", new ImageIcon(
					saveImage));

			registerButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			registerButton.addActionListener(this);
			panel.add(registerButton, "cell 0 4");
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
