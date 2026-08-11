<?php

function losojos_project_index_shortcode()
{

    if (!is_page('laboratorio')) {
        return '';
    }

    ob_start();


    /*
     * --------------------------------------------------
     * FILTROS
     * --------------------------------------------------
     */

    $selected_discipline = isset($_GET['disciplina'])
        ? sanitize_text_field(wp_unslash($_GET['disciplina']))
        : '';

    $selected_type = isset($_GET['tipo-de-proyecto'])
        ? sanitize_text_field(wp_unslash($_GET['tipo-de-proyecto']))
        : '';

    $selected_topic = isset($_GET['tema'])
        ? sanitize_text_field(wp_unslash($_GET['tema']))
        : '';


    /*
     * --------------------------------------------------
     * CONSULTA DE PROYECTOS
     * --------------------------------------------------
     */

    $tax_query = [
        'relation' => 'AND'
    ];


    if ($selected_discipline) {

        $tax_query[] = [
            'taxonomy' => 'disciplina',
            'field' => 'slug',
            'terms' => $selected_discipline,
        ];

    }


    if ($selected_type) {

        $tax_query[] = [
            'taxonomy' => 'tipo-de-proyecto',
            'field' => 'slug',
            'terms' => $selected_type,
        ];

    }


    if ($selected_topic) {

        $tax_query[] = [
            'taxonomy' => 'tema',
            'field' => 'slug',
            'terms' => $selected_topic,
        ];

    }


    $projects = new WP_Query([
        'post_type' => 'proyecto',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'tax_query' => count($tax_query) > 1
            ? $tax_query
            : [],
    ]);


    /*
     * --------------------------------------------------
     * FILTROS VISUALES
     * --------------------------------------------------
     */

    ?>

    <form class="project-filters" method="get" action="<?php echo esc_url(get_permalink()); ?>">

        <div class="project-filter">

            <label for="filter-disciplina">
                Disciplina
            </label>

            <select id="filter-disciplina" name="disciplina" onchange="this.form.submit()">

                <option value="">
                    Todas
                </option>

                <?php

                $disciplines = get_terms([
                    'taxonomy' => 'disciplina',
                    'hide_empty' => true,
                ]);

                if (!is_wp_error($disciplines)):

                    foreach ($disciplines as $discipline):
                        ?>

                        <option value="<?php echo esc_attr($discipline->slug); ?>" <?php selected(
                               $selected_discipline,
                               $discipline->slug
                           ); ?>>
                            <?php echo esc_html($discipline->name); ?>
                        </option>

                        <?php
                    endforeach;

                endif;
                ?>

            </select>

        </div>


        <div class="project-filter">

            <label for="filter-tipo">
                Tipo de proyecto
            </label>

            <select id="filter-tipo" name="tipo-de-proyecto" onchange="this.form.submit()">

                <option value="">
                    Todos
                </option>

                <?php

                $types = get_terms([
                    'taxonomy' => 'tipo-de-proyecto',
                    'hide_empty' => true,
                ]);

                if (!is_wp_error($types)):

                    foreach ($types as $type):
                        ?>

                        <option value="<?php echo esc_attr($type->slug); ?>" <?php selected(
                               $selected_type,
                               $type->slug
                           ); ?>>
                            <?php echo esc_html($type->name); ?>
                        </option>

                        <?php
                    endforeach;

                endif;
                ?>

            </select>

        </div>


        <div class="project-filter">

            <label for="filter-tema">
                Tema
            </label>

            <select id="filter-tema" name="tema" onchange="this.form.submit()">

                <option value="">
                    Todos
                </option>

                <?php

                $topics = get_terms([
                    'taxonomy' => 'tema',
                    'hide_empty' => true,
                ]);

                if (!is_wp_error($topics)):

                    foreach ($topics as $topic):
                        ?>

                        <option value="<?php echo esc_attr($topic->slug); ?>" <?php selected(
                               $selected_topic,
                               $topic->slug
                           ); ?>>
                            <?php echo esc_html($topic->name); ?>
                        </option>

                        <?php
                    endforeach;

                endif;
                ?>

            </select>

        </div>


        <?php if (
            $selected_discipline ||
            $selected_type ||
            $selected_topic
        ): ?>

            <a class="project-filters-reset" href="<?php echo esc_url(get_permalink()); ?>">
                Limpiar filtros
            </a>

        <?php endif; ?>

    </form>


    <?php


    /*
     * --------------------------------------------------
     * LISTADO DE PROYECTOS
     * --------------------------------------------------
     */

    ?>

    <div class="projects-index">

        <?php if ($projects->have_posts()): ?>

            <?php while ($projects->have_posts()):
                $projects->the_post(); ?>

                <article class="project-index-card">

                    <?php
                    /*
                     * ENLACE AL PROYECTO
                     */
                    ?>

                    <a class="project-index-card-link" href="<?php the_permalink(); ?>">

                        <?php
                        if (has_post_thumbnail()) {
                            echo get_the_post_thumbnail(
                                get_the_ID(),
                                'large',
                                [
                                    'class' => 'project-index-image'
                                ]
                            );
                        }
                        ?>

                        <div class="project-index-content">

                            <h2 class="project-index-title">
                                <?php the_title(); ?>
                            </h2>

                            <?php
                            $start_year = get_field('project_start_year');
                            $end_year = get_field('project_end_year');
                            ?>

                            <?php if ($start_year || $end_year): ?>

                                <div class="project-index-year">

                                    <?php if ($start_year): ?>
                                        <?php echo esc_html($start_year); ?>
                                    <?php endif; ?>

                                    <?php if ($start_year && $end_year): ?>
                                        –
                                    <?php endif; ?>

                                    <?php if ($end_year): ?>
                                        <?php echo esc_html($end_year); ?>
                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    </a>


                    <?php
                    /*
                     * TIPO DE PROYECTO
                     */
                    ?>

                    <?php
                    $project_types = get_the_terms(
                        get_the_ID(),
                        'tipo-de-proyecto'
                    );
                    ?>

                    <?php if (
                        $project_types &&
                        !is_wp_error($project_types)
                    ): ?>

                        <div class="project-index-type">

                            <?php foreach (
                                $project_types
                                as $index => $term
                            ): ?>

                                <?php if ($index > 0): ?>
                                    <span> · </span>
                                <?php endif; ?>

                                <?php
                                $filter_url = add_query_arg(
                                    'tipo-de-proyecto',
                                    $term->slug,
                                    get_permalink()
                                );
                                ?>

                                <a href="<?php echo esc_url($filter_url); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </a>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>


                    <?php
                    /*
                     * DISCIPLINAS
                     */
                    ?>

                    <?php
                    $project_disciplines = get_the_terms(
                        get_the_ID(),
                        'disciplina'
                    );
                    ?>

                    <?php if (
                        $project_disciplines &&
                        !is_wp_error($project_disciplines)
                    ): ?>

                        <div class="project-index-disciplines">

                            <?php foreach (
                                $project_disciplines
                                as $index => $term
                            ): ?>

                                <?php if ($index > 0): ?>
                                    <span> · </span>
                                <?php endif; ?>

                                <?php
                                $filter_url = add_query_arg(
                                    'disciplina',
                                    $term->slug,
                                    get_permalink()
                                );
                                ?>

                                <a href="<?php echo esc_url($filter_url); ?>">
                                    <?php echo esc_html($term->name); ?>
                                </a>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </article>

            <?php endwhile; ?>

        <?php else: ?>

            <p class="project-index-empty">
                No se encontraron proyectos con estos filtros.
            </p>

        <?php endif; ?>

    </div>

    <?php

    wp_reset_postdata();

    return ob_get_clean();


}


add_shortcode(
    'projects_index',
    'losojos_project_index_shortcode'
);