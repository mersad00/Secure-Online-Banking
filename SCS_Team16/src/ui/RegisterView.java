package ui;

import java.awt.Window;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JTextField;
import javax.swing.SwingUtilities;

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
		amountLabel.setBounds(10, 45, 80, 25);
		panel.add(amountLabel);

		sessionKeyText = new JTextField(100);
		sessionKeyText.setBounds(100, 45, 260, 25);
		panel.add(sessionKeyText);

		JLabel pinLabel = new JLabel("Pin");
		pinLabel.setBounds(10, 75, 80, 25);
		panel.add(pinLabel);

		pinText = new JTextField(100);
		pinText.setBounds(100, 75, 260, 25);
		panel.add(pinText);

		JButton registerButton = new JButton("save");
		registerButton.setBounds(100, 110, 110, 25);
		registerButton.addActionListener(this);
		panel.add(registerButton);
	}

	@Override
	public void actionPerformed(ActionEvent e) {
		if(pinText.getText().length() <4 || accountText.getText().length() == 0 || sessionKeyText.getText().length()<5){
			JOptionPane.showMessageDialog(null, "Invalid input!\n-Minimum pin length is 4 chars\n-Minimum A-Key is 5 chars");
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
