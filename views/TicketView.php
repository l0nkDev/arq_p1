<?php
    class TicketView {
        public $tickets;
        public $locations;

        public function render($tickets, $locations) {
            $this->tickets = $tickets;
            $this->locations = $locations;
            include_once("views/TicketView.phtml");
        }
    }
?>