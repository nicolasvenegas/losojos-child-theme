<?php


function losojos_project_taxonomies_shortcode() {


    if (!is_singular('proyecto')) {
        return '';
    }


    /*
     * ==================================================
     * URL DEL LABORATORIO
     * ==================================================
     */

    $laboratorio_page = get_page_by_path('laboratorio');

    $laboratorio_url = $laboratorio_page
        ? get_permalink($laboratorio_page)
        : home_url('/');


    /*
     * ==================================================
     * TAXONOMÍAS
     * ==================================================
     */

    $taxonomies = [
        'tipo-de-proyecto' => 'Tipo de proyecto',
        'disciplina'       => 'Disciplinas',
        'tema'             => 'Temas',
    ];


    ob_start();

    ?>


    <div class="project-meta">


        <?php foreach ($taxonomies as $taxonomy => $label) :


            $terms = get_the_terms(
                get_the_ID(),
                $taxonomy
            );


            if (
                empty($terms) ||
                is_wp_error($terms)
            ) {
                continue;
            }


            $links = [];


            foreach ($terms as $term) {


                /*
                 * --------------------------------------------------
                 * URL AL ÍNDICE DEL LABORATORIO
                 * --------------------------------------------------
                 */

                $filter_url = add_query_arg(
                    $taxonomy,
                    $term->slug,
                    $laboratorio_url
                );


                $links[] = sprintf(
                    '<a href="%s" class="project-filter-link" data-filter="%s" data-value="%s">%s</a>',
                    esc_url($filter_url),
                    esc_attr($taxonomy),
                    esc_attr($term->slug),
                    esc_html($term->name)
                );


            }


        ?>


            <div class="project-meta-item">


                <strong>
                    <?php echo esc_html($label); ?>:
                </strong>


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