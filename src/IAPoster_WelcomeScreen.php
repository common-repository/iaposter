<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IAPoster_WelcomeScreen {
	private $minimum_capability = 'manage_options';
	private $transient_name = 'iaposter_welcome_screen';
	private $plugin_name;
    private $file;
    private $slug;
	private $version;

	function __construct( $file, $slug, $version ) {
        $this->file    = $file;
        $this->slug = $slug;
		$this->version = $version;
		$this->plugin_name = 'Image Auto Poster';
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_init', array( $this, 'redirect' ), 11 );
	}

	public function admin_menus() {
		// About Page
		add_dashboard_page(
			sprintf( __( 'Welcome to %s', 'iaposter' ), $this->plugin_name ),
			sprintf( __( 'Welcome to %s', 'iaposter' ), $this->plugin_name ),
			$this->minimum_capability,
			$this->slug . '-welcome',
			array( $this, 'welcome_message' )
		);

		// Now remove them from the menus so plugins that allow customizing the admin menu don't show them
		remove_submenu_page( 'index.php', $this->slug . '-welcome' );
	}

	public function welcome_message() {
		?>
        <div class="wrap about-wrap">
            <h1><?php printf( __( 'Welcome to %s&nbsp;%s', 'iaposter' ), $this->plugin_name, $this->version ); ?></h1>

            <p class="about-text"><?php printf( __( 'Thank you for updating to the latest version! %s allows you to effortlessly share your images on your Pinterest account.', 'iaposter' ), $this->plugin_name, $this->version ); ?></p>

            <hr/>

            <div class="feature-section one-col">
                <h2><?php _e( 'Settings', 'iaposter' ); ?></h2>
                <p class="lead-description"><?php _e( 'The most important part of the plugin is the settings panel.', 'iaposter' ); ?></p>
                <p style="text-align: center;">
                    <img
                        src="<?php echo plugin_dir_url( $this->file ) . '/images/settings.png' ?>"
                        title="<?php _e( 'Settings link', 'iaposter' ); ?>"/>
                </p>
            </div>
            <div class="feature-section one-col">
                <h3><?php _e( 'Everything at hand', 'iaposter' ); ?></h3>
                <p style="margin-left: 0; margin-right: 0;"><?php _e( 'You can find all the links mentioned below in the settings panel.', 'iaposter' ); ?></p>
                <p style="text-align: center;"><img
                            src="<?php echo plugin_dir_url( $this->file ) . '/images/settings_tabs.png' ?>"
                            title="<?php _e( 'Settings tabs', 'iaposter' ); ?>"/>
                </p>
            </div>
            <div class="feature-section two-col">
                <div class="col">
                    <h3><?php _e( 'Settings tab', 'iaposter' ); ?></h3>
                    <p><?php _e( 'In this tab you configure pretty much all plugin\'s settings. You also authorize your Pinterest account here.', 'iaposter' ); ?></p>
					<?php printf( __( '<a href="%s" class="button button-primary">Go to Settings tab &rarr;</a>', 'iaposter' ), admin_url( 'options-general.php?page=' . $this->slug . '_settings&tab=settings' ) ); ?>
                </div>
                <div class="col">
                    <h3><?php _e( 'Pin Queue tab', 'iaposter' ); ?></h3>
                    <p><?php _e( 'Here you can check out which images are in queue to be pinned, delete them from the queue or visit your Pins.', 'iaposter' ); ?></p>
			        <?php printf( __( '<a href="%s" class="button button-primary">Go to Settings tab &rarr;</a>', 'iaposter' ), admin_url( 'options-general.php?page=' . $this->slug . '_settings&tab=settings' ) ); ?>
                </div>
            </div>
            <div class="feature-section two-col">
                <div class="col">
                    <h3><?php _e( 'Logs tab', 'iaposter' ); ?></h3>
                    <p><?php _e( 'In this tab you can monitor plugin\'s activity. Most useful  to check out when there are some errors. Otherwise no need to visit this tab.', 'iaposter' ); ?></p>
			        <?php printf( __( '<a href="%s" class="button button-primary">Go to Logs tab &rarr;</a>', 'iaposter' ), admin_url( 'options-general.php?page=' . $this->slug . '_settings&tab=logs' ) ); ?>
                </div>
                <?php
                ?>
            </div>
            <hr/>
            <div class="feature-section one-col">
                <h2><?php _e( 'Post editor', 'iaposter' ); ?></h2>
                <p class="lead-description"><?php _e( 'Post editor allows you to choose which images should be pinned automatically.', 'iaposter' ); ?></p>
            </div>
            <div class="feature-section two-col">
                <div class="col">
                    <h3><?php _e( 'Select images', 'iaposter' ); ?></h3>
                    <p><?php _e( 'You can select which images should be pinned using a checkbox.', 'iaposter' ); ?></p>
                    <p style="text-align: center;"><img
                                src="<?php echo plugin_dir_url( $this->file ) . '/images/post_editor_images.png' ?>"/>
                    </p>
                </div>
            </div>
            <hr/>
            <div class="feature-section one-col">
                <h2><?php _e( 'Finding help', 'iaposter' ); ?></h2>
                <p class="lead-description"><?php _e( 'If you\'re stuck and can\'t get the plugin to work the way you want it to, get help!', 'iaposter' ); ?></p>
            </div>

            <div class="feature-section two-col">
                <div class="col">
                    <h3><?php _e( 'Documentation', 'iaposter' ); ?></h3>
                    <p><?php printf( __( 'If you are having difficulties with some aspects of the plugin, the first place to look for help is <a href="%s" target="_blank">the documentation</a> of the plugin. Chances are you will find what you are looking for there.', 'iaposter' ), 'https://highfiveplugins.com/iap/iap-documentation/' ); ?></p>
                </div>
                <div class="col">
                    <h3><?php _e( 'Support', 'iaposter' ); ?></h3>
                    <p><?php printf( __( 'You can get support by sending an email to %s.', 'iaposter' ), 'support@highfiveplugins.com' ); ?></p>
                </div>
            </div>
            <hr />
            <div class="feature-section one-col">
                <h2><?php _e( 'Next steps', 'iaposter' ); ?></h2>
                <?php
                ?>
                <?php
                ?>
                <p class="lead-description"><?php echo sprintf( __( 'To start using the plugin go to the <a href="%s">Settings tab</a> and authorize your Pinterest account.', 'iaposter' ), admin_url( 'options-general.php?page=' . $this->slug . '_settings&tab=settings' ) ); ?></p>
                <?php
                ?>
            </div>
        </div>
		<?php
	}

	public function redirect() {
		if ( ! get_transient( $this->transient_name ) ) {
			return;
		}
		delete_transient( $this->transient_name );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}
		wp_safe_redirect( admin_url( 'index.php?page=' . $this->slug . '-welcome' ) );
		exit;
	}
}