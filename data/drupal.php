<?php

return [
    'drupal/core' => [
        'exclude' => [
            'drupal.INSTALL.txt',
            'drupal.README.md',
            'robots.txt',
            'sites.README.txt',
            'modules.README.txt',
            'profiles.README.txt',
            'themes.README.txt',
        ],
        'folders' => [
            'profiles/demo_umami',
            'profiles/nightwatch_testing',
            'profiles/testing_config_import',
            'profiles/testing_config_overrides',
            'profiles/testing_install_profile_all_dependencies',
            'profiles/testing_install_profile_dependencies',
            'profiles/testing_missing_dependencies',
            'profiles/testing_multilingual',
            'profiles/testing_multilingual_with_english',
            'profiles/testing_requirements',
            'profiles/testing_site_config',
            'profiles/testing_themes_blocks',
        ],
    ],
    'drupal/entity_browser' => [
        'folders' => [
            'modules/example',
        ]
    ],
    'drupal/metatag' => [
        'folders' => [
            'migrations',
        ]
    ],
    'drupal/migrate_plus' => [
        'folders' => [
            'migrate_example',
            'migrate_example_advanced',
            'migrate_json_example',
        ]
    ],
    'drupal/paragraphs' => [
        'folders' => [
            'modules/paragraphs_demo',
        ]
    ],
    'drupal/pathauto' => [
        'folders' => [
            'migrations',
        ]
    ],
    'drupal/redirect' => [
        'folders' => [
            'migrations',
        ]
    ],
    'drupal/robotstxt' => [
        'folders' => [
            'migrations',
        ]
    ],
    'drupal/search_api_solr' => [
        'folders' => [
            'jump-start',
        ]
    ],
    'drupal/webform' => [
        'folders' => [
            'modules/webform_demo',
            'modules/webform_example_composite',
            'modules/webform_example_custom_form',
            'modules/webform_example_element',
            'modules/webform_example_element',
            'modules/webform_example_element_properties',
            'modules/webform_example_handler',
            'modules/webform_example_remote_post',
            'modules/webform_example_variant',
            'modules/webform_examples',
            'modules/webform_examples_accessibility',
        ]
    ],
    'drush/drush' => [
        'exclude' => [
            'README.md', // https://github.com/druidfi/composer-slimmer/issues/5
        ]
    ]
];
