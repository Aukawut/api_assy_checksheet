<?php
class MachineController extends Model {
    public function index(){
        try{
            $stmt = $this->conn->prepare("SELECT * FROM TBL__MACHINE ORDER BY Id ASC");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["err" => false,"result" => $result,"status" => "Ok"]);
        }catch(PDOException $e){
            echo json_encode(["err" => true,"msg" => $e->getMessage()]);
        }
       
    }
}
?>