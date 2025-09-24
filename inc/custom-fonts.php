<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

function easyel_pro_custom_fonts_page() {
    $fonts = get_option( 'easyel_uploaded_fonts', [] );

    // Handle removal
    if ( isset( $_GET['remove_font'] ) && check_admin_referer( 'easyel_remove_font' ) ) {
        easyel_remove_font( sanitize_text_field( $_GET['remove_font'] ) );
        $fonts = get_option( 'easyel_uploaded_fonts', [] );
        echo '<div class="updated"><p>' . esc_html__( 'Font removed successfully.', 'easy-elements' ) . '</p></div>';
    }

    // Handle upload
    if ( isset( $_POST['easyel_font_nonce'] ) && wp_verify_nonce( $_POST['easyel_font_nonce'], 'easyel_upload_font' ) && ! empty( $_FILES['custom_font']['name'] ) ) {
        $uploaded = easyel_save_custom_font( $_FILES['custom_font'] );
        $fonts    = get_option( 'easyel_uploaded_fonts', [] );
        if ( $uploaded ) {
            echo '<div class="updated"><p>' . esc_html__( 'Font uploaded successfully.', 'easy-elements' ) . '</p></div>';
        } else {
            echo '<div class="error"><p>' . esc_html__( 'Font upload failed. Check file type or size.', 'easy-elements' ) . '</p></div>';
        }
    }

    // Upload form
    echo '<div class="wrap"><h1>' . esc_html__( 'Upload Custom Fonts', 'easy-elements' ) . '</h1>';
    echo wp_kses(
        '<p>' .
        esc_html__( 'Upload fonts here and they will be automatically added to the Elementor typography list, so you can select them directly in your designs.', 'easy-elements' ) .
        '</p>',
        [
            'p' => []
        ]
    );
    echo '<form method="post" enctype="multipart/form-data" id="easyel-custom-fonts-upload-form">';
    wp_nonce_field( 'easyel_upload_font', 'easyel_font_nonce' );
    echo '<input type="file" name="custom_font" accept=".woff,.woff2,.ttf,.otf" required>';
    submit_button( __( 'Upload Font', 'easy-elements' ), 'primary', '', false );
    echo '</form>';

    // Uploaded fonts grid
    if ( $fonts ) {
        echo '<h2>' . esc_html__( 'Uploaded Fonts', 'easy-elements' ) . '</h2>';
        echo '<div class="easyel-fonts-grid">';
        foreach ( $fonts as $name => $data ) {
            $remove_url = wp_nonce_url( admin_url( 'admin.php?page=easyel-custom-fonts&remove_font=' . urlencode( $name ) ), 'easyel_remove_font' );
            echo '<div class="easyel-font-box">';
            echo '<p><strong>' . esc_html( $name ) . '</strong></p>';
            echo '<p style="font-family:' . esc_attr( $name ) . '; font-size: 20px;">AaBbCc</p>';
            echo '<a href="' . esc_url( $remove_url ) . '" class="button button-secondary" onclick="return confirm(\'Are you sure?\')">Remove</a>';
            echo '</div>';
        }
        echo '</div>';
    }
    echo '</div>';
}

/**
 * === PRO ONLY HOOKS ===
 */
if ( defined( 'EASY_ELEMENTS_PRO_ACTIVE' ) && EASY_ELEMENTS_PRO_ACTIVE ) {

    // Allow custom font uploads
    if ( ! function_exists( 'Easyel_allow_font_uploads' ) ) {
        function Easyel_allow_font_uploads( $mimes ) {
            $mimes['woff']  = 'font/woff';
            $mimes['woff2'] = 'font/woff2';
            $mimes['ttf']   = 'font/ttf';
            $mimes['otf']   = 'font/otf';
            return $mimes;
        }
        add_filter( 'upload_mimes', 'Easyel_allow_font_uploads' );
    }
    

    // Save uploaded font
    function easyel_save_custom_font( $file ) {
        $upload_dir = wp_upload_dir();
        $font_dir   = $upload_dir['basedir'] . '/custom-fonts/';
        $font_url   = $upload_dir['baseurl'] . '/custom-fonts/';

        if ( ! file_exists( $font_dir ) ) wp_mkdir_p( $font_dir );

        $ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        if ( ! in_array( $ext, [ 'woff','woff2','ttf','otf' ] ) ) return false;
        if ( $file['size'] > 10485760 ) return false; // 10MB max

        $safe_name = sanitize_title( pathinfo( $file['name'], PATHINFO_FILENAME ) ) . '-' . time();
        $new_file_path = $font_dir . $safe_name . '.' . $ext;
        $new_file_url  = $font_url . $safe_name . '.' . $ext;

        if ( move_uploaded_file( $file['tmp_name'], $new_file_path ) ) {
            $fonts = get_option( 'easyel_uploaded_fonts', [] );
            $fonts[ pathinfo( $file['name'], PATHINFO_FILENAME ) ] = [
                'url' => $new_file_url,
                'ext' => $ext
            ];
            update_option( 'easyel_uploaded_fonts', $fonts );
            return true;
        }
        return false;
    }

    // Remove uploaded font
    function easyel_remove_font( $font_name ) {
        $fonts = get_option( 'easyel_uploaded_fonts', [] );
        if ( isset( $fonts[ $font_name ] ) ) {
            $file_path = str_replace( wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $fonts[ $font_name ]['url'] );
            if ( file_exists( $file_path ) ) unlink( $file_path );
            unset( $fonts[ $font_name ] );
            update_option( 'easyel_uploaded_fonts', $fonts );
        }
    }

    // Elementor fonts
    add_filter( 'elementor/fonts/groups', function( $groups ) {
        $groups['uploaded_fonts'] = __( 'Uploaded Fonts', 'easy-elements' );
        return $groups;
    });
    add_filter( 'elementor/fonts/additional_fonts', function( $fonts ) {
        $fonts_opt = get_option( 'easyel_uploaded_fonts', [] );
        foreach ( $fonts_opt as $name => $data ) {
            $fonts[ $name ] = 'uploaded_fonts';
        }
        return $fonts;
    });

    // Load fonts frontend
    add_action( 'wp_head', function() {
        $fonts = get_option( 'easyel_uploaded_fonts', [] );
        if ( ! $fonts ) return;

        $css = '';
        foreach ( $fonts as $name => $data ) {
            $format = match( $data['ext'] ) {
                'woff2' => 'woff2',
                'woff'  => 'woff',
                'ttf'   => 'truetype',
                default => 'opentype'
            };
            $css .= "@font-face{font-family:'{$name}';src:url('{$data['url']}') format('{$format}');font-weight:normal;font-style:normal;font-display:swap;}\n";
        }
        echo "<style id='easyel-uploaded-fonts'>{$css}</style>";
    }, 99);
}
