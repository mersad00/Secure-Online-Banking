package ui;

import java.awt.BorderLayout;
import java.awt.Window;

import javax.swing.JDialog;
import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.SwingUtilities;
import javax.swing.UIManager;

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

	/**
	 * @wbp.parser.entryPoint
	 */
	public static void main(String[] args) {
		// set system properties here that affect Quaqua
		// for example the default layout policy for tabbed
		// panes:
		System.setProperty("Quaqua.tabLayoutPolicy", "wrap");
		// set the Quaqua Look and Feel in the UIManager
		try {
			UIManager.setLookAndFeel(ch.randelshofer.quaqua.QuaquaManager
					.getLookAndFeel());
			// set UI manager properties here that affect Quaqua
		} catch (Exception e) {
			// take an appropriate action here

		}
		decideWhatToShow();
	}

	static void decideWhatToShow() {
		// UI configurations
		JFrame.setDefaultLookAndFeelDecorated(true);
		JDialog.setDefaultLookAndFeelDecorated(true);
		ILogin loginController = new LoginImp();
		// /if no repo show register otherwise show login
		if (loginController.isUserDefined()) {
			ShowLogin();
		} else {
			ShowRegister();
		}
	}

	static void ShowRegister() {
		SCSJFrame frame = new SCSJFrame("Set up SCS - Secure Banking G16", 300,
				220);
		SCSJPanel panel = new SCSJPanel();
		frame.getContentPane().add(panel, BorderLayout.CENTER);
		RegisterView registerView = new RegisterView(panel);
		frame.setVisible(true);
	}

	static void ShowTanGenerator() {
		SCSJFrame frame = new SCSJFrame("SCS G16 Secure Banking", 350, 280);
		SCSJPanel panel = new SCSJPanel();
		frame.getContentPane().add(panel, BorderLayout.CENTER);
		TanGeneratorView tanView = new TanGeneratorView(panel);
		frame.setVisible(true);
	}

	static void ShowLogin() {
		SCSJFrame frame = new SCSJFrame("SCS G16 Secure Banking", 380, 150);
		SCSJPanel panel = new SCSJPanel();
		frame.getContentPane().add(panel, BorderLayout.CENTER);
		LoginView lv = new LoginView(panel);
		frame.setVisible(true);

	}

	static void ShowMenu() {

		SCSJFrame frame = new SCSJFrame("SCS G16 Secure Banking", 410, 150);
		SCSJPanel panel = new SCSJPanel();
		frame.getContentPane().add(panel);
		MenuView mv = new MenuView(panel);
		frame.setVisible(true);

	}

	static void ShowBatchUpload() {
		SCSJFrame frame = new SCSJFrame("SCS G16 Secure Banking", 420, 180);

		SCSJPanel panel = new SCSJPanel();
		frame.getContentPane().add(panel);
		BatchUploadView buv = new BatchUploadView(panel);
		frame.setVisible(true);

	}

}
