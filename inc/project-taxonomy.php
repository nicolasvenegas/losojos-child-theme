<?php

function losojos_projects_taxonomy_shortcode() {

    if (!is_tax('tema')) {
        return '';
    }

    $term = get_queried_object();

    if (!$term || empty($term->term_id)) {
        return '';
    }

    $projects = new WP_Query([
        'post_type'      => 'proyecto',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => [
            [
                'taxonomy' => 'tema',
                'field'    => 'term_id',
                'terms'    => $term->term_id,
            ],
        ],
    ]);

    ob_start();
    ?>

    <div class="projects-index">

        <?php if ($projects->have_posts()) : ?>

            <?php while ($projects->have_posts()) : $projects->the_post(); ?>

                <article class="project-index-card">

                    <?php if (has_post_thumbnail()) : ?>

                        <a
                            class="project-index-card-link"
                            href="<?php the_permalink(); ?>"
                        >
                            <?php
                            echo get_the_post_thumbnail(
                                get_the_ID(),
                                'large',
                                [
                                    'class' => 'project-index-image'
                                ]
                            );
                            ?>
                        </a>

                    <?php endif; ?>


                    <div class="project-index-content">

                        <h2 class="project-index-title">

                            <a
                                class="project-index-title-link"
                                href="<?php the_permalink(); ?>"
                            >
                                <?php the_title(); ?>
                            </a>

                        </h2>


                        <?php
                        $start_year = get_field('project_start_year');
                        $end_year   = get_field('project_end_year');
                        ?>

                        <?php if ($start_year || $end_year) : ?>

                            <div class="project-index-year">

                                <?php if ($start_year) : ?>
                                    <?php echo esc_html($start_year); ?>
                                <?php endif; ?>

                                <?php if ($start_year && $end_year) : ?>
                                    –
                                <?php endif; ?>

                                <?php if ($end_year) : ?>
                                    <?php echo esc_html($end_year); ?>
                                <?php endif; ?>

                            </div>

                        <?php endif; ?>


                        <?php
                        $project_types = get_the_terms(
                            get_the_ID(),
                            'tipo-de-proyecto'
                        );
                        ?>

                        <?php if (
                            $project_types &&
                            !is_wp_error($project_types)
                        ) : ?>

                            <div class="project-index-type">

                                <?php foreach (
                                    $project_types as $index => $term_type
                                ) : ?>

                                    <?php if ($index > 0) : ?>

                                        <span class="taxonomy-separator">
                                            ·
                                        </span>

                                    <?php endif; ?>

                                    <a
                                        href="<?php echo esc_url(
                                            add_query_arg(
                                                'tipo-de-proyecto',
                                                $term_type->slug,
                                                get_permalink(
                                                    get_page_by_path('laboratorio')
                                                )
                                            )
                                        ); ?>"
                                    >
                                        <?php echo esc_html($term_type->name); ?>
                                    </a>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>


                        <?php
                        $project_disciplines = get_the_terms(
                            get_the_ID(),
                            'disciplina'
                        );
                        ?>

                        <?php if (
                            $project_disciplines &&
                            !is_wp_error($project_disciplines)
                        ) : ?>

                            <div class="project-index-disciplines">

                                <?php foreach (
                                    $project_disciplines as $index => $discipline
                                ) : ?>

                                    <?php if ($index > 0) : ?>

                                        <span class="taxonomy-separator">
                                            ·
                                        </span>

                                    <?php endif; ?>

                                    <a
                                        href="<?php echo esc_url(
                                            add_query_arg(
                                                'disciplina',
                                                $discipline->slug,
                                                get_permalink(
                                                    get_page_by_path('laboratorio')
                                                )
                                            )
                                        ); ?>"
                                    >
                                        <?php echo esc_html(
                                            $discipline->name
                                        ); ?>
                                    </a>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                </article>

            <?php endwhile; ?>

        <?php else : ?>

            <p class="project-index-empty">
                No se encontraron proyectos asociados a este tema.
            </p>

        <?php endif; ?>

    </div>

    <?php

    wp_reset_postdata();

    return ob_get_clean();
}


add_shortcode(
    'projects_taxonomy',
    'losojos_projects_taxonomy_shortcode'
);