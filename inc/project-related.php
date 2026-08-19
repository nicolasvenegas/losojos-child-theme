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

        $project_id = is_object( $project ) ? $project->ID : (int) $project;

        if ( ! $project_id ) {
            continue;
        }

        $title = get_the_title( $project_id );
        $link  = get_permalink( $project_id );
        $year  = get_field( 'project_year', $project_id );

        if ( ! $title || ! $link ) {
            continue;
        }

        $output .= '<article class="project-related-card">';

        if ( has_post_thumbnail( $project_id ) ) {

            $output .= sprintf(
                '<a class="project-related-card-link" href="%s" aria-label="%s">',
                esc_url( $link ),
                esc_attr( 'Ver proyecto: ' . $title )
            );

            $output .= get_the_post_thumbnail(
                $project_id,
                'medium',
                [
                    'alt'    => $title,
                    'loading' => 'lazy',
                    'decoding' => 'async',
                ]
            );

            $output .= '</a>';
        }

        $output .= sprintf(
            '<h3><a class="project-related-card-title" href="%s">%s</a></h3>',
            esc_url( $link ),
            esc_html( $title )
        );

        if ( $year ) {
            $output .= sprintf(
                '<p class="project-related-card-year">%s</p>',
                esc_html( $year )
            );
        }

        $output .= '</article>';
    }

    if ( ! $output ) {
        return '';
    }

    return sprintf(
        '<section class="project-related">
            <h2>Proyectos relacionados</h2>
            <div class="project-related-grid">%s</div>
        </section>',
        $output
    );
}

add_shortcode( 'project_related', 'losojos_project_related_shortcode' );