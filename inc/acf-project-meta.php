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


        <?php if (get_field('project_start_year')) : ?>
            <div class="project-meta-item">
                <strong>Inicio:</strong>
                <?php echo esc_html(get_field('project_start_year')); ?>
            </div>
        <?php endif; ?>


        <?php if (get_field('project_end_year')) : ?>
            <div class="project-meta-item">
                <strong>Término:</strong>
                <?php echo esc_html(get_field('project_end_year')); ?>
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
                <strong>Herramientas / Medios:</strong>
                <?php echo esc_html(get_field('project_tools')); ?>
            </div>
        <?php endif; ?>


        <?php if (get_field('project_client')) : ?>
            <div class="project-meta-item">
                <strong>Cliente / Institución:</strong>
                <?php echo esc_html(get_field('project_client')); ?>
            </div>
        <?php endif; ?>


        <?php if (get_field('project_location')) : ?>
            <div class="project-meta-item">
                <strong>Ubicación:</strong>
                <?php echo esc_html(get_field('project_location')); ?>
            </div>
        <?php endif; ?>


        <?php if (get_field('project_collaborators')) : ?>
            <div class="project-meta-item">
                <strong>Colaboradores:</strong>
                <?php echo esc_html(get_field('project_collaborators')); ?>
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