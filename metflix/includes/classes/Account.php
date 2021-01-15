<?php
    class Account {
        private $con;
        private $errorArray = array();

        public function __construct($con) {
            $this->con = $con;
        }
        public function updateDetails($fn, $ln, $em, $un){
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateNewEmail($em, $un);
            
            if (empty($this->errorArray)) {
                $query = $this->con->prepare("UPDATE users SET firstName=:fn, lastName=:ln,
                                             email=:em 
                                             WHERE username=:un");
                $query->bindValue(":fn", $fn);
                $query->bindValue(":ln", $ln);
                $query->bindValue(":em", $em);
                $query->bindValue(":un", $un);

                return $query->execute();
            }
            return false;
        }
        public function updatePassword($oldpw, $pw, $pw2, $un){
            $this->validateOldPasswords($oldpw, $un); //lulube pw = 22222 
            $this->validatePasswords($pw, $pw2);

            if (empty($this->errorArray)) {
                $pw = hash("sha512", $pw);
                $query = $this->con->prepare("UPDATE users SET password=:pw
                                             WHERE username=:un");
                $query->bindValue(":pw", $pw);
                $query->bindValue(":un", $un);

                return $query->execute();
            }
            return false;
        }

        public function register($fn, $ln, $un, $em, $em2, $pw, $pw2) {
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateUsername($un);
            $this->validateEmail($em, $em2);
            $this->validatePasswords($pw, $pw2);
            // if no error message -> value correct
            if (empty($this->errorArray)) {
                return $this->insertUserDetails($fn, $ln, $un, $em, $pw);
            }
            return false;

        }
        public function login($un, $pw) {
            $pw = hash("sha512", $pw);
            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
            $query->bindValue(":un", $un);
            $query->bindValue(":pw", $pw);
            $query->execute();

            if ($query->rowCount() == 1) {
                return true;
            }
            
            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
        private function insertUserDetails($fn, $ln, $un, $em, $pw) {
            $pw = hash("sha512", $pw);
            $query = $this->con->prepare("INSERT INTO users (firstName, lastName, username, email, password)
            VALUE (:fn, :ln, :un, :em, :pw)");
            $query->bindValue(":fn", $fn);
            $query->bindValue(":ln", $ln);
            $query->bindValue(":un", $un);
            $query->bindValue(":em", $em);
            $query->bindValue(":pw", $pw);

            // if php success insert the data -> return true; else false
            return $query->execute();
        }

        private function validateFirstName($fn) {
            if (strlen($fn) < 2 || strlen($fn) > 25) {
                array_push($this->errorArray, Constants::$firstNameCharacters);
            }
        }
        private function validateLastName($ln) {
            if (strlen($ln) < 2 || strlen($ln) > 25) {
                array_push($this->errorArray, Constants::$lastNameCharacters);
            }
        }
        private function validateUsername($un) {
            if (strlen($un) < 2 || strlen($un) > 25) {
                array_push($this->errorArray, Constants::$usernameCharacters);
                return; // since it's wrong length, return, so no bother the query
            }

            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un");
            $query->bindValue(":un", $un);
            $query->execute();

            // if username already exist
            if($query->rowCount() != 0) {
                array_push($this->errorArray, Constants::$usernameTaken);
            }
        }
        private function validateEmail($em, $em2) {
            if ($em != $em2) {
                array_push($this->errorArray, Constants::$emailsDontMatch);
                return;
            }
            if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }
            $query = $this->con->prepare("SELECT * FROM users WHERE email=:em");
            $query->bindValue(":em", $em);
            $query->execute();

            // if email already exist
            if($query->rowCount() != 0) {
                array_push($this->errorArray, Constants::$emailTaken);
            }
        }
        private function validateNewEmail($em, $un) {
            
            if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid);
                return;
            }
            $query = $this->con->prepare("SELECT * FROM users WHERE email=:em AND username != :un");
            $query->bindValue(":em", $em);
            $query->bindValue(":un", $un);
            $query->execute();

            // if email already exist
            if($query->rowCount() != 0) {
                array_push($this->errorArray, Constants::$emailTaken);
            }
        }
        private function validateOldPasswords($oldpw, $un){
            $oldpw = hash("sha512", $oldpw);
            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
            $query->bindValue(":un", $un);
            $query->bindValue(":pw", $oldpw);
            $query->execute();

            if ($query->rowCount() == 0){
                array_push($this->errorArray, Constants::$wrongPassword);
            }

        }
        private function validatePasswords($pw, $pw2) {
            if ($pw != $pw2) {
                array_push($this->errorArray, Constants::$passwordsDontMatch);
                return;
            }
            if (strlen($pw) < 5 || strlen($pw) > 25) {
                array_push($this->errorArray, Constants::$passwordLength);
                return; // since it's wrong length, return, so no bother the query
            }
        }
        public function getError($error) {
            if (in_array($error, $this->errorArray)) {
                return "<span class='errorMessage'>$error</span>";
            }
        }
        public function getFirstError(){
            if(!empty($this->errorArray)){
                return $this->errorArray[0];
            }
        }
    }
?>