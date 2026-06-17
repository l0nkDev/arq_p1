<?php
require_once("models/patterns/Observer.php");
require_once("models/patterns/Subject.php");

class TicketAuditObserver implements Observer {
    private $logFile;
    private TicketModel $subject;

    public function __construct(TicketModel $subject) {
        $this->subject = $subject;
        $this->logFile = __DIR__ . '/../logs/audit.log';
        if (!file_exists(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    public function update() {
        $action = $this->subject->getLastAction();
        $data = $this->subject->getLastData();
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] ACTION: $action | DATA: " . json_encode($data) . PHP_EOL;
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
}
?>
