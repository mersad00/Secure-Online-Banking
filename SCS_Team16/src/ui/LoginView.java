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
import javax.swing.JPasswordField;
import javax.swing.JTextField;

public class LoginView extends UIView implements ActionListener {

	ILogin loginController = new LoginImp();
	JTextField pinText;

	public LoginView(JPanel panel) {
		super(panel);

		Image pinImage;
		try {
			pinImage = ImageIO.read(new File("icons/password.png"));
			JLabel userLabel = new JLabel("Pin", new ImageIcon(pinImage), 0);
			// userLabel.setBounds(30, 10, 80, 25);
			// userLabel.setBorder(BorderFactory.createEmptyBorder());

			panel.add(userLabel, "cell 0 0 1 1");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		pinText = new JPasswordField(23);
		panel.add(pinText, "cell 1 0 3 1");

		panel.add(Box.createVerticalStrut(50));

		Image loginImage;
		try {
			loginImage = ImageIO.read(new File("icons/login.png"));

			JButton loginButton = new JButton("login",
					new ImageIcon(loginImage));

			loginButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			loginButton.addActionListener(this);
			panel.add(loginButton, "cell 1 1 1 1");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		Image resetImage;
		try {
			resetImage = ImageIO.read(new File("icons/refresh.png"));
			JButton resetButton = new JButton("reset",
					new ImageIcon(resetImage));

			resetButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			resetButton.addActionListener(this);
			panel.add(resetButton, "cell 2 1 1 1");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		Image settingsImage;
		try {
			settingsImage = ImageIO.read(new File("icons/settings.png"));
			JButton registerButton = new JButton("settings", new ImageIcon(
					settingsImage));
			registerButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			registerButton.addActionListener(this);
			panel.add(registerButton, "cell 3 1 1 1");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

	int attempts = 3;

	@Override
	public void actionPerformed(ActionEvent arg0) {
		String buttonText = ((JButton) arg0.getSource()).getText();
		switch (buttonText) {
		case "login":
			if (loginController.authenticate(pinText.getText())) {

				this.hideMe(false);
				// /Go to tan generator
				ShowMenu();
			} else {
				JOptionPane
						.showMessageDialog(
								null,
								"Wrong pin! \nPlease try again... \nPlease note after three times failure your local data will be reset for security reasons.");
				this.pinText.setText("");
				this.pinText.grabFocus();
				attempts--;
				if (attempts <= 0) {
					if (loginController.resetData()) {
						JOptionPane
								.showMessageDialog(null, "Local data wiped!");
						this.hideMe();
					}
				}
			}
			break;
		case "settings":
			this.hideMe();
			// /Go to register
			ShowRegister();
			break;
		case "reset":
			// /Go to register
			if (loginController.resetData()) {
				JOptionPane.showMessageDialog(null, "SCS has been reset!");
				this.hideMe();
			} else {
				JOptionPane.showMessageDialog(null, "Failed to reset SCS!");
			}
			break;
		}

	}

}
