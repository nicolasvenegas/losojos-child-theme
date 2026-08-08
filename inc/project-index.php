<?php

function losojos_project_index_shortcode()
{

    $projects = new WP_Query([
        'post_type'      => 'proyecto',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    if (!$projects->have_posts()) {
        return '';
    }

    ob_start();

    ?>

    <div class="projects-index">

        <?php while ($projects->have_posts()) : $projects->the_post(); ?>

            <article class="project-index-card">

                <a
                    class="project-index-card-link"
                    href="<?php the_permalink(); ?>"
                >

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
                        $types = get_the_terms(
                            get_the_ID(),
                            'tipo-de-proyecto'
                        );
                        ?>

                        <?php if ($types && !is_wp_error($types)) : ?>

                            <div class="project-index-type">

                                <?php foreach ($types as $index => $term) : ?>

                                    <?php if ($index > 0) : ?>
                                        <span> · </span>
                                    <?php endif; ?>

                                    <span>
                                        <?php echo esc_html($term->name); ?>
                                    </span>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>


                        <?php
                        $disciplines = get_the_terms(
                            get_the_ID(),
                            'disciplina'
                        );
                        ?>

                        <?php if ($disciplines && !is_wp_error($disciplines)) : ?>

                            <div class="project-index-disciplines">

                                <?php foreach ($disciplines as $index => $term) : ?>

                                    <?php if ($index > 0) : ?>
                                        <span> · </span>
                                    <?php endif; ?>

                                    <span>
                                        <?php echo esc_html($term->name); ?>
                                    </span>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                </a>

            </article>

        <?php endwhile; ?>

    </div>

    <?php

    wp_reset_postdata();

    return ob_get_clean();
}


add_shortcode(
    'projects_index',
    'losojos_project_index_shortcode'
);