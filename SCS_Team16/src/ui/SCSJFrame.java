package ui;

import java.awt.Image;
import java.io.File;
import java.io.IOException;

import javax.imageio.ImageIO;
import javax.swing.JFrame;
import javax.swing.JRootPane;
import javax.swing.UIManager;

public class SCSJFrame extends JFrame {

	private static final long serialVersionUID = 2512677511650848366L;
	static Image icon;

	public SCSJFrame(String title, int width, int height) {
		super(title);
		try {
			icon = ImageIO.read(new File("icons/bank12.png"));
		} catch (IOException e) {
			e.printStackTrace();
		}
		// JFrame configurations
		this.setIconImage(icon);
		this.setResizable(false);
		this.setSize(width, height);
		// pack();
		this.setLocationRelativeTo(null);
		this.getRootPane().setWindowDecorationStyle(JRootPane.FRAME);
		this.getRootPane().setFont(UIManager.getFont("SystemFont"));
		this.getRootPane().putClientProperty("Quaqua.RootPane.isVertical",
				Boolean.FALSE);
		this.getRootPane().putClientProperty("Quaqua.RootPane.isPalette",
				Boolean.FALSE);
		this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);

	}

}
