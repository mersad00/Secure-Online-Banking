package ui;

import java.awt.Color;
import java.awt.Image;
import java.awt.Window;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.SwingUtilities;

public abstract class UIView {
	JPanel panel;
	static Image icon;

	public UIView(JPanel panel) {
		this.panel = panel;
		this.panel.setBackground(new Color(217, 234, 211));

		try {
			icon = ImageIO.read(new File("icons/bank12.png"));
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	public void hideMe() {
		Window w = SwingUtilities.getWindowAncestor(this.panel);
		w.setVisible(false);
		decideWhatToShow();
	}

	public void hideMe(boolean decideWhatToShow) {
		Window w = SwingUtilities.getWindowAncestor(this.panel);
		w.setVisible(false);
		if (decideWhatToShow) {
			decideWhatToShow();
		}
	}

	public static void main(String[] args) {

		decideWhatToShow();
	}

	static void decideWhatToShow() {
		ILogin loginController = new LoginImp();
		// /if no repo show register otherwise show login
		if (loginController.isUserDefined()) {
			ShowLogin();
		} else {
			ShowRegister();
		}
	}

	static void ShowRegister() {
		JFrame frame = new JFrame("Set up SCS - Secure Banking G16");
		frame.setIconImage(icon);
		frame.setSize(310, 170);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		JPanel panel = new JPanel();
		frame.add(panel);
		RegisterView registerView = new RegisterView(panel);
		frame.setVisible(true);
	}

	static void ShowTanGenerator() {
		JFrame frame = new JFrame("SCS G16 Secure Banking");
		frame.setIconImage(icon);
		frame.setSize(310, 170);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		JPanel panel = new JPanel();
		panel.setBackground(new Color(217, 234, 211));
		frame.add(panel);
		TanGeneratorView tanView = new TanGeneratorView(panel);
		frame.setVisible(true);
	}

	static void ShowLogin() {
		JFrame frame = new JFrame("SCS G16 Secure Banking");
		frame.setIconImage(icon);
		frame.setSize(310, 150);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		JPanel panel = new JPanel();
		frame.add(panel);
		// placeComponents(panel);
		LoginView lv = new LoginView(panel);
		frame.setVisible(true);
	}

	static void ShowMenu() {
		JFrame frame = new JFrame("SCS G16 Secure Banking");
		frame.setIconImage(icon);
		frame.setSize(400, 150);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		JPanel panel = new JPanel();
		frame.add(panel);
		// placeComponents(panel);
		MenuView mv = new MenuView(panel);
		frame.setVisible(true);

	}

	static void ShowBatchUpload() {
		JFrame frame = new JFrame("SCS G16 Secure Banking");
		frame.setIconImage(icon);

		frame.setSize(400, 200);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		JPanel panel = new JPanel();
		frame.setBackground(new Color(217, 234, 211));
		frame.add(panel);
		// placeComponents(panel);
		BatchUploadView buv = new BatchUploadView(panel);
		frame.setVisible(true);

	}

}
