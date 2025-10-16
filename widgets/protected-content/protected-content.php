<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Easy_Protected_Content_Widget extends Widget_Base {

    public function get_name() {
        return 'easy-protected-content';
    }

    /**
     * Enqueue styles and scripts specific to this widget
     */
    public function get_style_depends() {

        wp_register_style(
            'easy-protected-content-style',
            EASYELEMENTS_PRO_URL . 'widgets/protected-content/assets/css/easy-protected-content.css',
            [],
            time()
        );

        return [ 'easy-protected-content-style' ];
    }

    public function get_script_depends() {

        wp_register_script(
            'easy-protected-content-script',
            EASYELEMENTS_PRO_URL . 'widgets/protected-content/assets/js/easy-protected-content.js',
            [ 'jquery' ],
            time(),
            true
        );

        return [ 'easy-protected-content-script' ];
    }

    public function get_title() {
        return __( 'Protected Content', 'easy-elements-pro' );
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'protected', 'restricted', 'members', 'login', 'password' ];
    }

    protected function register_controls() {

        /**
         * ==============================
         * Content Type Section
         * ==============================
         */
        $this->start_controls_section(
            'section_content_type',
            [
                'label' => __( 'Content Type', 'easy-elements-pro' ),
            ]
        );

        $this->add_control(
            'content_type',
            [
                'label' => __( 'Select Type', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'content'  => __( 'Select Content', 'easy-elements-pro' ),
                    'template' => __( 'Select Template', 'easy-elements-pro' ),
                ],
                'default' => 'content',
            ]
        );

        $this->add_control(
            'protected_text',
            [
                'label' => __( 'Protected Content', 'easy-elements-pro' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __( 'This content is protected. You can either set a password or restrict access based on user roles to view it.
', 'easy-elements-pro' ),
                'condition' => [ 'content_type' => 'content' ],
            ]
        );

        $this->add_control(
            'template_id',
            [
                'label' => __( 'Select Template', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => $this->get_elementor_templates(),
                'condition' => [ 'content_type' => 'template' ],
            ]
        );

        $this->end_controls_section();

        /**
         * ==============================
         * Protection Type Section
         * ==============================
         */
        $this->start_controls_section(
            'section_protection',
            [
                'label' => __( 'Protection Type', 'easy-elements-pro' ),
            ]
        );

        $this->add_control(
            'protection_type',
            [
                'label' => __( 'Protection Type', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'user'      => __( 'User Role Based', 'easy-elements-pro' ),
                    'password'  => __( 'Password Protected', 'easy-elements-pro' ),
                ],
                'default' => 'user',
            ]
        );

        $this->add_control(
            'visible_roles',
            [
                'label' => __( 'Visible To Roles', 'easy-elements-pro' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_user_roles(),
                'default' => [ 'administrator' ],
                'condition' => [ 'protection_type' => 'user' ],
            ]
        );

        $this->add_control(
            'password_key',
            [
                'label' => __( 'Protection Password', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'input_type' => 'password',
                'default' => '',
                'condition' => [ 'protection_type' => 'password' ],
            ]
        );

        $this->end_controls_section();

        /**
         * ==============================
         * Cookie Settings Section 🍪
         * ==============================
         */
        $this->start_controls_section(
            'section_cookie_settings',
            [
                'label' => __( 'Cookie Settings', 'easy-elements-pro' ),
                'condition' => [ 'protection_type' => 'password' ],
            ]
        );

        $this->add_control(
            'enable_cookie',
            [
                'label' => __( 'Enable Cookie', 'easy-elements-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'easy-elements-pro' ),
                'label_off' => __( 'No', 'easy-elements-pro' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'cookie_duration',
            [
                'label' => __( 'Cookie Duration (minutes)', 'easy-elements-pro' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1440,
                'step' => 1,
                'default' => 60,
                'condition' => [ 'enable_cookie' => 'yes' ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'form_label',
            [
                'label' => __( 'Form Label', 'easy-elements-pro' ),
                'condition' => [ 'protection_type' => 'password' ],
            ]
        );

        $this->add_control(
            'form_button_text',
            [
                'label' => __( 'Submit Button Text', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Submit',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'input_placeholder_text',
            [
                'label' => __( 'Input Placeholder Text', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Enter Password',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        /**
         * ==============================
         * Warning Message Section
         * ==============================
         */
        $this->start_controls_section(
            'section_warning',
            [
                'label' => __( 'Warning Message', 'easy-elements-pro' ),
            ]
        );

        $this->add_control(
            'easy_warning_message',
            [
                'label' => __( 'Unauthorized Message', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'You do not permission to view this content.', 'easy-elements-pro' ),
            ]
        );

        $this->add_control(
            'wrong_password_message',
            [
                'label' => __( 'Wrong Password Message', 'easy-elements-pro' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => __( 'Incorrect password. Please try again.', 'easy-elements-pro' ),
                'condition' => [ 'protection_type' => 'password' ],
            ]
        );

        $this->end_controls_section();
    }

    protected function get_user_roles() {

        global $wp_roles;
        $roles = [];
        foreach ( $wp_roles->roles as $key => $data ) {
            $roles[$key] = $data['name'];
        }
        return $roles;
    }

    protected function get_elementor_templates() {
        $options = [];
        $templates = get_posts([
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ]);
        foreach ( $templates as $template ) {
            $options[$template->ID] = $template->post_title;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $protection_type = $settings['protection_type'];
        $content_type = $settings['content_type'];
        $form_button_text = $settings['form_button_text'];
        $input_text = $settings['input_placeholder_text'];

        $has_access = false;

        if ( $protection_type === 'user' ) {
            $user = wp_get_current_user();
            if ( is_user_logged_in() && !empty($settings['visible_roles']) ) {
                foreach ( $settings['visible_roles'] as $role ) {
                    if ( in_array( $role, (array) $user->roles ) ) {
                        $has_access = true;
                        break;
                    }
                }
            }
        } elseif ( $protection_type === 'password' ) {
            $cookie_name = 'easy_protected_access_' . $this->get_id();
            $cookie_enabled = ( isset( $settings['enable_cookie'] ) && $settings['enable_cookie'] === 'yes' );
            $cookie_duration = !empty( $settings['cookie_duration'] ) ? intval( $settings['cookie_duration'] ) : 60;

           
            if ( isset($_POST['easy_protected_password']) ) {
               
                if ( $_POST['easy_protected_password'] === $settings['password_key'] ) {
                    $has_access = true;

                    if ( $cookie_enabled ) {
                        setcookie( $cookie_name, '1', time() + ( $cookie_duration * 60 ), COOKIEPATH, COOKIE_DOMAIN );
                    }

                } 
            }

            elseif ( isset($_COOKIE[$cookie_name]) && $settings['enable_cookie'] === 'yes'  ) {
                $has_access = true;
            }

            if ( ! $has_access ) { ?> 
                <div class="easy-password-protected-form-main-wrapper">
                    <?php if ( isset( $_POST['easy_protected_password'] ) && $_POST['easy_protected_password'] !== $settings['password_key'] ) { ?>
                    <div class="easy-protected-warning"><?php echo esc_html( $settings['wrong_password_message'] ); ?></div>
                    <?php } ?>
                    <div class="easy-password-warning-text">
                        <?php echo esc_html( $settings['easy_warning_message'] ); ?>
                    </div>
                    <div class="easy-password-protected-form">
                        <form method="post" class="easy-content-protected-form">
                            <input type="password" name="easy_protected_password" placeholder="<?php echo esc_attr( $input_text ); ?>" />
                            <button type="submit"><?php echo esc_html( $form_button_text ); ?></button>
                        </form>
                    </div>
                </div>
                <?php 
                return;
            }
        }

        ?>
        <div class="easy-elements-password-protected-content">
            <div class="easy-password-proteced-content-wrap">
        <?php 
        if ( $has_access ) {
            if ( $content_type === 'template' && ! empty( $settings['template_id'] ) ) {
                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['template_id'] );
            } else {
                echo wp_kses_post( $settings['protected_text'] );
            }
        } else { ?>
            <div class="easy-protected-warning">
                <?php echo esc_html( $settings['easy_warning_message'] ); ?>
            </div>
        <?php }
        ?>
            </div>
        </div>
        <?php
    }
}
