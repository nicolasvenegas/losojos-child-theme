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

            <article class="project-index-item">

                <h2>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>

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