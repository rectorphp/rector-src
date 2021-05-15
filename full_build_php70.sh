#!/usr/bin/env bash

# see https://stackoverflow.com/questions/66644233/how-to-propagate-colors-from-bash-script-to-github-action?noredirect=1#comment117811853_66644233
export TERM=xterm-color

# show errors
set -e

# script fails if trying to access to an undefined variable
set -u




composer install --no-dev --ansi

wget https://github.com/box-project/box/releases/download/3.12.2/box.phar -N --no-verbose
php box.phar extract vendor/phpstan/phpstan/phpstan.phar vendor/phpstan/phpstan-extracted
rm -rf vendor/bin/phpstan vendor/phpstan/phpstan/bootstrap.php
echo "<?php " > vendor/phpstan/phpstan/bootstrap.php
rm -rf vendor/phpstan/phpstan/phpstan.phar

rsync --exclude rector-build-php70 -av * rector-build-php70 --quiet
rm -rf rector-build-php70/packages-tests rector-build-php70/rules-tests rector-build-php70/tests

bin/rector list --debug

sh build/downgrade-rector-php70.sh rector-build-php70
sh build/build-rector-scoped-php70.sh rector-build-php70 rector-prefixed-downgraded-php70

rm -rf vendor && mkdir -p vendor
cp -R rector-prefixed-downgraded-php70/vendor/* vendor/
rm -rf php-parallel-lint

/opt/homebrew/opt/php@7.0/bin/php /usr/local/bin/composer create-project php-parallel-lint/php-parallel-lint php-parallel-lint --ansi
/opt/homebrew/opt/php@7.0/bin/php php-parallel-lint/parallel-lint rector-prefixed-downgraded-php70 --exclude rector-prefixed-downgraded-php70/stubs --exclude rector-prefixed-downgraded-php70/vendor/symfony/error-handler/Resources --exclude rector-prefixed-downgraded-php70/vendor/symfony/http-kernel/Resources --exclude rector-prefixed-downgraded-php70/vendor/rector/rector-nette/tests --exclude rector-prefixed-downgraded-php70/vendor/symfony/polyfill-mbstring/bootstrap80.php --exclude rector-prefixed-downgraded-php70/vendor/tracy/tracy/examples --exclude rector-prefixed-downgraded-php70/vendor/rector/rector-installer/tests --exclude rector-prefixed-downgraded-php70/vendor/symplify/smart-file-system/tests --exclude rector-prefixed-downgraded-php70/vendor/symfony/http-foundation/Session --exclude rector-prefixed-downgraded-php70/vendor/symfony/var-dumper --exclude rector-prefixed-downgraded-php70/vendor/nette/caching --exclude rector-prefixed-downgraded-php70/vendor/rector/rector-nette/src/Rector/LNumber --exclude rector-prefixed-downgraded-php70/vendor/symfony/http-foundation/Test --exclude rector-prefixed-downgraded-php70/vendor/symplify/simple-php-doc-parser/tests --exclude rector-prefixed-downgraded-php70/vendor/tracy/tracy/src/Tracy/Bar/panels/info.panel.phtml --exclude rector-prefixed-downgraded-php70/vendor/symfony/string/Slugger/AsciiSlugger.php

