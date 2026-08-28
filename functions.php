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

function los_ojos_hero_shortcode($atts) {
    $atts = shortcode_atts(array(
        'titulo' => 'Los Ojos',
        'descripcion' => 'Diseñamos y desarrollamos objetos y experiencias que combinan tecnología, espacio, audiovisual e interacción.',
    ), $atts);

    wp_enqueue_script(
        'matter-js',
        'https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.19.0/matter.min.js',
        array(),
        '0.19.0',
        true
    );

    wp_enqueue_style(
        'los-ojos-animation-css',
        get_stylesheet_directory_uri() . '/assets/css/los-ojos-animation.css',
        array(),
        '1.0.3' // Versión actualizada
    );

    wp_enqueue_script(
        'los-ojos-animation-js',
        get_stylesheet_directory_uri() . '/assets/js/los-ojos-animation.js',
        array('matter-js'),
        '1.0.3', // Versión actualizada
        true
    );

    ob_start();
    ?>
    <section class="los-ojos-hero-section">
        <canvas id="los-ojos-canvas"></canvas>
        <div class="los-ojos-content">
            <h1><?php echo esc_html($atts['titulo']); ?></h1>
            <p><?php echo esc_html($atts['descripcion']); ?></p>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('los_ojos_hero', 'los_ojos_hero_shortcode');