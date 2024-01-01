#!/usr/bin/env bash

# see https://stackoverflow.com/questions/66644233/how-to-propagate-colors-from-bash-script-to-github-action?noredirect=1#comment117811853_66644233
export TERM=xterm-color

# show errors
set -e
set -u

# functions
note()
{
    MESSAGE=$1;

    printf "\n";
    echo "[NOTE] $MESSAGE";
    printf "\n";
}


# configure here
BUILD_DIRECTORY=$1
RESULT_DIRECTORY=$2

# ---------------------------

note "Starts"

# 2. scope it
note "Downloading php-scoper.phar"
wget https://github.com/humbug/php-scoper/releases/download/0.17.7/php-scoper.phar -N --no-verbose

# avoid phpstan/phpstan dependency duplicate
note "Remove PHPStan to avoid duplicating it"
php "$BUILD_DIRECTORY/bin/add-phpstan-self-replace.php"
composer remove phpstan/phpstan -W --update-no-dev --working-dir "$BUILD_DIRECTORY"

# Work around possible PHP memory limits
note "Running php-scoper on /bin, /config, /src, /rules and /vendor"
php -d memory_limit=-1 php-scoper.phar add-prefix bin config src rules vendor composer.json --output-dir "../$RESULT_DIRECTORY" --config scoper.php --force --ansi --working-dir "$BUILD_DIRECTORY";

note "Dumping prefixed Composer Autoload"
composer dump-autoload --working-dir "$RESULT_DIRECTORY" --ansi --classmap-authoritative --no-dev

rm -rf "$BUILD_DIRECTORY"


# copy metafiles needed for release
note "Copy metafiles like composer.json, .github etc to repository"
rm -f "$RESULT_DIRECTORY/composer.json"

# make bin/rector runnable without "php"
chmod 777 "$RESULT_DIRECTORY/bin/rector"
chmod 777 "$RESULT_DIRECTORY/bin/rector.php"

note "Finished"
