<?php
class AuthView {
        public $error;

        public function render($error) {
            $this->error = $error;
            include_once("views/AuthView.phtml");
        }
    }
?>