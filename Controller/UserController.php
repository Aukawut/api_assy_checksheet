<?php
date_default_timezone_set("Asia/Bangkok");


class UserController extends Model
{

    public function getUsers()
    {
        try {
            $stmt =  $this->conn->prepare("SELECT * FROM TBL_USERS_CHECKSHEET ORDER BY Id ASC");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                echo json_encode(["err" => false, "results" => $result, "status" => "Ok"]);
            } else {
                echo json_encode(["msg" => "User is not found!"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    }
    public function addUsers($req)
    {
        // เช็คค่าวาง
        if (empty($req->username) || empty($req->password) || empty($req->fullname) || empty($req->empNo) || empty($req->department)) {
            echo json_encode(["err" => true, "msg" => "Please fill in complete information."]);
        } else {
            $username = preg_replace('/\s+/', '', $req->username); //Replace ช่องว่าง
            $password = preg_replace('/\s+/', '', $req->password);
            if (strlen($username) < 3) {
                // ตรวจสอบความยาว Username
                echo json_encode(["err" => true, "msg" => "Please enter a username of 3 characters or more"]);
            } else if (strlen($req->password) < 6 || strlen($req->password) > 20) {

                // ตรวจสอบความยาว Password
                echo json_encode(["err" => true, "msg" => "Password must be between 6 - 20 characters."]);
            } else {
                try {
                    $stmt =  $this->conn->prepare("SELECT * FROM TBL_USERS_CHECKSHEET WHERE USERNAME = ? AND ACTIVE = 'Y'");
                    $stmt->execute([$username]);
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result) {
                        echo json_encode(["err" => true, "msg" => "Username is duplicated!"]);
                    } else {

                        $stmt_insert = $this->conn->prepare("INSERT INTO TBL_USERS_CHECKSHEET (USERNAME,PASSWORD,FULLNAME,EMP_NO,DEPARTMENT,CREATED_AT,ROLE,LOGIN_TIME,ACTIVE) VALUES (?,?,?,?,?,?,?,?,?)");
                        $hashed = password_hash($password, PASSWORD_DEFAULT);
                        $stmt_insert->execute([$username, $hashed, $req->fullname, $req->empNo, $req->department, date('Y-m-d H:i:s'), 3, 0, 'Y']); //3 = Id Table Role | 0 = จำนวนการเข้าระบบ
                        echo json_encode(["err" => false, "msg" => "Added!", "status" => "Ok"]);
                    }
                } catch (PDOException $e) {
                    echo json_encode(["err" => true, "msg" => $e->getMessage()]);
                }
            }
        }
    }
    public function updateUser($req)
    {
        // เช็คค่าวาง
        if (empty($req->fullname) || empty($req->empNo || empty($req->role)) || empty($req->department) || empty($req->id || empty($req->active))) {
            echo json_encode(["err" => true, "msg" => "Please fill in complete information."]);
        } else {
           
            if ((strlen($req->password) < 6 || strlen($req->password) > 20) && !empty($req->password)) {
                // ตรวจสอบความยาว Password
                echo json_encode(["err" => true, "msg" => "Password must be between 6 - 20 characters."]);
            } else {
                try {
                    $stmt_select = $this->conn->prepare("SELECT * FROM TBL_USERS_CHECKSHEET WHERE Id = ?");
                    $stmt_select->execute([$req->id]);
                    $result = $stmt_select->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                       
                        if ($result["ACTIVE"] == 'N' && $req->active == 'Y') {
                             // ตรวจสอบ User ในระบบที่เพิ่มมาใหม่่ถ้ามีแล้วจะไม่สามารถเปิดใช้งาน User ได้
                            $stmt_check = $this->conn->prepare("SELECT * FROM TBL_USERS_CHECKSHEET WHERE USERNAME = ? AND ACTIVE = 'Y'");
                            $stmt_check->execute([$result["USERNAME"]]);
                            $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
                            if ($result_check) {
                               echo json_encode(["err" => true,"msg" => "Username duplicate!"]);
                            }
                        } else {
                            $stmt_update = $this->conn->prepare("UPDATE TBL_USERS_CHECKSHEET SET PASSWORD = ?,FULLNAME = ?,EMP_NO = ?,DEPARTMENT = ?,UPDATED_AT = ?,ROLE = ?,ACTIVE = ? WHERE Id = ?");
                            if(empty($req->password)){
                                $hashed = $result["PASSWORD"];
                            }else{
                                $password = preg_replace('/\s+/', '', $req->password);
                                $hashed = password_hash($password, PASSWORD_DEFAULT);
                            }
                           
                            $stmt_update->execute([$hashed, $req->fullname, $req->empNo, $req->department, date('Y-m-d H:i:s'), $req->role, $req->active, $req->id]); //3 = Id Table Role | 0 = จำนวนการเข้าระบบ
                            echo json_encode(["err" => false, "msg" => "Updated!", "status" => "Ok"]);
                        }
                    } else {
                        echo json_encode(["err" => true, "msg" => "User not found!"]);
                    }
                } catch (PDOException $e) {
                    echo json_encode(["err" => true, "msg" => $e->getMessage()]);
                }
            }
        }
    }
    public function deleteUser($req)
    {
        // เช็คค่าวาง
        $id = $req->id;   
        try{
                $stmt_select = $this->conn->prepare("SELECT * FROM TBL_USERS_CHECKSHEET WHERE Id = ?");
                $stmt_select->execute([$id]);
                $result = $stmt_select->fetch(PDO::FETCH_ASSOC);
                if($result){
                    $stmt_update = $this->conn->prepare("DELETE FROM TBL_USERS_CHECKSHEET WHERE Id = ?");
                    $stmt_update->execute([$id]);
                    echo json_encode(["err" => false, "msg" => "User Deleted!"]);
                }else{
                    echo json_encode(["err" => true, "msg" => "User not found!"]);
                }
        }catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    
    }
}
