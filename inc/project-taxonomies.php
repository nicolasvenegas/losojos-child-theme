<?php

function losojos_project_taxonomies_shortcode()
{

    if (!is_singular('proyecto')) {
        return '';
    }

    ob_start();

    ?>

    <div class="project-taxonomies">


        <?php

        $taxonomies = [
            'tipo-de-proyecto' => 'Tipo de proyecto',
            'disciplina' => 'Disciplinas',
            'tema' => 'Temas'
        ];


        foreach ($taxonomies as $taxonomy => $label):

            $terms = get_the_terms(
                get_the_ID(),
                $taxonomy
            );


            if ($terms && !is_wp_error($terms)):

                ?>

                <div class="project-taxonomy-item">

    <span class="project-taxonomy-label">
        <?php echo esc_html($label); ?>
    </span>

    <span class="project-taxonomy-values">

        <?php foreach ($terms as $index => $term) : ?>

            <?php if ($index > 0) : ?>
                <span class="taxonomy-separator"> · </span>
            <?php endif; ?>

            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                <?php echo esc_html($term->name); ?>
            </a>

        <?php endforeach; ?>

    </span>

</div>


                <?php

            endif;

        endforeach;

        ?>


    </div>

    <?php

    return ob_get_clean();

}


add_shortcode(
    'project_taxonomies',
    'losojos_project_taxonomies_shortcode'
);