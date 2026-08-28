<?php
/**
 * Template Name: Inicio Interactiva
 */
get_header();
?>

<!-- LOGO CON ATRIBUTOS CRÍTICOS PARA LCP/CLS -->
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

<!-- MAIN CON role="main" PARA ARREGLAR ACCESIBILIDAD -->
<main id="primary" class="site-main los-ojos-hero" role="main">
  <div class="screen-reader-text">
    <h1>Los Ojos</h1>
    <h2>Diseñamos y desarrollamos objetos y experiencias que combinan tecnología, espacio, audiovisual e interacción.</h2>
    <p>Los Ojos nace de una convicción sencilla: la tecnología como herramienta, lenguaje, medio creativo y forma de pensamiento capaz de configurar nuevas maneras de relacionarnos con el mundo. Un campo de convergencia entre código, imagen, espacio, sonido, movimiento, humanos y más que humanos.</p>
  </div>
</main>

<?php get_footer(); ?>