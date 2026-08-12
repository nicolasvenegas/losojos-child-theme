<?php


/*
 * ==================================================
 * RENDER DE PROYECTOS POR TAXONOMÍA
 * ==================================================
 */


function losojos_render_taxonomy_projects($term_id) {


    /*
     * --------------------------------------------------
     * CONSULTA
     * --------------------------------------------------
     */


    $projects = new WP_Query([

        'post_type'      => 'proyecto',

        'posts_per_page' => -1,

        'orderby'        => 'date',

        'order'          => 'DESC',

        'tax_query'      => [

            [

                'taxonomy' => 'tema',

                'field'    => 'term_id',

                'terms'    => $term_id,

            ],

        ],

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

                        $start_year = get_field(
                            'project_start_year'
                        );

                        $end_year = get_field(
                            'project_end_year'
                        );

                        ?>


                        <?php if ($start_year || $end_year): ?>


                            <div class="project-index-year">


                                <?php if ($start_year): ?>

                                    <?php echo esc_html(
                                        $start_year
                                    ); ?>

                                <?php endif; ?>


                                <?php if ($start_year && $end_year): ?>

                                    –

                                <?php endif; ?>


                                <?php if ($end_year): ?>

                                    <?php echo esc_html(
                                        $end_year
                                    ); ?>

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

                                    <span>
                                        ·
                                    </span>

                                <?php endif; ?>


                                <?php

                                $filter_url = add_query_arg(

                                    'tipo-de-proyecto',

                                    $term->slug,

                                    $laboratorio_url

                                );

                                ?>


                                <a

                                    href="<?php echo esc_url(
                                        $filter_url
                                    ); ?>"

                                    class="project-filter-link"

                                    data-filter="tipo-de-proyecto"

                                    data-value="<?php echo esc_attr(
                                        $term->slug
                                    ); ?>"

                                >

                                    <?php echo esc_html(
                                        $term->name
                                    ); ?>

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

                                as $index => $discipline

                            ): ?>


                                <?php if ($index > 0): ?>

                                    <span>
                                        ·
                                    </span>

                                <?php endif; ?>


                                <?php

                                $filter_url = add_query_arg(

                                    'disciplina',

                                    $discipline->slug,

                                    $laboratorio_url

                                );

                                ?>


                                <a

                                    href="<?php echo esc_url(
                                        $filter_url
                                    ); ?>"

                                    class="project-filter-link"

                                    data-filter="disciplina"

                                    data-value="<?php echo esc_attr(
                                        $discipline->slug
                                    ); ?>"

                                >

                                    <?php echo esc_html(
                                        $discipline->name
                                    ); ?>

                                </a>


                            <?php endforeach; ?>


                        </div>


                    <?php endif; ?>


                </article>


            <?php endwhile; ?>


        <?php else: ?>


            <p class="project-index-empty">

                No se encontraron proyectos asociados a este tema.

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


function losojos_projects_taxonomy_shortcode() {


    if (!is_tax('tema')) {

        return '';

    }


    $term = get_queried_object();


    if (

        !$term ||

        empty($term->term_id)

    ) {

        return '';

    }


    return losojos_render_taxonomy_projects(
        $term->term_id
    );

}


add_shortcode(

    'projects_taxonomy',

    'losojos_projects_taxonomy_shortcode'

);