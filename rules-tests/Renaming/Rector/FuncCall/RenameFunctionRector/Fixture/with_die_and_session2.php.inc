<?php

if ($_SESSION['order_in_progress'] != 1) {

    $isNewUIEnabled = true;

    if ($isNewUIEnabled) {
        header('Location: /storefront/checkout/success');
        return;
    }
} else {
    die('Sorry, ');
}

function insertorder() {
    if (!empty($orderTaxDetails)) {

        abc($orderTaxQuery);
        if (abc2()) {
            return false;
        }
    }
}

echo $_SESSION['facebook_app_id'];

?>
-----
<?php

if ($_SESSION['order_in_progress'] != 1) {

    $isNewUIEnabled = true;

    if ($isNewUIEnabled) {
        header('Location: /storefront/checkout/success');
        return;
    }
} else {
    die('Sorry, ');
}

function insertorder() {
    if (!empty($orderTaxDetails)) {

        abc($orderTaxQuery);
        if (abc()) {
            return false;
        }
    }
}

echo $_SESSION['facebook_app_id'];

?>