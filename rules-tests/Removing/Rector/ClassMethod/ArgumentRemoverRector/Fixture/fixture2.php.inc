<?php

use Rector\Tests\Removing\Rector\ClassMethod\ArgumentRemoverRector\Source\Yaml;

function argumentRemover()
{
    $yaml = [];

    Yaml::parse($yaml, Symfony\Component\Yaml\Yaml::PARSE_KEYS_AS_STRINGS);

    Yaml::parse($yaml, Symfony\Component\Yaml\Yaml::PARSE_KEYS_AS_STRINGS);

    Yaml::parse($yaml, Yaml::KEPT_CONSTANT);

    Yaml::parse($yaml, Symfony\Component\Yaml\Yaml::PARSE_KEYS_AS_STRINGS);

    Yaml::parse($yaml, 55);
    Yaml::parse($yaml, 56);
    Yaml::parse($yaml, 'hey');
    Yaml::parse($yaml, 'hi');
    Yaml::parse($yaml, 5.5);
}

?>
-----
<?php

use Rector\Tests\Removing\Rector\ClassMethod\ArgumentRemoverRector\Source\Yaml;

function argumentRemover()
{
    $yaml = [];

    Yaml::parse($yaml);

    Yaml::parse($yaml);

    Yaml::parse($yaml, Yaml::KEPT_CONSTANT);

    Yaml::parse($yaml);

    Yaml::parse($yaml);
    Yaml::parse($yaml, 56);
    Yaml::parse($yaml);
    Yaml::parse($yaml, 'hi');
    Yaml::parse($yaml);
}

?>
