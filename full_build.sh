#!/usr/bin/env bash

# usage:
#
#   export PHP72_BIN_PATH=/opt/local/bin/php72 && sh ./full_build.sh

# see https://stackoverflow.com/questions/66644233/how-to-propagate-colors-from-bash-script-to-github-action?noredirect=1#comment117811853_66644233
export TERM=xterm-color

# show errors
set -e

# script fails if trying to access to an undefined variable
set -u

# clean up
rm -rf rector-prefixed-downgraded
rm -rf composer.lock
rm -rf vendor
composer clear-cache

composer install --ansi

# ensure remove cache directory
php -r 'shell_exec("rm -rf " . sys_get_temp_dir() . "/rector_cached_files");';

composer install --no-dev --ansi

# early downgrade individual functions
bin/rector process src/functions/node_helper.php -c build/config/config-downgrade.php --ansi

rsync --exclude rector-build -av * rector-build --quiet

rm -rf rector-build/packages-tests rector-build/rules-tests rector-build/tests rector-build/bin/generate-changelog.php rector-build/bin/validate-phpstan-version.php rector-build/vendor/tracy/tracy/examples rector-build/vendor/symfony/console/Tester rector-build/vendor/symfony/console/Event rector-build/vendor/symfony/console/EventListener  rector-build/vendor/tracy/tracy/examples rector-build/vendor/tracy/tracy/src/Bridges rector-build/vendor/tracy/tracy/src/Tracy/Bar rector-build/vendor/tracy/tracy/src/Tracy/Session

php -d memory_limit=-1 bin/rector process rector-build/bin rector-build/config rector-build/src rector-build/packages rector-build/rules rector-build/vendor --config build/config/config-downgrade.php --ansi --no-diffs

sh build/build-rector-scoped.sh rector-build rector-prefixed-downgraded

# verify syntax valid in php 7.2
composer global require php-parallel-lint/php-parallel-lint

if test -z ${PHP72_BIN_PATH+y}; then
    ~/.config/composer/vendor/bin/parallel-lint rector-prefixed-downgraded --exclude rector-prefixed-downgraded/stubs --exclude rector-prefixed-downgraded/vendor/tracy/tracy/examples --exclude rector-prefixed-downgraded/vendor/rector/rector-generator/templates
else
    echo "verify syntax valid in php 7.2 with specify PHP72_BIN_PATH env";
    $PHP72_BIN_PATH ~/.composer/vendor/bin/parallel-lint rector-prefixed-downgraded --exclude rector-prefixed-downgraded/stubs --exclude rector-prefixed-downgraded/vendor/tracy/tracy/examples --exclude rector-prefixed-downgraded/vendor/rector/rector-generator/templates
fi

# Check php 7.2 can be used locally with PHP72_BIN_PATH env
# rector-prefixed-downgraded check
cp -R build/target-repository/. rector-prefixed-downgraded
cp -R templates rector-prefixed-downgraded/
cp CONTRIBUTING.md rector-prefixed-downgraded/
cp preload.php rector-prefixed-downgraded/

# the bin/rector cannot work, as depends on external phpstan/phpstan dependency
# this package cannot be installed here, as it would override scoped autoload
