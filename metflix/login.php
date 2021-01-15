<?php
    require_once("includes/config.php");
    require_once("includes/classes/FormSanitizer.php");
    require_once("includes/classes/Account.php");
    require_once("includes/classes/Constants.php");
    $account = new Account($con);

    if (isset($_POST["submitButton"])) {
       
        $un = FormSanitizer::sanitizeFormUsername($_POST["username"]);
        $pw = FormSanitizer::sanitizeFormPassword($_POST["password"]);
      
        $success = $account->login($un, $pw);
        if ($success) {
            $_SESSION["userLoggedIn"] = $un;
            // go to index.php
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
                    <h3>Sign in</h3>
                    <span>to continue to metflix</span>
                </div>
                <form method="POST">
                    <?php echo $account->getError(Constants::$loginFailed); ?>
                    <input type="text" name="username" placeholder="Username" value="<?php getInputValue("username"); ?>" required>

                    <input type="password" name="password" placeholder="Password" required>
                    <input type="submit" name="submitButton" value="Submit">
                </form>
                <a href="register.php" class="signInMessage">Need an account? Let's sign up!</a>
            </div>
        </div>
    </body>
</html>