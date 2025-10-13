<?php
use Elementor\Base_Data_Control;
use Elementor\Plugin;

if (!defined('ABSPATH')) exit; // Exit if accessed directly
class Dynamic_Post_Select extends Base_Data_Control {

    const Easy_type = 'easy-dynamic-select';

    public function get_type() {
        return self::Easy_type;
    }

    protected function get_default_settings() {
        return [
            'options' => [],
            'multiple' => false,
            'query_args' => [],
        ];
    }

    public function content_template() {
        $control_id = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
                <label for="<?php echo esc_attr($control_id); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            <div class="elementor-control-input-wrapper">
                <# var multipleAttr = ( data.multiple ) ? 'multiple' : ''; #>
                <select id="<?php echo esc_attr($control_id); ?>" class="elementor-select2" {{ multipleAttr }} data-setting="{{ data.name }}">
                    <# _.each( data.options, function(title, val) {
                        var currentValue = data.controlValue;
                        var selectedAttr = '';
                        if ( typeof currentValue === 'string' ) {
                            selectedAttr = ( val === currentValue ) ? 'selected' : '';
                        } else if ( currentValue !== null ) {
                            var values = _.values(currentValue);
                            selectedAttr = ( values.indexOf(val) !== -1 ) ? 'selected' : '';
                        }
                    #>
                        <option {{ selectedAttr }} value="{{ val }}">{{{ title }}}</option>
                    <# }); #>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
            <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    public function enqueue() {
        wp_enqueue_script(
            'easy-dynamic-post-select-js',
            EASYELEMENTS_PRO_URL . 'inc/extensions/dynamic-content/assets/js/easy-dynamic-select.js',
            ['jquery'],
            '1.0.0',
            true
        );

        wp_localize_script(
            'easy-dynamic-post-select-js',
            'dynamicPostSelect',
            [
                'nonce' => wp_create_nonce('easy_dynamic_post_select'),
                'action' => 'dynamic_post_select_ajax',
                'ajax_url' => admin_url('admin-ajax.php'),
            ]
        );
    }
}

add_action('elementor/controls/register', function() {
    
    $controls_manager = \Elementor\Plugin::$instance->controls_manager;
    $controls_manager->register(new Dynamic_Post_Select());
   
});