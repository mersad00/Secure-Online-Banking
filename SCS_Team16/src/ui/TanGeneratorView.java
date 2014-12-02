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

public class TanGeneratorView implements ActionListener {

	ITanGenerator tanController = new TanGeneratorImp();
	JTextField tanText;
	JTextField accountText;
	JTextField amountText;

	public TanGeneratorView(JPanel panel) {

		panel.setLayout(null);

		JLabel accountLabel = new JLabel("Target Acc.");
		accountLabel.setBounds(10, 10, 80, 25);
		panel.add(accountLabel);

		accountText = new JTextField(100);
		accountText.setBounds(100, 10, 160, 25);
		panel.add(accountText);

		JLabel amountLabel = new JLabel("Amount");
		amountLabel.setBounds(10, 40, 80, 25);
		panel.add(amountLabel);

		amountText = new JTextField(100);
		amountText.setBounds(100, 40, 160, 25);
		panel.add(amountText);

		JLabel tanLabel = new JLabel("Tan");
		tanLabel.setBounds(10, 70, 80, 25);
		panel.add(tanLabel);

		tanText = new JTextField(100);
		tanText.setBounds(100, 70, 160, 25);
		panel.add(tanText);

		Image generateImage;
		try {
			generateImage = ImageIO.read(new File("icons/light59.png"));
			JButton generateButton = new JButton("generate", new ImageIcon(
					generateImage));
			generateButton.setBounds(100, 100, 110, 25);
			generateButton.setBorder(BorderFactory.createEmptyBorder());
			generateButton.setContentAreaFilled(false);
			generateButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			generateButton.addActionListener(this);
			panel.add(generateButton);
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

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
