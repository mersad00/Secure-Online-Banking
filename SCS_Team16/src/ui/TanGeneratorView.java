package ui;

import java.awt.Cursor;
import java.awt.Image;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JTextField;

public class TanGeneratorView extends UIView implements ActionListener {

	ITanGenerator tanController = new TanGeneratorImp();
	JTextField tokenText;
	JTextField tanText;
	JTextField accountText;
	JTextField amountText;

	public TanGeneratorView(JPanel panel) {
		super(panel);
		JLabel titleLabel = new JLabel("Transaction Form");
		panel.add(titleLabel, "cell 0 0 2 1");

		JLabel tokenLabel = new JLabel("Token");
		panel.add(tokenLabel, "cell 0 1 2 1");

		tokenText = new JTextField(20);
		panel.add(tokenText, "cell 1 1 2 1");

		JLabel accountLabel = new JLabel("Target Account");
		panel.add(accountLabel, "cell 0 2 1 1");

		accountText = new JTextField(20);
		panel.add(accountText, "cell 1 2 1 1");

		JLabel amountLabel = new JLabel("Amount");
		panel.add(amountLabel, "cell 0 3 1 1");

		amountText = new JTextField(20);
		panel.add(amountText, "cell 1 3 1 1");

		JLabel tanLabel = new JLabel("TAN");
		panel.add(tanLabel, "cell 0 4 3 1");

		tanText = new JTextField(20);
		panel.add(tanText, "cell 1 4 3 1");

		Image generateImage;
		try {
			generateImage = ImageIO.read(new File("icons/light59.png"));
			JButton generateButton = new JButton("generate", new ImageIcon(
					generateImage));

			generateButton.setCursor(new Cursor(Cursor.HAND_CURSOR));
			generateButton.addActionListener(this);
			panel.add(generateButton, "cell 0 5 ");
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

	@Override
	public void actionPerformed(ActionEvent e) {
		try {
			int amount = Integer.parseInt(amountText.getText());
			if (tokenText.getText().length() == 0
					|| accountText.getText().length() == 0 || amount <= 0) {
				JOptionPane.showMessageDialog(null, "Invalid input!");
				return;
			}
			String generatedTan = tanController.generateTan(LoginImp.pin,
					tokenText.getText(), accountText.getText(),
					amountText.getText());
			if (generatedTan.length() == 0) {
				JOptionPane.showMessageDialog(null, "Invalid input!");
				return;
			}
			this.tanText.setText(generatedTan);
		} catch (Exception exc) {
			JOptionPane.showMessageDialog(null, "Invalid input!");
		}
	}

}
