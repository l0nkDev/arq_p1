<?php
    class LocationView {
        public $data;

        public function render($data) {
            $this->data = $data;
            include_once("views/LocationView.phtml");
        }
    }
?>