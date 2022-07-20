<?php
function a(){
return array(
'dependencies' => array( 'lodash', 'moment', 'react', 'react-dom', 'wc-components', 'wc-csv', 'wc-currency', 'wc-customer-effort-score', 'wc-date', 'wc-experimental', 'wc-explat', 'wc-navigation', 'wc-notices', 'wc-number', 'wc-settings', 'wc-store-data', 'wc-tracks', 'wp-a11y', 'wp-api-fetch', 'wp-components', 'wp-compose', 'wp-data', 'wp-data-controls', 'wp-date', 'wp-deprecated', 'wp-dom', 'wp-element', 'wp-hooks', 'wp-html-entities', 'wp-i18n', 'wp-keycodes', 'wp-notices', 'wp-plugins', 'wp-primitives', 'wp-url', 'wp-warning' ),
'version'      => '70f93d4745dfa01f57bfb086ea19da12',
);
}

?>
-----
<?php

namespace Rector\Tests\Php54\Rector\Array_\LongArrayToShortArrayRector\Fixture;

// what is expected code?
// should remain the same? delete part bellow ----- (included)

// Based on https://getrector.org/demo/183be8ca-cb30-45aa-aa15-40d711c760f5
// the array constructor should use long constructor if LongArrayToShortArrayRector
// included in the skip

<?php
function a(){
return array(
'dependencies' => array( 'lodash', 'moment', 'react', 'react-dom', 'wc-components', 'wc-csv', 'wc-currency', 'wc-customer-effort-score', 'wc-date', 'wc-experimental', 'wc-explat', 'wc-navigation', 'wc-notices', 'wc-number', 'wc-settings', 'wc-store-data', 'wc-tracks', 'wp-a11y', 'wp-api-fetch', 'wp-components', 'wp-compose', 'wp-data', 'wp-data-controls', 'wp-date', 'wp-deprecated', 'wp-dom', 'wp-element', 'wp-hooks', 'wp-html-entities', 'wp-i18n', 'wp-keycodes', 'wp-notices', 'wp-plugins', 'wp-primitives', 'wp-url', 'wp-warning' ),
'version'      => '70f93d4745dfa01f57bfb086ea19da12',
);
}
?>
