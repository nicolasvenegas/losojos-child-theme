<?php


/*
 * ==================================================
 * RENDER DEL LISTADO DE PROYECTOS
 * ==================================================
 */

function losojos_render_projects_index(
    $selected_discipline = '',
    $selected_type = '',
    $selected_topic = ''
) {


    /*
     * --------------------------------------------------
     * CONSULTA
     * --------------------------------------------------
     */


    $tax_query = [
        'relation' => 'AND'
    ];


    if ($selected_discipline) {

        $tax_query[] = [
            'taxonomy' => 'disciplina',
            'field'    => 'slug',
            'terms'    => $selected_discipline,
        ];

    }


    if ($selected_type) {

        $tax_query[] = [
            'taxonomy' => 'tipo-de-proyecto',
            'field'    => 'slug',
            'terms'    => $selected_type,
        ];

    }


    if ($selected_topic) {

        $tax_query[] = [
            'taxonomy' => 'tema',
            'field'    => 'slug',
            'terms'    => $selected_topic,
        ];

    }


    $projects = new WP_Query([
        'post_type'      => 'proyecto',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => count($tax_query) > 1
            ? $tax_query
            : [],
    ]);


    /*
     * --------------------------------------------------
     * URL DE LABORATORIO
     * --------------------------------------------------
     */


    $laboratorio_page = get_page_by_path('laboratorio');

    $laboratorio_url = $laboratorio_page
        ? get_permalink($laboratorio_page)
        : home_url('/');


    ob_start();


    ?>


    <div class="projects-index">


        <?php if ($projects->have_posts()): ?>


            <?php while ($projects->have_posts()): $projects->the_post(); ?>


                <article class="project-index-card">


                    <?php
                    /*
                     * IMAGEN
                     */
                    ?>


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


                        <?php
                        /*
                         * TÍTULO
                         */
                        ?>


                        <h2 class="project-index-title">

                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>

                        </h2>



                        <?php
                        /*
                         * AÑO
                         */
                        ?>


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
                                    $laboratorio_url
                                );

                                ?>


                                <a
                                    href="<?php echo esc_url($filter_url); ?>"
                                    class="project-filter-link"
                                    data-filter="tipo-de-proyecto"
                                    data-value="<?php echo esc_attr($term->slug); ?>"
                                >

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
                                    $laboratorio_url
                                );

                                ?>


                                <a
                                    href="<?php echo esc_url($filter_url); ?>"
                                    class="project-filter-link"
                                    data-filter="disciplina"
                                    data-value="<?php echo esc_attr($term->slug); ?>"
                                >

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



/*
 * ==================================================
 * SHORTCODE
 * ==================================================
 */

