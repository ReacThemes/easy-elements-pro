<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Core\DynamicTags\Module;
use Elementor\Controls_Manager;

class Easy_Dynamic_Request_Parameter extends Tag {

    public function get_name() {
        return 'easy-request-parameter-dynamic';
    }

    public function get_title() {
        return __( 'Request Parameter', 'easy-elements-pro' );
    }

    public function get_group() {
        return 'easy-sitetitle-tag';
    }

    public function get_categories() {
        return [
            \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY,
        ];
    }

    public function is_settings_required() {
        return true;
    }

    protected function register_controls() {
         $this->add_control(
            'request_type',
            [
                'label' => __( 'Request Type', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'get' => __( 'Get', 'easy-elements-pro' ),
                    'post' => __( 'Post', 'easy-elements-pro' ),
                    'query_var' => 'Query Var',
                ],
                'default' => 'get',
            ]
        );

        $this->add_control(
            'easy_parameter_name',
            [
                'label' => __( 'Parameter Name', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'e.g. product_id', 'easy-elements-pro' ),
                'description' => __( 'Enter the query parameter name, e.g., ?product_id=25', 'easy-elements-pro' ),
                'ai' => [
                    'active' => false,
                ],
            ]
        );
    }

    protected function register_advanced_section() {
        easy_advanced_control( $this );
    }

   
    /**
     * Render frontend output
     */
    public function render(): void {
        $settings = $this->get_settings_for_display();
        $type = isset( $settings['request_type'] ) ? $settings['request_type'] : 'get';
        $param_name = isset( $settings['easy_parameter_name'] ) ? sanitize_text_field( $settings['easy_parameter_name'] ) : '';

        if ( empty( $param_name ) ) {
            return;
        }

        $value = '';

        switch ( $type ) {
            case 'get':
                $value = isset( $_GET[$param_name] ) ? sanitize_text_field( wp_unslash( $_GET[$param_name] ) ) : '';
                break;

            case 'post':
                $value = isset( $_POST[$param_name] ) ? sanitize_text_field( wp_unslash( $_POST[$param_name] ) ) : '';
                break;

            case 'query_var':
                $query_value = get_query_var( $param_name );
                if (!empty($query_value)) {
                    $value = sanitize_text_field( $query_value );
                }
                break;
        }

        if ( ! empty( $value ) ) {
            echo wp_kses_post( $value );
        }
    }

    /**
     * Show preview in Elementor editor
     */
    public function get_value_for_editor() {
        ob_start();
        $this->render();
        return ob_get_clean();
    }
}