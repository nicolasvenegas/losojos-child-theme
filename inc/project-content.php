<?php

function losojos_project_content_shortcode() {

    if (!is_singular('proyecto')) {
        return '';
    }

    $sections = [
        'project_context' => 'Contexto / Investigación',
        'project_process' => 'Proceso',
        'project_description' => 'Resultado / Descripción',
    ];

    ob_start();

    foreach ($sections as $field => $title) :

        $content = get_field($field);

        if (empty($content)) {
            continue;
        }

        ?>

        <section class="project-content-section">

            <h2><?php echo esc_html($title); ?></h2>

            <div class="project-content-body">
                <?php echo wp_kses_post($content); ?>
            </div>

        </section>

        <?php

    endforeach;

    return ob_get_clean();
}

add_shortcode(
    'project_content',
    'losojos_project_content_shortcode'
);