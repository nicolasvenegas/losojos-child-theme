<?php


require_once get_stylesheet_directory() . '/inc/acf-project-meta.php';
require_once get_stylesheet_directory() . '/inc/project-taxonomies.php';
require_once get_stylesheet_directory() . '/inc/project-links.php';
require_once get_stylesheet_directory() . '/inc/project-related.php';
require_once get_stylesheet_directory() . '/inc/project-content.php';
require_once get_stylesheet_directory() . '/inc/project-index.php';



add_action('wp_footer', function () {
    echo '<!-- LOSOJOS CHILD FUNCTIONS OK -->';
});



function losojos_enqueue_child_styles() {

    wp_enqueue_style(
        'losojos-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [],
        filemtime(
            get_stylesheet_directory() . '/style.css'
        )
    );

}

add_action(
    'wp_enqueue_scripts',
    'losojos_enqueue_child_styles'
);



/**
 * Enable SVG uploads.
 */
function losojos_enable_svg_uploads($mimes) {

    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}

add_filter(
    'upload_mimes',
    'losojos_enable_svg_uploads'
);


add_filter( 'get_custom_logo', 'add_dimensions_to_custom_logo' );
function add_dimensions_to_custom_logo( $html ) {
    // Reemplaza 200 y 60 con el ancho y alto real de tu logo SVG/PNG
    return str_replace( '<img ', '<img width="200" height="60" ', $html );
}