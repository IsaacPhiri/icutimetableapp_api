<?php
namespace Controllers;

use Core\Controller;

class UserController extends Controller {
    public function index() {
        try {
            $result = $this->db->query("SELECT * FROM Users");
            $users = $result->fetch_all(MYSQLI_ASSOC);
            $this->response->success($users);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage());
        }
    }

    public function show($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM Users WHERE user_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            
            if (!$user) {
                $this->response->error("User not found", 404);
            }
            
            $this->response->success($user);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage());
        }
    }

    public function store() {
        try {
            $data = $this->getRequestData();
            $this->validateRequest(['username', 'password', 'email'], $data);
            
            $password = password_hash($data['password'], PASSWORD_BCRYPT);
            
            $stmt = $this->db->prepare("
                INSERT INTO Users (username, password, email, first_name, last_name, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->bind_param("ssssss", 
                $data['username'],
                $password,
                $data['email'],
                $data['first_name'],
                $data['last_name'],
                $data['role']
            );
            
            if (!$stmt->execute()) {
                throw new \Exception("Failed to create user");
            }
            
            $this->response->success(['id' => $stmt->insert_id], "User created successfully");
        } catch (\Exception $e) {
            $this->response->error($e->getMessage());
        }
    }
} 