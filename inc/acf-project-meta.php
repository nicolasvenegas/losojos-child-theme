<?php
function losojos_project_meta_shortcode() {

    if (!is_singular('proyecto')) {
        return '';
    }

    ob_start();

    ?>

    <div class="project-meta">

        <?php if (get_field('project_year')) : ?>
            <div class="project-meta-item">
                <strong>Año:</strong>
                <?php echo esc_html(get_field('project_year')); ?>
            </div>
        <?php endif; ?>


        <?php if (get_field('project_status')) : ?>
            <div class="project-meta-item">
                <strong>Estado:</strong>
                <?php echo esc_html(get_field('project_status')); ?>
            </div>
        <?php endif; ?>


        <?php if (get_field('project_license')) : ?>
            <div class="project-meta-item">
                <strong>Licencia:</strong>
                <?php echo esc_html(get_field('project_license')); ?>
            </div>
        <?php endif; ?>


        <?php if (get_field('project_tools')) : ?>
            <div class="project-meta-item">
                <strong>Herramientas:</strong>
                <?php echo esc_html(get_field('project_tools')); ?>
            </div>
        <?php endif; ?>

    </div>

    <?php

    return ob_get_clean();

}

add_shortcode(
    'project_meta',
    'losojos_project_meta_shortcode'
);