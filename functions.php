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


// Añadir dimensiones al logo del sitio
add_filter('wp_get_attachment_image_attributes', 'add_dimensions_to_site_logo', 10, 3);
function add_dimensions_to_site_logo($attr, $attachment, $size) {
    // Solo aplicar al logo del sitio
    if (isset($attr['class']) && strpos($attr['class'], 'custom-logo') !== false) {
        $attr['width'] = '120';  // Ajusta al ancho real
        $attr['height'] = '60';  // Ajusta al alto real
    }
    return $attr;
}

add_action('wp_head', 'preload_critical_fonts', 1);
function preload_critical_fonts() {
    echo '<link rel="preload" href="https://losojos.cl/wp-content/uploads/fonts/terminal-grotesque.ttf" as="font" type="font/ttf" crossorigin>' . "\n";
    // Si usas la fuente woff2 de Chivo como principal, agrega también:
    // echo '<link rel="preload" href="https://losojos.cl/wp-content/uploads/fonts/va9b4kzIxd1KFppkaRKvDRPJVDf_vB_ukmDSFXjQiQ.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}