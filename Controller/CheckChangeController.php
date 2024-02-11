<?php
date_default_timezone_set("Asia/Bangkok");


class CheckChangeController extends Model
{
    public function index()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM TBL_CHECK_CHANGE ORDER BY Id DESC");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo  json_encode($result);
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    }
    public function saveCheckCheet($req)
    {
        try {
            if (empty($req->emp_no) || empty($req->label)  || empty($req->fourM) || empty($req->manual) || empty($req->electricity) || empty($req->pokayoke) || empty($req->stock) || empty($req->assemble) || empty($req->inspector) || empty($req->dateCheck) || empty($req->machine)) {
                // ตรวจสอบค่า  Null
                echo json_encode(["err" => true, "msg" => "Please fill out the information completely !"]);
            } else {
                if ($req->label == "NG" && empty($req->label_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->fourM == "NG" && empty($req->four_m_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->manual == "NG" && empty($req->manual_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->electricity == "NG" && empty($req->electricity_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->pokayoke == "NG" && empty($req->pokayoke_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->stock == "NG" && empty($req->stock_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->assemble == "NG" && empty($req->assemble_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else {
                    $stmt_insert = $this->conn->prepare("INSERT INTO TBL_CHECK_CHANGE 
                    ([EMP_NO],[LABEL],[FOUR_M],[MANUAL],[ELECTRICITY],[POKAYOKE],[STOCK],[ASSEMBLE],[LABEL_REMARK],[FOUR_M_REMARK],[MANUAL_REMARK],[ELECTRICITY_REMARK],[POKAYOKE_REMARK],[STOCK_REMARK],[ASSEMBLE_REMARK],[CREATED_AT],[INSPECTOR],[DATE_CHECK],[MACHINE]) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    $stmt_insert->execute([$req->emp_no, $req->label, $req->fourM, $req->manual, $req->electricity, $req->pokayoke, $req->stock, $req->assemble, $req->label_remark, $req->four_m_remark, $req->manual_remark, $req->electricity_remark, $req->pokayoke_remark, $req->stock_remark, $req->assemble_remark, date('Y-m-d H:i:s'), $req->inspector, $req->dateCheck, $req->machine]);
                    //บันทึกข้อมูลของ Database
                    echo json_encode(["err" => false, "msg" => "Inserted!", "status" => "Ok"]);
                }
            }
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    }
    public function deleteCheckCheet($req)
    {
        $id = $req->id;
        if (empty($id)) {
            echo json_encode(["err" => true, "msg" => "Id empty!"]);
        } else {
            try {
                $stmt_del = $this->conn->prepare("DELETE FROM TBL_CHECK_CHANGE WHERE Id = ?");
                $stmt_del->execute([$id]);
                echo json_encode(["err" => false, "msg" => "Deleted!", "status" => "Ok"]);
            } catch (PDOException $e) {
                echo json_encode(["err" => true, "msg" => $e->getMessage()]);
            }
        }
    }
    public function updateCheckChange($req)
    {
        try {
            if (empty($req->label)  || empty($req->fourM) || empty($req->manual) || empty($req->electricity) || empty($req->pokayoke) || empty($req->stock) || empty($req->assemble) || empty($req->id)) {
                // ตรวจสอบค่า  Null
                echo json_encode(["err" => true, "msg" => "Please fill out the information completely !"]);
            } else {
                if ($req->label == "NG" && empty($req->label_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->fourM == "NG" && empty($req->four_m_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->manual == "NG" && empty($req->manual_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->electricity == "NG" && empty($req->electricity_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->pokayoke == "NG" && empty($req->pokayoke_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->stock == "NG" && empty($req->stock_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else if ($req->assemble == "NG" && empty($req->assemble_remark)) {
                    echo json_encode(["err" => true, "msg" => "Please fill out the notes completely."]);
                } else {
                    $stmt_select = $this->conn->prepare("SELECT * FROM TBL_CHECK_CHANGE WHERE Id = ?");
                    $stmt_select->execute([$req->id]);
                    $result = $stmt_select->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $stmt_update = $this->conn->prepare("UPDATE TBL_CHECK_CHANGE SET [LABEL] = ?,[FOUR_M] = ?,[MANUAL] = ?,[ELECTRICITY] = ?,[POKAYOKE] = ?,[STOCK] = ?,[ASSEMBLE] = ?,[LABEL_REMARK] = ?,[FOUR_M_REMARK] = ?,[MANUAL_REMARK] = ?,[ELECTRICITY_REMARK] = ?,[POKAYOKE_REMARK] = ?,[STOCK_REMARK] = ?,[ASSEMBLE_REMARK] = ?,[UPDATED_AT] = ? WHERE [Id] = ?");
                        $stmt_update->execute([$req->label, $req->fourM, $req->manual, $req->electricity, $req->pokayoke, $req->stock, $req->assemble, $req->label_remark, $req->four_m_remark, $req->manual_remark, $req->electricity_remark, $req->pokayoke_remark, $req->stock_remark, $req->assemble_remark, date('Y-m-d H:i:s'), $req->id]);
                        //อัพเดทข้อมูลของ Database
                        echo json_encode(["err" => false, "msg" => "Updated!", "status" => "Ok"]);
                    } else {
                        echo json_encode(["err" => true, "msg" => "Id not found!"]);
                    }
                }
            }
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    }
    public function seniorComment($req)
    {
        if (empty($req->comment)) {
            echo json_encode(["err" => true, "msg" => "Please completed comment"]);
        } else {
            try {
                $stmt = $this->conn->prepare("UPDATE TBL_CHECK_CHANGE SET SENIOR_REMARK = ? WHERE Id = ?");
                $stmt->execute([$req->comment, $req->id]);
                echo json_encode(["err" => false, "msg" => "Updated!","status" => "Ok"]);
            } catch (PDOException $e) {
                echo json_encode(["err" => true, "msg" => $e->getMessage()]);
            }
        }
    }
}
