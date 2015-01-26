package ui;

import javax.swing.JPanel;

import net.miginfocom.swing.MigLayout;

public class SCSJPanel extends JPanel {

	private static final long serialVersionUID = 951876819224590294L;

	public SCSJPanel() {
		super();
		MigLayout layout = new MigLayout("insets 10");

		this.setLayout(layout);

	}

}
