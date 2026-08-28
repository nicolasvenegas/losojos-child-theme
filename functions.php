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

/**
 * Shortcode para la animación de Los Ojos
 * Uso: [los_ojos_animacion]
 */
function los_ojos_animacion_shortcode() {
    // 1. Cargar Matter.js desde CDN
    wp_enqueue_script(
        'matter-js',
        'https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.19.0/matter.min.js',
        array(),
        '0.19.0',
        true // Cargar en el footer
    );
    
    // 2. Cargar el CSS de la animación
    wp_enqueue_style(
        'los-ojos-animation-css',
        get_stylesheet_directory_uri() . '/assets/css/los-ojos-animation.css',
        array(),
        '1.0.0'
    );

    // 3. Cargar el JS de la animación (depende de matter-js)
    wp_enqueue_script(
        'los-ojos-animation-js',
        get_stylesheet_directory_uri() . '/assets/js/los-ojos-animation.js',
        array('matter-js'),
        '1.0.0',
        true // Cargar en el footer
    );

    // 4. Generar el HTML de la animación
    ob_start();
    ?>
    <div class="los-ojos-animation-wrapper">
        <a href="<?php echo esc_url(home_url('/servicios/')); ?>" class="los-ojos-logo-link" aria-label="Servicios">
            <img
                id="los-ojos-logo"
                src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/losojos_logo.svg'); ?>"
                alt="Los Ojos"
                width="300"
                height="300"
                fetchpriority="high"
                loading="eager"
                decoding="async"
                data-no-lazy="1"
            />
        </a>
        
        <main id="primary" class="site-main los-ojos-hero" role="main">
            <div class="screen-reader-text">
                <h1>Los Ojos</h1>
                <h2>Diseñamos y desarrollamos objetos y experiencias que combinan tecnología, espacio, audiovisual e interacción.</h2>
                <p>Los Ojos nace de una convicción sencilla: la tecnología como herramienta, lenguaje, medio creativo y forma de pensamiento capaz de configurar nuevas maneras de relacionarnos con el mundo. Un campo de convergencia entre código, imagen, espacio, sonido, movimiento, humanos y más que humanos.</p>
            </div>
        </main>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('los_ojos_animacion', 'los_ojos_animacion_shortcode');