<?php
require_once("models/LocationModel.php");

class LocationController
{
    private $locations;

    public function __construct()
    {
        $this->locations = new LocationModel();
    }

    public function handleRequest($action)
    {
        switch ($action) {
            case "create":
                $this->create($_POST);
                break;
            default:
                $this->read();
                break;
        }
    }

    function create($form)
    {
        if ($_SESSION["user_role"] !== "M") {
            header('Location: /');
            exit;
        };
        if (isset($form["name"])) {
            $this->locations->createPrecinct($form);
        } else if (isset($form["precinct_id"])) {
            $this->locations->createModule($form);
        }
        else if (isset($form["batch_data"])) {
            $items = json_decode($form["batch_data"], true);
            $type = $form["batch_type"];

            if (is_array($items)) {
                foreach ($items as $item) {
                    if ($type === 'room') {
                        $this->locations->createRoom([
                            "number" => $item["number"],
                            "module_id" => $item["parent_id"]
                        ]);
                    } else if ($type === 'module') {
                        $this->locations->createModule([
                            "number" => $item["number"],
                            "precinct_id" => $item["parent_id"]
                        ]);
                    }
                }
            }
        } else if (isset($form["module_id"])) {
            $this->locations->createRoom($form);
        }
        header('Location: /locations');
        exit;
    }

    function read()
    {
        if ($_SESSION["user_role"] !== "M") {
            header('Location: /');
            exit;
        };
        require_once("views/LocationView.php");
        $data = $this->locations->read();
        $view = new LocationView();
        $view->render($data);
        exit;
    }

}
?>