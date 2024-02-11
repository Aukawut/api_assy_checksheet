<?php
class PatrolController extends Model
{
    public function index()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM TBL_PATROL_INFO");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo  json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    }
    public function savePatrol($req)
    {
        try {
            if (empty($req->emp_no) || empty($req->manual)  || empty($req->screws) || empty($req->chemical) || empty($req->label) || empty($req->equipment) || empty($req->partrank) || empty($req->partlabel) || empty($req->wear_equipment) || empty($req->inspector)) {
                // ตรวจสอบค่า  Null
                echo json_encode(["err" => true, "msg" => "Please fill out the information completely !"]);
            } else {
                if ($req->manual == "NG" && empty($req->manual_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->screws == "NG" && empty($req->screws_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->chemical == "NG" && empty($req->chemical_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->label == "NG" && empty($req->label_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->equipment == "NG" && empty($req->equipment_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->partrank == "NG" && empty($req->partrank_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->partlabel == "NG" && empty($req->partlabel_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->wear_equipment == "NG" && empty($req->wear_equipment_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else {
                    $stmt_insert = $this->conn->prepare("INSERT INTO TBL_PATROL_INFO 
                    ([EMP_NO],[MANUAL],[MANUAL_REMARK],[SCREWS],[SCREWS_REMARK],[CHEMICAL],[CHEMICAL_REMARK],[LABEL],[LABEL_REMARK],[EQUIPMENT],[EQUIPMENT_REMARK],[PARTRANK],[PARTRANK_REMARK],[PARTLABEL],[PARTLABEL_REMARK],[WEAR_EQUIPMENT],[WEAR_EQUIPMENT_REMARK],[INSPECTOR],[CREATED_AT]) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt_insert->execute([$req->emp_no, $req->manual, $req->manual_remark, $req->screws, $req->screws_remark, $req->chemical, $req->chemical_remark, $req->label, $req->label_remark, $req->equipment, $req->equipment_remark, $req->partrank, $req->partrank_remark, $req->partlabel, $req->partlabel_remark, $req->wear_equipment, $req->wear_equipment_remark, $req->inspector, date('Y-m-d H:i:s')]);
                    //บันทึกข้อมูลของ Database
                    echo json_encode(["err" => false, "msg" => "Inserted!", "status" => "Ok"]);
                }
            }
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    }
    public function deletePatrol($req)
    {
        $id = $req->id;
        if (empty($id)) {
            echo json_encode(["err" => true, "msg" => "Id empty!"]);
        } else {
            try {
                $stmt_del = $this->conn->prepare("DELETE FROM TBL_PATROL_INFO WHERE Id = ?");
                $stmt_del->execute([$id]);
                echo json_encode(["err" => false, "msg" => "Deleted!", "status" => "Ok"]);
            } catch (PDOException $e) {
                echo json_encode(["err" => true, "msg" => $e->getMessage()]);
            }
        }
    }
    public function updatePatrol($req){
        try {
            if (empty($req->id) || empty($req->manual)  || empty($req->screws) || empty($req->chemical) || empty($req->label) || empty($req->equipment) || empty($req->partrank) || empty($req->partlabel) || empty($req->wear_equipment) ) {
                // ตรวจสอบค่า  Null
                echo json_encode(["err" => true, "msg" => "Please fill out the information completely !"]);
            } else {
                if ($req->manual == "NG" && empty($req->manual_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->screws == "NG" && empty($req->screws_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->chemical == "NG" && empty($req->chemical_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->label == "NG" && empty($req->label_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->equipment == "NG" && empty($req->equipment_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->partrank == "NG" && empty($req->partrank_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->partlabel == "NG" && empty($req->partlabel_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->wear_equipment == "NG" && empty($req->wear_equipment_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else {
                    $stmt_update = $this->conn->prepare("UPDATE TBL_PATROL_INFO SET [MANUAL] = ?,[MANUAL_REMARK] = ?,[SCREWS] = ?,[SCREWS_REMARK] = ?,[CHEMICAL] = ?,[CHEMICAL_REMARK] = ?,[LABEL] = ?,[LABEL_REMARK] = ?,[EQUIPMENT] = ?,[EQUIPMENT_REMARK] = ?,[PARTRANK] = ?,[PARTRANK_REMARK] = ?,[PARTLABEL] = ?,[PARTLABEL_REMARK] = ?,[WEAR_EQUIPMENT] = ?,[WEAR_EQUIPMENT_REMARK] = ?,[UPDATED_AT] = ? WHERE [Id] = ?") ;
                    $stmt_update->execute([$req->manual, $req->manual_remark, $req->screws, $req->screws_remark, $req->chemical, $req->chemical_remark, $req->label, $req->label_remark, $req->equipment, $req->equipment_remark, $req->partrank, $req->partrank_remark, $req->partlabel, $req->partlabel_remark, $req->wear_equipment, $req->wear_equipment_remark, date('Y-m-d H:i:s'),$req->id]);
                    //บันทึกข้อมูลของ Database
                    echo json_encode(["err" => false, "msg" => "Updated!", "status" => "Ok"]);
                }
            }
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    }
}
