<?php

function losojos_project_links_shortcode() {

    if (!is_singular('proyecto')) {
        return '';
    }

    ob_start();
    ?>

    <div class="project-links">

        <?php

        $project_title = get_the_title();

        $links = [
            'project_repository' => [
                'label' => 'Repositorio',
                'text'  => 'GitHub ↗',
                'aria'  => 'Repositorio de ' . $project_title . ' — GitHub'
            ],
            'project_url' => [
                'label' => 'Sitio web',
                'text'  => 'Visitar ↗',
                'aria'  => 'Sitio web de ' . $project_title
            ]
        ];

        foreach ($links as $field => $config) :

            $url = get_field($field);

            if (empty($url)) {
                continue;
            }

        ?>

            <div class="project-link-item">

                <strong>
                    <?php echo esc_html($config['label']); ?>:
                </strong>

                <a
                    href="<?php echo esc_url($url); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="<?php echo esc_attr($config['aria']); ?>"
                ><?php echo esc_html($config['text']); ?></a>

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