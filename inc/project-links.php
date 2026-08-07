<?php

function losojos_project_links_shortcode() {

    if (!is_singular('proyecto')) {
        return '';
    }

    ob_start();
    ?>

    <div class="project-meta">

        <?php

        $links = [
            'project_repository' => [
                'label' => 'Repositorio',
                'text'  => 'GitHub ↗'
            ],
            'project_url' => [
                'label' => 'Sitio web',
                'text'  => 'Visitar ↗'
            ]
        ];

        foreach ($links as $field => $config) :

            $url = get_field($field);

            if (empty($url)) {
                continue;
            }

        ?>

            <div class="project-meta-item">
                <strong><?php echo esc_html($config['label']); ?>:</strong>

                <a href="<?php echo esc_url($url); ?>"
                   target="_blank"
                   rel="noopener noreferrer">

                    <?php echo esc_html($config['text']); ?>

                </a>

            </div>

        <?php endforeach; ?>

    </div>

    <?php

    return ob_get_clean();

}

add_shortcode(
    'project_links',
    'losojos_project_links_shortcode'
);