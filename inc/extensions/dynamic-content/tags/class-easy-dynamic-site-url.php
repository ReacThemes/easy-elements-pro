<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Site_Url extends Tag {

    public function get_name() {
        return 'easy-site-url-dynamic';
    }

    public function get_title() {
        return __( 'Site Url', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-sitetitle-tag';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::URL_CATEGORY,
        ];
    }

    public function is_settings_required() {
        return false;
    }

    protected function register_controls() {
        $this->add_control(
            'easy_url_type',
            [
                'label' => esc_html__('Select URL Type', 'easy-elements-pro'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'home_url',
                'options' => [
                    'home_url'   => esc_html__('Home URL', 'easy-elements-pro'),
                    'site_url'   => esc_html__('Site URL', 'easy-elements-pro'),
                    'admin_url'  => esc_html__('Admin URL', 'easy-elements-pro'),
                ],
            ]
        );
    }

    protected function register_advanced_section() : void {
    }

     /**
     * Core function to get the site URL
     */
    protected function get_site_url(): string {
        return home_url(); 
    }

   
    /**
     * Frontend render output
     */
    public function render(): void {

        $settings = $this->get_settings_for_display();

        $url = home_url();

        if ( ! empty( $settings['easy_url_type'] ) ) {
            switch ( $settings['easy_url_type'] ) {
                case 'site_url':
                    $url = site_url();
                    break;

                case 'admin_url':
                    $url = admin_url();
                    break;

                case 'home_url':
                default:
                    $url = home_url();
                    break;
            }
        }

        echo esc_url( $url );
    }

}
