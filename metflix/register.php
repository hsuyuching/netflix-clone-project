<?php
    require_once("includes/config.php");
    require_once("includes/classes/FormSanitizer.php");
    require_once("includes/classes/Account.php");
    require_once("includes/classes/Constants.php");
    $account = new Account($con);

    if (isset($_POST["submitButton"])) {
        $fn = FormSanitizer::sanitizeFormString($_POST["firstName"]);
        $ln = FormSanitizer::sanitizeFormString($_POST["lastName"]);
        $un = FormSanitizer::sanitizeFormUsername($_POST["username"]);
        $em = FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $em2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);
        $pw = FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $pw2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);
        $success = $account->register($fn, $ln, $un, $em, $em2, $pw, $pw2);
        if ($success) {
            // go to index.php
            $_SESSION["userLoggedIn"] = $un;
            header("Location: index.php");
        }

    }
    function getInputValue($name) {
        if (isset($_POST[$name])) {
            echo $_POST[$name];
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to Metflix</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css"/>
    </head>

    <body>
        <div class="signUpContainer">
            <div class="column">
                <div class="header">
                    <img src="assets/images/metflix.png" title="logo" alt="metflix logo">
                    <h3>Sign up</h3>
                    <span>to continue to metflix</span>
                </div>
                <form method="POST">
                    <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                    <input type="text" name="firstName" placeholder="First name" value="<?php getInputValue("firstName"); ?>" required>

                    <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                    <input type="text" name="lastName" placeholder="Last name" value="<?php getInputValue("lastName"); ?>" required>

                    <?php echo $account->getError(Constants::$usernameCharacters); ?>
                    <?php echo $account->getError(Constants::$usernameTaken); ?>
                    <input type="text" name="username" placeholder="Username" value="<?php getInputValue("username"); ?>" required>

                    <?php echo $account->getError(Constants::$emailsDontMatch); ?>
                    <?php echo $account->getError(Constants::$emailInvalid); ?>
                    <?php echo $account->getError(Constants::$emailTaken); ?>
                    <input type="email" name="email" placeholder="Email" value="<?php getInputValue("email"); ?>" required>
                    <input type="email" name="email2" placeholder="Confirm email" value="<?php getInputValue("email2"); ?>" required>

                    <?php echo $account->getError(Constants::$passwordsDontMatch); ?>
                    <?php echo $account->getError(Constants::$passwordLength); ?>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="password2" placeholder="Confirm password" required>

                    <input type="submit" name="submitButton" value="Submit">
                </form>
                <a href="login.php" class="signInMessage">Already have an account? Let's login!</a>
            </div>
        </div>
    </body>
</html>