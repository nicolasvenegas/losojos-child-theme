<?php

function losojos_project_taxonomies_shortcode() {

    if (!is_singular('proyecto')) {
        return '';
    }

    $taxonomies = [
        'tipo-de-proyecto' => 'Tipo de proyecto',
        'disciplina'        => 'Disciplinas',
        'tema'              => 'Temas',
    ];

    ob_start();
    ?>

    <div class="project-meta">

        <?php foreach ($taxonomies as $taxonomy => $label) :

            $terms = get_the_terms(get_the_ID(), $taxonomy);

            if (empty($terms) || is_wp_error($terms)) {
                continue;
            }

            $links = [];

            foreach ($terms as $term) {
                $links[] = sprintf(
                    '<a href="%s">%s</a>',
                    esc_url(get_term_link($term)),
                    esc_html($term->name)
                );
            }

        ?>

            <div class="project-meta-item">
                <strong><?php echo esc_html($label); ?>:</strong>
                <?php echo implode(' · ', $links); ?>
            </div>

        <?php endforeach; ?>

    </div>

    <?php

    return ob_get_clean();
}

add_shortcode(
    'project_taxonomies',
    'losojos_project_taxonomies_shortcode'
);