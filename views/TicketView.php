<?php
    class TicketView {
        public $data;

        public function render($data) {
            $this->data = $data;
            include_once("views/TicketView.phtml");
        }
    }
?>