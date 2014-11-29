package ui;

import java.awt.Window;

import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.SwingUtilities;

public abstract class UIView {
	JPanel panel;

	public UIView(JPanel panel) {
		this.panel = panel;
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
		frame.setSize(500, 170);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		JPanel panel = new JPanel();
		frame.add(panel);
		RegisterView registerView = new RegisterView(panel);
		frame.setVisible(true);
	}

	static void ShowTanGenerator() {
		JFrame frame = new JFrame("SCS G16 Secure Banking");
		frame.setSize(500, 200);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

		JPanel panel = new JPanel();
		frame.add(panel);
		TanGeneratorView tanView = new TanGeneratorView(panel);
		frame.setVisible(true);
	}

	static void ShowLogin() {
		JFrame frame = new JFrame("SCS G16 Secure Banking");
		frame.setSize(300, 140);
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		JPanel panel = new JPanel();
		frame.add(panel);
		// placeComponents(panel);
		LoginView lv = new LoginView(panel);
		frame.setVisible(true);
	}

}
