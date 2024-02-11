<?php
require_once("./vendor/autoload.php");
require_once("./Config/connect.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
date_default_timezone_set("Asia/Bangkok");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Disposition, Content-Type, Content-Length, Accept-Encoding,Authorization");
header('Content-Type: application/json');
require_once("./Controller/CheckChangeController.php");
require_once("./Controller/UserController.php");
require_once("./Controller/AuthController.php");
require_once("./Controller/PatrolController.php");
require_once("./Controller/MachineController.php");

// สร้าง Class API
class API
{
    public $CheckChangeController;
    public $UserController;
    public $AuthController;
    public $PatrolController;
    public $MachineController;
    public function __construct()
    {
        $this->CheckChangeController = new CheckChangeController();
        $this->UserController = new UserController();
        $this->AuthController = new AuthController();
        $this->PatrolController = new PatrolController();
        $this->MachineController = new MachineController();
    }
}

$api = new API(); //สร้าง Initial API

//ตั้งค่า Routes API Endpoint
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $req = (object) json_decode(file_get_contents("php://input"));
    if ($req !== null) {
        //ตรวจสอบ Request Router
        if (isset($req->router)) {
            switch ($req->router) {
                case "getCheckChangeList":
                    $api->CheckChangeController->index(); //Method
                    break;
                case "checkChangeLists":
                    $api->CheckChangeController->saveCheckCheet($req); //Method
                    break;
                case "deleteCheckCheet":
                    $api->CheckChangeController->deleteCheckCheet($req); //Method
                    break;
                case "updateCheckChange":
                    $api->CheckChangeController->updateCheckChange($req); //Method
                    break;
                case "seniorComment":
                    $api->CheckChangeController->seniorComment($req); //Method
                    break;
                case "getUsers":
                    $api->UserController->getUsers(); //Method
                    break;
                case "addUsers":
                    $api->UserController->addUsers($req); //Method
                    break;
                case "updateUsers":
                    $api->UserController->updateUser($req); //Method
                    break;
                case "deleteUser":
                    $api->UserController->deleteUser($req); //Method
                    break;
                case "login":
                    $api->AuthController->Login($req); //Method
                    break;
                case "auth":
                    $api->AuthController->AuthToken($req); //Method
                    break;
                case "getPatrolList":
                    $api->PatrolController->index(); //Method
                    break;
                case "checkPatrol":
                    $api->PatrolController->savePatrol($req); //Method
                    break;
                case "deletePatrol":
                    $api->PatrolController->deletePatrol($req); //Method
                    break;
                case "updatePatrol":
                    $api->PatrolController->updatePatrol($req); //Method
                    break;
                case "getMachine":
                    $api->MachineController->index(); //Method
                    break;
                default:
                    echo json_encode(["msg" => "Can't Request!"]);
            }
        } else {
            echo json_encode(["msg" => "Router is not provided!"]);
        }
    } else {
        echo json_encode(["msg" => "Error Router!"]);
    }
} else if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    echo json_encode(["msg" => "Can't GET Request."]);
} else {
    echo json_encode(["msg" => "Route is not provided!"]);
}
