package ui;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JTextField;

public class TanGeneratorView implements ActionListener {

	ITanGenerator tanController = new TanGeneratorImp();
	JTextField tanText;
	JTextField accountText;
	JTextField amountText;

	public TanGeneratorView(JPanel panel) {

		panel.setLayout(null);

		JLabel accountLabel = new JLabel("Account");
		accountLabel.setBounds(10, 10, 80, 25);
		panel.add(accountLabel);

		accountText = new JTextField(100);
		accountText.setBounds(100, 10, 160, 25);
		panel.add(accountText);

		JLabel amountLabel = new JLabel("Amount");
		amountLabel.setBounds(10, 45, 80, 25);
		panel.add(amountLabel);

		amountText = new JTextField(100);
		amountText.setBounds(100, 45, 160, 25);
		panel.add(amountText);

		JLabel tanLabel = new JLabel("Tan");
		tanLabel.setBounds(10, 75, 80, 25);
		panel.add(tanLabel);

		tanText = new JTextField(100);
		tanText.setBounds(100, 75, 260, 25);
		panel.add(tanText);

		JButton generateButton = new JButton("generate");
		generateButton.setBounds(100, 110, 110, 25);
		generateButton.addActionListener(this);
		panel.add(generateButton);
	}

	@Override
	public void actionPerformed(ActionEvent e) {
		try {
			int amount = Integer.parseInt(amountText.getText());
			if (accountText.getText().length() == 0 || amount <= 0) {
				JOptionPane.showMessageDialog(null, "Invalid input!");
				return;
			}
			this.tanText.setText(tanController.generateTan(LoginImp.pin,
					accountText.getText(), amountText.getText()));
		} catch (Exception exc) {
			JOptionPane.showMessageDialog(null, "Invalid input!");
		}
	}

}
