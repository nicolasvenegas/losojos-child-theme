<?php

function losojos_project_links_shortcode() {

    if ( ! is_singular( 'proyecto' ) ) {
        return '';
    }

    $links = [
        'project_repository' => [
            'label' => 'Repositorio',
            'text'  => 'GitHub ↗',
        ],
        'project_url' => [
            'label' => 'Sitio web',
            'text'  => 'Visitar ↗',
        ],
    ];

    $output = '';

    foreach ( $links as $field => $config ) {

        $url = get_field( $field );

        if ( empty( $url ) ) {
            continue;
        }

        $output .= '<div class="project-meta-item">';
        $output .= '<strong>' . esc_html( $config['label'] ) . ':</strong> ';
        $output .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">';
        $output .= esc_html( $config['text'] );
        $output .= '</a>';
        $output .= '</div>';
    }

    if ( empty( $output ) ) {
        return '';
    }

    return '<div class="project-meta">' . $output . '</div>';
}

add_shortcode( 'project_links', 'losojos_project_links_shortcode' );