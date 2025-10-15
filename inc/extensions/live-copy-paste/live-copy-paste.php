<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Easy_Live_Copy_Paste {

    /**
     * Singleton instance
     * @var Easy_Live_Copy_Paste|null
     */
    protected static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {

        $tab_slug = 'extensions';
        $extensions_settings = get_option('easy_element_' . $tab_slug, [] );

        $enable_live_copy_paste = isset( $extensions_settings['enable_live_copy_paste'] ) ? $extensions_settings['enable_live_copy_paste'] : 0;

        if(  (int) $enable_live_copy_paste !== 1 ) {
            return;
        }

        add_action( 'init', [ $this, 'easy_init' ] );
    }

    /**
     * Get instance
     */
    public static function getInstance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize actions
     */
    public function easy_init() {

        add_action( 'elementor/kit/register_tabs', [ $this, 'easy_live_copy_panel_control' ] );
        add_action('elementor/element/common/_section_style/after_section_end', [ $this, 'easy_live_copy_register_section'] );
		add_action('elementor/element/section/section_advanced/after_section_end', [ $this, 'easy_live_copy_register_section'] );

        add_action('elementor/element/common/easy_live_copy_section/before_section_end', [ $this, 'easy_live_copy_register_controls'], 10, 2 );
 		add_action('elementor/element/section/easy_live_copy_section/before_section_end', [ $this, 'easy_live_copy_register_controls'], 10, 2 );

        add_action('elementor/element/container/section_layout/after_section_end', [ $this, 'easy_live_copy_register_section'] );

        add_action('elementor/element/container/easy_live_copy_section/before_section_end', [ $this, 'easy_live_copy_register_controls'], 10, 2 );

        add_action( 'wp_enqueue_scripts', [ $this, 'easy_wp_enqueue_scripts' ] );
        add_action( 'wp_ajax_easy_download_section_json', [ $this, 'ajax_download_section_json' ] );
        add_action( 'wp_ajax_nopriv_easy_download_section_json', [ $this, 'ajax_download_section_json' ] );

        add_action( 'wp_ajax_easy_copy_section_json', [ $this, 'ajax_copy_section_json_func' ] );
        add_action( 'wp_ajax_nopriv_easy_copy_section_json', [ $this, 'ajax_copy_section_json_func' ] );

        $this->easy_live_copy_settings();

    }

    public function easy_live_copy_settings() {
        $kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();

        if ( ! $kit ) {
            return;
        }

        $settings = $kit->get_settings_for_display();

        $easy_enable_live_copy = (
            isset($settings['easy_enable_live_copy']) && $settings['easy_enable_live_copy'] === 'yes'
        );

        $enable_copy_logged_in_user = (
            isset($settings['enable_copy_logged_in_user']) && $settings['enable_copy_logged_in_user'] === 'yes'
        );

        if( $easy_enable_live_copy == 'yes') {
            update_option( "easy_live_copy_btn_enable" , true );

            if( $enable_copy_logged_in_user == 'yes' ) {
                update_option( "easy_live_copy_btn_logged_in_user" , true );
            } else {
                update_option( "easy_live_copy_btn_logged_in_user" , false );
            }

        } else {
            update_option( "easy_live_copy_btn_enable" , false );
            update_option( "easy_live_copy_btn_logged_in_user" , false );
        }
    }


     /**
     * AJAX: Download Section JSON
     */
    public function ajax_download_section_json() {
        check_ajax_referer( 'easy_live_copy_nonce', 'security' );

        if ( empty( $_POST['section_id'] ) ) {
            wp_send_json_error(['message' => 'Missing section ID']);
        }

        $section_id = sanitize_text_field( $_POST['section_id'] );

        $post_id = absint( $_POST['post_id'] ?? get_the_ID() );
        $document = \Elementor\Plugin::$instance->documents->get( $post_id );

        if ( ! $document ) {
            wp_send_json_error(['message' => 'No Elementor document found']);
        }

        $data = $document->get_elements_data();

        // Find the requested section
        $section_data = null;
        foreach ( $data as $element ) {
            if ( isset( $element['id'] ) && $element['id'] === $section_id ) {
                $section_data = $element;
                break;
            }
        }

        if ( ! $section_data ) {
            wp_send_json_error(['message' => 'Section not found']);
        }

        $export_data = [
            'content' => [ $section_data ],
            'type' => $section_data['elType'] ?? 'section',
            'title' => get_the_title( $post_id ),
            'version' => '1.0',
            'page_settings' => [],
        ];

        // Save JSON
        $upload_dir = wp_upload_dir();
        $dir = $upload_dir['basedir'] . '/easy-live-copy';
        if ( ! file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
        }

        $filename  = 'section-' . $section_id . '.json';
        $filepath  = $dir . '/' . $filename;
        $json_data = wp_json_encode( $export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

        file_put_contents( $filepath, $json_data );

        wp_send_json_success([
            'json_url'  => $upload_dir['baseurl'] . '/easy-live-copy/' . $filename,
            'filename'  => $filename,
        ]);
    }

    public function ajax_copy_section_json_func() {
        check_ajax_referer( 'easy_live_copy_nonce', 'security' );

        if ( empty( $_POST['section_id'] ) ) {
            wp_send_json_error(['message' => 'Missing section ID']);
        }

        $section_id = sanitize_text_field( $_POST['section_id'] );

        $post_id = absint( $_POST['post_id'] ?? get_the_ID() );
        $document = \Elementor\Plugin::$instance->documents->get( $post_id );

        if ( ! $document ) {
            wp_send_json_error(['message' => 'No Elementor document found']);
        }

        $data = $document->get_elements_data();

        // Find the requested section
        $section_data = null;
        foreach ( $data as $element ) {
            if ( isset( $element['id'] ) && $element['id'] === $section_id ) {
                $section_data = $element;
                break;
            }
        }

        if ( ! $section_data ) {
            wp_send_json_error(['message' => 'Section not found']);
        }

        $site_url  = site_url( '/wp-json/' );

        $export_data = [
            "type" => 'elementor',
            "siteurl" => $site_url,
            'elements' => [ $section_data ],
        ];

        // Save JSON
        $upload_dir = wp_upload_dir();
        $dir = $upload_dir['basedir'] . '/easy-live-copy';
        if ( ! file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
        }

        $filename  = 'section-' . $section_id . '.json';
        $filepath  = $dir . '/' . $filename;
        $json_data = wp_json_encode( $export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

        file_put_contents( $filepath, $json_data );

        wp_send_json_success([
            'json_url'  => $upload_dir['baseurl'] . '/easy-live-copy/' . $filename,
            'filename'  => $filename,
        ]);
    }

    public function easy_wp_enqueue_scripts() {

        wp_enqueue_style(
            'easy-live-copy-paste-css',
            EASYELEMENTS_PRO_URL . 'inc/extensions/live-copy-paste/assets/css/live-copy-paste.css',array(),
            time()
        );
        wp_enqueue_script(
            'easy-live-copy-paste-js',
            EASYELEMENTS_PRO_URL . 'inc/extensions/live-copy-paste/assets/js/easy-live-copy.js',
            ['jquery'],
            time(),
            true
        );

        wp_localize_script(
            'easy-live-copy-paste-js',
            'easy_live_copypaste',
            [
                'nonce' => wp_create_nonce('easy_live_copy_nonce'),
                'ajax_url' => admin_url('admin-ajax.php'),
                'easy_logged_in_user' => get_option( 'easy_live_copy_btn_logged_in_user' ),
                
            ]
        );
    }

    public function easy_live_copy_register_section( $element ) {

        $enable_live_copy = get_option( 'easy_live_copy_btn_enable', true );

        if(  ( int ) $enable_live_copy !== 1 ) {
            return;
        }

        $element->start_controls_section(
            'easy_live_copy_section',
            [
                'label' => __( 'Easy Live Copy Paste', 'easy-elements-pro' ),
                'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );
       
		$element->end_controls_section();
	}

    /**
	 * Register visibility controls in Elementor panel
	 */
	public function easy_live_copy_register_controls( $element, $args ) {

		require_once EASYELEMENTS_PRO_PATH . 'inc/extensions/live-copy-paste/includes/controls/class-copy-paste-advanced-control.php';
		Easy_Live_Copy_Paste_Section::register_controls( $element );
	}

    /**
     * Register Live Copy Paste settings tab in Elementor Site Settings
     */
    public function easy_live_copy_panel_control( $kit ) {

        $file_path = EASYELEMENTS_PRO_PATH . 'inc/extensions/live-copy-paste/includes/class-settings-live-copy.php';
        
        if ( file_exists( $file_path ) ) {
            require_once $file_path;
            $kit->register_tab(
                'easy-settings-live-copy',
                \EasyElements\Includes\Settings\Easy_Settings_Live_Copy::class
            );
        } 
    }
}

// Initialize singleton
Easy_Live_Copy_Paste::getInstance();