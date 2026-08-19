<?php

function losojos_project_related_shortcode() {

    if ( ! is_singular( 'proyecto' ) ) {
        return '';
    }

    $projects = get_field( 'related_projects' );

    if ( empty( $projects ) || ! is_array( $projects ) ) {
        return '';
    }

    $output = '';

    foreach ( $projects as $project ) {

        $project_id = is_object( $project ) ? $project->ID : $project;

        if ( ! $project_id ) {
            continue;
        }

        $title = get_the_title( $project_id );
        $link  = get_permalink( $project_id );
        $year  = get_field( 'project_year', $project_id );

        if ( ! $link || ! $title ) {
            continue;
        }

        $output .= '<article class="project-related-card">';

        if ( has_post_thumbnail( $project_id ) ) {

            $output .= '<a class="project-related-card-link"';
            $output .= ' href="' . esc_url( $link ) . '"';
            $output .= ' aria-label="' . esc_attr( 'Ver proyecto: ' . $title ) . '">';

            $output .= get_the_post_thumbnail(
                $project_id,
                'medium',
                [
                    'alt' => $title,
                ]
            );

            $output .= '</a>';
        }

        $output .= '<h3>';
        $output .= '<a class="project-related-card-title"';
        $output .= ' href="' . esc_url( $link ) . '">';
        $output .= esc_html( $title );
        $output .= '</a>';
        $output .= '</h3>';

        if ( ! empty( $year ) ) {
            $output .= '<p class="project-related-card-year">';
            $output .= esc_html( $year );
            $output .= '</p>';
        }

        $output .= '</article>';
    }

    if ( empty( $output ) ) {
        return '';
    }

    return '<section class="project-related">'
        . '<h2>Proyectos relacionados</h2>'
        . '<div class="project-related-grid">'
        . $output
        . '</div>'
        . '</section>';
}

add_shortcode(
    'project_related',
    'losojos_project_related_shortcode'
);