<?php
    require_once("PayPal-PHP-SDK/autoload.php");
    // After Step 1
    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'AbFnRYSRdH_hn-qUeOFP80ZgMBFlstOxMhws-vUekQpq2ULuZteVotj_7cprxEMdwkHrJXgoTeMR4i6n',     // ClientID
            'ENCp6K9TLtD4O8PEwAhfsEA1Fc9-VJf-jVlORoGPtXokFZgZUKze_wgXW0ManjENZGBRb22AS9VDbDd-'      // ClientSecret
        )
    );
?>