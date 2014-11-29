package ui;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowEvent;
import java.awt.event.WindowListener;

import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JTextField;
import javax.swing.JPasswordField;

public class LoginView extends UIView implements  ActionListener {

	
	ILogin loginController = new LoginImp();
	JTextField pinText;

	public LoginView(JPanel panel) {
		super(panel);

		panel.setLayout(null);

		JLabel userLabel = new JLabel("Pin");
		userLabel.setBounds(10, 10, 80, 25);
		panel.add(userLabel);

		pinText = new JPasswordField(20);
		pinText.setBounds(100, 10, 160, 25);
		panel.add(pinText);

		JButton loginButton = new JButton("login");
		loginButton.setBounds(10, 80, 80, 25);
		loginButton.addActionListener(this);
		panel.add(loginButton);

		JButton resetButton = new JButton("reset");
		resetButton.setBounds(95, 80, 80, 25);
		resetButton.addActionListener(this);
		panel.add(resetButton);

		JButton registerButton = new JButton("settings");
		registerButton.setBounds(180, 80, 100, 25);
		registerButton.addActionListener(this);
		panel.add(registerButton);
	}
	int attempts=3;
	@Override
	public void actionPerformed(ActionEvent arg0) {
		String buttonText = ((JButton) arg0.getSource()).getText();
		switch (buttonText) {
		case "login":
			if (loginController.authenticate(pinText.getText())) {
				
				this.hideMe(false);
				// /Go to tan generator
				ShowTanGenerator();
			} else {
				JOptionPane
						.showMessageDialog(
								null,
								"Wrong pin! \nPlease try again... \nPlease note after three times failure your local data will be reset for security reasons.");
			this.pinText.setText("");
			this.pinText.grabFocus();
			attempts--;
			if(attempts<=0){
				if(loginController.resetData()){
					JOptionPane.showMessageDialog(null, "Local data wiped!");
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
