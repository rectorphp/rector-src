<?php

if ($_SESSION['order_in_progress'] != 1) {
    $isNewUIEnabled = true;

    if ($isNewUIEnabled) {
        if (
            isset($deliveryRequestResponse) &&
            !preg_match('/^Error/m', $deliveryRequestResponse) &&
            !empty($deliveryRequestResponse->delivery_tracking_url)
        ) {
            $trackingUrl = $deliveryRequestResponse->delivery_tracking_url;
            $_SESSION['checkout_success_tracking_url'] = $trackingUrl;
        }
        return;
    }
} else {
    die('Sorry, this order may already have been placed. If you did not receive a confirmation email, please give us a call to confirm your order.');
}

function insertorder()
{

    $result = abc('');
    if (!$result) {
        die(abc2());
    }
}

unset($_SESSION['cart']);

?>
