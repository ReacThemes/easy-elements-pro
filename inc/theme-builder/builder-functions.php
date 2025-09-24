<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // direct access disallow
}

class EasyEL_Free_Pro_Unlock {

    /**
     * Hold the singleton instance
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        // Register hooks
        add_filter( 'easyel_singulars_data', [ $this, 'easyel_builder_unlock_pro_items' ] );
        add_filter('easyel_archives_data', [ $this, 'easyel_unlock_pro_archives' ] );
    }

    /**
     * Singleton instance
     */
    public static function instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Unlock pro items and remove label [Pro]
     */
    public function easyel_builder_unlock_pro_items( $singulars ) {
        foreach ( $singulars as &$item ) {
            if ( isset( $item['pro'] ) && $item['pro'] === true ) {
                $item['pro'] = false;
                $item['label'] = str_replace( ' [Pro]', '', $item['label'] );
            }
        }
        return $singulars;
    }

    /**
     * Unlock all Pro items in archives
     *
     * @param array $archives
     * @return array
     */
    function easyel_unlock_pro_archives($archives) {
        foreach ($archives as $group => &$items) {
            foreach ($items as &$item) {
                if (isset($item['pro']) && $item['pro'] === true) {
                    $item['pro'] = false;
                    // Optional: [Pro]  remove
                    $item['label'] = str_replace(' [Pro]', '', $item['label']);
                }
            }
        }
        return $archives;
    }

}

// Bootstrap
EasyEL_Free_Pro_Unlock::instance();
