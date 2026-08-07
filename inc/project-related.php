<?php

function losojos_project_related_shortcode() {

    if (!is_singular('proyecto')) {
        return '';
    }

    $projects = get_field('related_projects');

    if (empty($projects)) {
        return '';
    }

    ob_start();
    ?>

    <section class="project-related">

        <h2>Proyectos relacionados</h2>

        <div class="project-related-grid">

            <?php foreach ($projects as $project) :

                $title = get_the_title($project);
                $link  = get_permalink($project);
                $year  = get_field('project_year', $project);

            ?>

                <article class="project-related-card">

                    <a href="<?php echo esc_url($link); ?>">

                        <?php
                        if (has_post_thumbnail($project)) {
                            echo get_the_post_thumbnail(
                                $project,
                                'medium'
                            );
                        }
                        ?>

                        <h3><?php echo esc_html($title); ?></h3>

                        <?php if ($year) : ?>

                            <p><?php echo esc_html($year); ?></p>

                        <?php endif; ?>

                    </a>

                </article>

            <?php endforeach; ?>

        </div>

    </section>

    <?php

    return ob_get_clean();

}

add_shortcode(
    'project_related',
    'losojos_project_related_shortcode'
);