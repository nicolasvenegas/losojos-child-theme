<?php

function losojos_project_meta_shortcode() {

    if (!is_singular('proyecto')) {
        return '';
    }

    $fields = [
        'project_year' => 'Año',
        'project_start_year' => 'Inicio',
        'project_end_year' => 'Término',
        'project_status' => 'Estado',
        'project_license' => 'Licencia',
        'project_tools' => 'Herramientas / Medios',
        'project_client' => 'Cliente / Institución',
        'project_location' => 'Ubicación',
        'project_collaborators' => 'Colaboradores',
    ];

    $output = '<div class="project-meta">';

    foreach ($fields as $field => $label) {

        $value = get_field($field);

        if (empty($value)) {
            continue;
        }

        $output .= '<div class="project-meta-item">';
        $output .= '<strong>' . esc_html($label) . ':</strong>';
        $output .= esc_html($value);
        $output .= '</div>';
    }

    $output .= '</div>';

    return $output;
}

add_shortcode(
    'project_meta',
    'losojos_project_meta_shortcode'
);