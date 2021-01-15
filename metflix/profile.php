<?php
    include_once("includes/header.php");
    include_once("includes/paypalConfig.php");
    include_once("includes/classes/Account.php");
    include_once("includes/classes/FormSanitizer.php");
    include_once("includes/classes/Constants.php");
    include_once("includes/classes/BillingDetails.php");

    $detailsMessage = "";
    $passwordMessage = "";
    $subscriptionMessage = "";
    $user = new User($con, $userLoggedIn);

    if(isset($_POST["saveDetailsButton"])){
        $account = new Account($con);

        $firstName = FormSanitizer::sanitizeFormString($_POST["firstName"]);
        $lastName = FormSanitizer::sanitizeFormString($_POST["lastName"]);
        $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);


        if ($account->updateDetails($firstName, $lastName, $email, $userLoggedIn)){
            // Success update
            $detailsMessage = "<div class='alertSuccess'>
                                Details update successfully!
                                </div>";
        }
        else {
            // Fail update
            $errorMessage = $account->getFirstError();
            $detailsMessage = "<div class='alertError'>
                                $errorMessage
                                </div>";
        }
    }
    if(isset($_POST["savePasswordButton"])){
        $account = new Account($con);

        $oldpw = FormSanitizer::sanitizeFormPassword($_POST["oldpassword"]);
        $newpw = FormSanitizer::sanitizeFormPassword($_POST["newpassword"]);
        $newpw2 = FormSanitizer::sanitizeFormPassword($_POST["newpassword2"]);
        
        if ($account->updatePassword($oldpw, $newpw, $newpw2, $userLoggedIn)){
            // Success update
            $passwordMessage = "<div class='alertSuccess'>
                                password update successfully!
                                </div>";
        }
        else {
            // Fail update
            $errorMessage = $account->getFirstError();
            $passwordMessage = "<div class='alertError'>
                                $errorMessage
                                </div>";
        }
    }
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $token = $_GET['token'];
        $agreement = new \PayPal\Api\Agreement();
      
        $subscriptionMessage = "<div class='alertError'>
                            Something went wrong.
                            </div>";

        try {
          // Execute agreement
          $agreement->execute($token, $apiContext);

          // update user subscibe table
            $result = BillingDetails::insertDetails($con, $agreement, $token, $userLoggedIn);
            $result = $result && $user->setIsSubscibe(1);

            if ($result) {
                $subscriptionMessage = "<div class='alertSuccess'>
                            You're all signed up!
                            </div>";
            }

        } catch (PayPal\Exception\PayPalConnectionException $ex) {
          echo $ex->getCode();
          echo $ex->getData();
          die($ex);
        } catch (Exception $ex) {
          die($ex);
        }
      } 
      else if(isset($_GET['success']) && $_GET['success'] == 'false') {
        $subscriptionMessage = "<div class='alertError'>
                            User cancel or something went wrong.
                            </div>";
      }
?>
<div class="settingContainer column">
    <div class="formSection">
        <form method="POST">
            <h2>User Details</h2>

            <?php
                $firstName = isset($_POST["firstName"]) ? $_POST["firstName"] : $user->getFirstName();
                $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : $user->getLastName();
                $email = isset($_POST["email"]) ? $_POST["email"] : $user->getEmail();
            ?>

            <input type="text" name="firstName" placeholder="First name" value="<?php echo $firstName ?>">
            <input type="text" name="lastName" placeholder="Last name" value="<?php echo $lastName ?>">
            <input type="email" name="email" placeholder="Email" value="<?php echo $email ?>">
            <div class="message">
                <?php echo $detailsMessage; ?>
            </div>

            <input type="submit" name="saveDetailsButton" value="Save">
        </form>
    </div>

    <div class="formSection">
        <form method="POST">
            <h2>Update password</h2>
            <input type="password" name="oldpassword" placeholder="Old password">
            <input type="password" name="newpassword" placeholder="New password">
            <input type="password" name="newpassword2" placeholder="Confirm password">

            <div class="message">
                <?php echo $passwordMessage; ?>
            </div>

            <input type="submit" name="savePasswordButton" value="Update">
        </form>
    </div>

    <div class="formSection">
        <h2>Subscription </h2>
        <div class="message">
            <?php echo $subscriptionMessage; ?>
        </div>
        <?php 
            if($user->getIsSubscribed()){
                echo "<h3>You are subscribed! Go to Paypal if you want to cancel.";
            }else{
                echo "<a href='billing.php'>Subscribe to Metflix!</a>";
            }
        ?>
    </div>
</div>