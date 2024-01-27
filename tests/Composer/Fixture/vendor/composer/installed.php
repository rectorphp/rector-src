<?php return array(
    'root' => array(
        'name' => 'fixture/root-package',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => NULL,
        'type' => 'project',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'fixture/fake-package' => array(
            'pretty_version' => '1.0.0',
            'version' => '1.0.0.0',
            'reference' => 'a31d3358a2a5d6ae947df1691d1f321418a5f3d5',
            'type' => 'library',
            'install_path' => __DIR__ . '/../fixture/fake-package',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
