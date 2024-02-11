<?php
date_default_timezone_set("Asia/Bangkok");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Model
{
    public function GenarateToken($payload)
    {
        $key = $_ENV["JWT_SECRET"];
        $jwt = JWT::encode($payload, $key, 'HS256');
        return $jwt;
    }
    public function DecodeJWTToken($token)
    {
        try {
            $key = $_ENV["JWT_SECRET"];
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            return ["err" => false, "decoded" => $decoded];
        } catch (Exception $e) {
            return ["err" => true, "msg" => $e->getMessage()];
        }
    }
    public function AuthToken($req)
    {
        if (empty($req->token)) {
            echo json_encode(["err" => true, "msg" => "Token is empty!"]);
        } else {

            $decoded = $this->DecodeJWTToken($req->token);
            if ($decoded["err"] !== false) {
                echo json_encode(["err" => true, "msg" => $decoded["msg"]]);
            } else {
                echo json_encode(["err" => false,"msg" => "Authen success!","info" => $decoded ]);
              
            }
        }
    }
    public function Login($req)
    {
        if (empty($req->username) || empty($req->password)) {
        }
        try {
            $stmt = $this->conn->prepare("SELECT u.USERNAME,u.PASSWORD,u.FULLNAME,u.EMP_NO,u.DEPARTMENT,r.ROLE_NAME,u.ACTIVE FROM TBL_USERS_CHECKSHEET u LEFT JOIN TBL_SYS_ROLE r ON u.ROLE = r.id  WHERE u.USERNAME = ? AND u.ACTIVE = 'Y' ");
            $stmt->execute([$req->username]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $passwordHash = $result["PASSWORD"]; //Password ที่เข้ารหัสไว้ที่ Database
                $payload = [
                    "username" => $result["USERNAME"],
                    "fullname" => $result["FULLNAME"],
                    "emp_no" => $result["EMP_NO"],
                    "department" => $result["DEPARTMENT"],
                    "role" => $result["ROLE_NAME"],
                    "iat" => time(),
                    "exp" => time() + (90 * 60) //1 ชั่วโมง 30 นาที
                ];
                if (password_verify($req->password, $passwordHash)) {
                    $token = $this->GenarateToken($payload); //สร้าง JWT Token
                    if ($token !== null) {
                        echo json_encode(["err" => false, "msg" => "Login success!", "status" => "Ok", "info" => $payload, "token" => $token]);
                    } else {
                        echo json_encode(["err" => true, "msg" => "Error genarate token!"]);
                    }
                }
            } else {
                echo json_encode(["err" => true, "msg" => "Username or Password invalid!"]);
            }
        } catch (PDOException $e) {
            echo json_encode($e->getMessage());
        }
    }
}
