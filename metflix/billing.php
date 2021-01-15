<?php
    require_once("includes/paypalConfig.php");
    require_once("billingPlan.php");
    
    $id = $plan->getId();

    /* create billing agreement plan */
    use PayPal\Api\Agreement;
    use PayPal\Api\Payer;
    use PayPal\Api\Plan;

    // Create new agreement
    $agreement = new Agreement();
    $agreement->setName('Subscription to Metflix')
    ->setDescription('Recurring payments of $' . $price . 'to Metflix')
    ->setStartDate(gmdate("Y-m-d\TH:i:s\Z", strtotime("+1 month", time())));  // UTC time

    // Set plan id
    $plan = new Plan();
    $plan->setId($id);
    $agreement->setPlan($plan);

    // Add payer type
    $payer = new Payer();
    $payer->setPaymentMethod('paypal');
    $agreement->setPayer($payer);

    /* pass the plan into billing agreement */
    try {
        // Create agreement
        $agreement = $agreement->create($apiContext);
      
        // Extract approval URL to redirect user
        $approvalUrl = $agreement->getApprovalLink();
        header("Location: $approvalUrl");

      } catch (PayPal\Exception\PayPalConnectionException $ex) {
        echo $ex->getCode();
        echo $ex->getData();
        die($ex);
      } catch (Exception $ex) {
        die($ex);
      }
?>