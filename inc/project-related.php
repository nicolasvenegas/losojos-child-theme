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

                    <?php if (has_post_thumbnail($project)) : ?>
                        <a
                            class="project-related-card-link"
                            href="<?php echo esc_url($link); ?>"
                        ><?php
                            echo get_the_post_thumbnail(
                                $project,
                                'medium'
                            );
                        ?></a>
                    <?php endif; ?>

                    <h3>
                        <a
                            class="project-related-card-title"
                            href="<?php echo esc_url($link); ?>"
                        ><?php echo esc_html($title); ?></a>
                    </h3>

                    <?php if ($year) : ?>
                        <p class="project-related-card-year"><?php echo esc_html($year); ?></p>
                    <?php endif; ?>

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