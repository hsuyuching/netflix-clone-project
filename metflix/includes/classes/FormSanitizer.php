<?php
    class FormSanitizer {
        public static function sanitizeFormString($inputtext) {
            $inputtext = strip_tags($inputtext);
            $inputtext = str_replace(" ", "", $inputtext);
            $inputtext = strtolower($inputtext);
            $inputtext = ucfirst($inputtext);
            return $inputtext;
        }
        public static function sanitizeFormUsername($inputtext) {
            $inputtext = strip_tags($inputtext);
            $inputtext = str_replace(" ", "", $inputtext);
            return $inputtext;
        }
        public static function sanitizeFormPassword($inputtext) {
            $inputtext = strip_tags($inputtext);
            return $inputtext;
        }
        public static function sanitizeFormEmail($inputtext) {
            $inputtext = strip_tags($inputtext);
            $inputtext = str_replace(" ", "", $inputtext);
            return $inputtext;
        }
    }

?>