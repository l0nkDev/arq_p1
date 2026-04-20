<?php
    class UserView {
        public $data;

        public function render($data) {
            $this->data = $data;
            include_once("views/UserView.phtml");
        }
    }
?>