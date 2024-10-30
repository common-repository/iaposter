<?php

class IAPoster_Screen_Settings {

	private $file;
	private $slug;
	private $screen_id;
	private $version;
	private $save_settings_action;
	/**
	 * @var IAPoster_Settings_Base[]
	 */
	private $settings_modules;

	function __construct( $file, $version, $slug ) {
		$this->file                 = $file;
		$this->version              = $version;
		$this->slug                 = $slug;
		$this->save_settings_action = $slug . '_save_settings';
		$this->settings_modules     = array(
			'settings' => new IAPoster_Settings_Main( $slug ),
		);
		$this->add_actions();
	}

	function add_actions() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_action( 'admin_init', array( $this, 'process_actions' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	function add_settings_page() {
		$this->screen_id = add_options_page(
			__( 'Image Auto Poster Settings', 'iaposter' ),
			__( 'Image Auto Poster', 'iaposter' ),
			'manage_options',
			$this->slug . '_settings',
			array( $this, 'render_settings' )
		);
	}

	function enqueue_scripts() {
		$screen = get_current_screen();
		$tab    = $this->get_current_tab();
		if ( $this->screen_id != $screen->id || ! in_array( $tab, array_keys( $this->settings_modules ) ) ) {
			return;
		}
		$settings_module = $this->settings_modules[ $tab ];
		$settings = array(
			'save'     => array(
				'post_url' => add_query_arg( 'tab', $tab, admin_url( 'options-general.php?page=' . $this->slug . '_settings' ) ),
				'action'   => $this->save_settings_action,
				'nonce'    => wp_create_nonce( $this->save_settings_action ),
				'tab' => $tab,
			),
			'settings' => $settings_module->get_settings_configuration(),
			'i18n'     => array(
				'editor' => $settings_module->get_settings_i18n(),
			),
		);
		wp_enqueue_script( $this->slug . 'settings-script', plugin_dir_url( $this->file ) . 'js/settings.min.js', array( 'jquery' ), $this->version, true );
		wp_localize_script( $this->slug . 'settings-script', $this->slug . '_settings', $settings );
		wp_enqueue_style( $this->slug . 'settings-style', plugin_dir_url( $this->file ) . 'css/settings.css', array(), $this->version );
	}

	private function get_current_tab() {
		return isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings';
	}

	function process_actions() {
		$tab = $this->get_current_tab();
		if ( 'queue' === $tab ) {
			$list_table = new IAPoster_UI_QueueListTable( $this->slug );
			$list_table->process_actions();
		}

		if ( 'logs' === $tab ) {
			$list_table = new IAPoster_UI_LogsListTable( $this->slug );
			$list_table->process_actions();
		}
	}

	function save_settings() {
		$return_condition = ! isset( $_POST[ $this->save_settings_action ] ) ||
		                    ! wp_verify_nonce( $_POST[ $this->save_settings_action ], $this->save_settings_action );
		if ( $return_condition ) {
			return;
		}
		$tab = $this->get_current_tab();
		$settings_module = $this->settings_modules[ $tab ];
		$settings_module->save_settings( $_POST );
	}

	function render_settings() {
		$tabs = array();
		foreach( $this->settings_modules as $slug => $module ) {
			$mod_settings = $module->get_module_settings();
			$tabs[ $slug ] = $mod_settings['name'];
		}
		$tabs['queue'] = __( 'Pin Queue', 'iaposter' );
		$tabs['logs'] = __( 'Logs', 'iaposter' );
		$tab  = $this->get_current_tab();
		?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
				<?php foreach ( $tabs as $tab_slug => $tab_name ): ?>
                    <a href="<?php echo add_query_arg( 'tab', $tab_slug, admin_url( 'options-general.php?page=' . $this->slug . '_settings' ) ); ?>"
                       class="nav-tab <?php echo $tab === $tab_slug ? 'nav-tab-active' : ''; ?>">
						<?php echo $tab_name; ?>
                    </a>
				<?php endforeach; ?>
                <a href="https://highfiveplugins.com/iap/iap-documentation/" target="_blank"
                   class="nav-tab"><?php _e( 'Documentation', 'iaposter' ); ?></a>
                <a href="mailto:support@highfiveplugins.com" target="_top" class="nav-tab"><?php _e( 'Support', 'iaposter' ); ?></a>
            </h2>
			<?php
			if ( 'queue' === $tab ) {
				$this->render_queue_page();
			} else if ( 'logs' === $tab ) {
				$this->render_logs_page();
			} else if ( 'settings' === $tab || 'license' === $tab ) {
				$this->render_settings_page( $tab );
			}
			?>
        </div>
		<?php
	}

	function render_queue_page() {
		$list_table = new IAPoster_UI_QueueListTable( $this->slug );
		$list_table->prepare_items();
		$list_table->css();
		$list_table->notifications();
		echo '<form method="post">';
		$list_table->views();
		$list_table->display();
		echo '</form>';
	}

	function render_logs_page() {
		$list_table = new IAPoster_UI_LogsListTable( $this->slug );
		$list_table->prepare_items();
		$list_table->css();
		$list_table->notifications();
		echo '<form method="post">';
		$list_table->views();
		$list_table->display();
		echo '</form>';
	}

	function render_settings_page( $tab ) {
		$this->render_settings_page_css();
		echo '<div id="iaposter-container"></div>';
	}

	function render_settings_page_css() {
		?>
        <style type="text/css">
            .dashicons.iaposter-spin {
                animation: dashicons-iaposter-spin 1s infinite;
                animation-timing-function: linear;
            }

            @keyframes dashicons-iaposter-spin {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
        </style>
		<?php
	}
}