function losojos_project_index_shortcode()
{


    if (!is_page('laboratorio')) {
        return '';
    }


    ob_start();


    /*
     * --------------------------------------------------
     * VALORES ACTUALES
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
     * URL DE LABORATORIO
     * --------------------------------------------------
     */


    $laboratorio_page = get_page_by_path('laboratorio');

    $laboratorio_url = $laboratorio_page
        ? get_permalink($laboratorio_page)
        : home_url('/');



    /*
     * --------------------------------------------------
     * FILTROS
     * --------------------------------------------------
     */


    ?>


    <form
        class="project-filters"
        method="get"
        action="<?php echo esc_url($laboratorio_url); ?>"
    >


        <div class="project-filter">


            <label for="filter-disciplina">
                Disciplina
            </label>


            <select
                id="filter-disciplina"
                name="disciplina"
            >


                <option value="">
                    Todas
                </option>


                <?php

                $disciplines = get_terms([
                    'taxonomy'   => 'disciplina',
                    'hide_empty' => true,
                ]);


                if (!is_wp_error($disciplines)):

                    foreach ($disciplines as $discipline):

                        ?>


                        <option
                            value="<?php echo esc_attr($discipline->slug); ?>"
                            <?php selected(
                                $selected_discipline,
                                $discipline->slug
                            ); ?>
                        >

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


            <select
                id="filter-tipo"
                name="tipo-de-proyecto"
            >


                <option value="">
                    Todos
                </option>


                <?php

                $types = get_terms([
                    'taxonomy'   => 'tipo-de-proyecto',
                    'hide_empty' => true,
                ]);


                if (!is_wp_error($types)):

                    foreach ($types as $type):

                        ?>


                        <option
                            value="<?php echo esc_attr($type->slug); ?>"
                            <?php selected(
                                $selected_type,
                                $type->slug
                            ); ?>
                        >

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


            <select
                id="filter-tema"
                name="tema"
            >


                <option value="">
                    Todos
                </option>


                <?php

                $topics = get_terms([
                    'taxonomy'   => 'tema',
                    'hide_empty' => true,
                ]);


                if (!is_wp_error($topics)):

                    foreach ($topics as $topic):

                        ?>


                        <option
                            value="<?php echo esc_attr($topic->slug); ?>"
                            <?php selected(
                                $selected_topic,
                                $topic->slug
                            ); ?>
                        >

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


            <a
                class="project-filters-reset"
                href="<?php echo esc_url($laboratorio_url); ?>"
            >

                Limpiar filtros

            </a>


        <?php endif; ?>


    </form>



    <?php


    /*
     * --------------------------------------------------
     * CONTENEDOR ACTUALIZABLE
     * --------------------------------------------------
     */


    ?>


    <div
        id="projects-index-container"
        aria-live="polite"
    >

        <?php

        echo losojos_render_projects_index(
            $selected_discipline,
            $selected_type,
            $selected_topic
        );

        ?>

    </div>


    <?php


    /*
     * --------------------------------------------------
     * JAVASCRIPT
     * --------------------------------------------------
     */


    ?>


    <script>

    document.addEventListener('DOMContentLoaded', function () {

        const filters = document.querySelector('.project-filters');
        const container = document.getElementById('projects-index-container');

        if (!filters || !container) {
            return;
        }


        /*
         * ----------------------------------------------
         * CONSTRUIR URL
         * ----------------------------------------------
         */

        function buildUrl() {

            const url = new URL(
                filters.getAttribute('action'),
                window.location.origin
            );

            const formData = new FormData(filters);

            for (const [key, value] of formData.entries()) {

                if (value) {
                    url.searchParams.set(key, value);
                }

            }

            return url;

        }


        /*
         * ----------------------------------------------
         * ACTUALIZAR FILTROS
         * ----------------------------------------------
         */

        async function updateProjects(pushState = true) {

            const url = buildUrl();

            container.classList.add('is-loading');

            try {

                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar los proyectos.');
                }

                const html = await response.text();

                const parser = new DOMParser();

                const doc = parser.parseFromString(
                    html,
                    'text/html'
                );

                const newContainer =
                    doc.querySelector('#projects-index-container');

                if (!newContainer) {
                    throw new Error('No se encontró el índice de proyectos.');
                }

                container.innerHTML = newContainer.innerHTML;

                if (pushState) {

                    window.history.pushState(
                        {},
                        '',
                        url.toString()
                    );

                }

                updateSelects(url);

            } catch (error) {

                /*
                 * Si algo falla, utilizamos la navegación
                 * normal de WordPress.
                 */

                window.location.href = url.toString();

            } finally {

                container.classList.remove('is-loading');

            }

        }


        /*
         * ----------------------------------------------
         * ACTUALIZAR SELECTS
         * ----------------------------------------------
         */

        function updateSelects(url) {

            const params = url.searchParams;

            const discipline =
                filters.querySelector('[name="disciplina"]');

            const type =
                filters.querySelector('[name="tipo-de-proyecto"]');

            const topic =
                filters.querySelector('[name="tema"]');


            if (discipline) {

                discipline.value =
                    params.get('disciplina') || '';

            }


            if (type) {

                type.value =
                    params.get('tipo-de-proyecto') || '';

            }


            if (topic) {

                topic.value =
                    params.get('tema') || '';

            }

        }


        /*
         * ----------------------------------------------
         * SELECTS
         * ----------------------------------------------
         */

        filters.addEventListener('change', function (event) {

            if (
                event.target.matches('select')
            ) {

                updateProjects(true);

            }

        });


        /*
         * ----------------------------------------------
         * ENLACES DE TAXONOMÍAS
         * ----------------------------------------------
         */

        container.addEventListener('click', function (event) {

            const link =
                event.target.closest('.project-filter-link');

            if (!link) {
                return;
            }

            event.preventDefault();

            const url = new URL(
                link.href,
                window.location.origin
            );

            /*
             * Al seleccionar un filtro desde un proyecto,
             * eliminamos los otros filtros.
             */

            filters.querySelector('[name="disciplina"]').value =
                url.searchParams.get('disciplina') || '';

            filters.querySelector('[name="tipo-de-proyecto"]').value =
                url.searchParams.get('tipo-de-proyecto') || '';

            filters.querySelector('[name="tema"]').value =
                url.searchParams.get('tema') || '';

            updateProjects(true);

        });


        /*
         * ----------------------------------------------
         * LIMPIAR FILTROS
         * ----------------------------------------------
         */

        const resetLink =
            filters.querySelector('.project-filters-reset');

        if (resetLink) {

            resetLink.addEventListener('click', function (event) {

                event.preventDefault();

                filters.querySelector('[name="disciplina"]').value = '';

                filters.querySelector('[name="tipo-de-proyecto"]').value = '';

                filters.querySelector('[name="tema"]').value = '';

                updateProjects(true);

            });

        }


        /*
         * ----------------------------------------------
         * ATRÁS / ADELANTE DEL NAVEGADOR
         * ----------------------------------------------
         */

        window.addEventListener('popstate', function () {

            const url = new URL(
                window.location.href
            );

            updateSelects(url);

            updateProjects(false);

        });

    });

    </script>


    <?php


    return ob_get_clean();

}


add_shortcode(
    'projects_index',
    'losojos_project_index_shortcode'
);