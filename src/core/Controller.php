<?php
namespace Core;

abstract class Controller {
    protected $db;
    protected $response;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->response = new \Services\ResponseService();
    }

    protected function getRequestData() {
        return json_decode(file_get_contents("php://input"), true);
    }

    protected function validateRequest(array $required_fields, array $data) {
        foreach ($required_fields as $field) {
            if (!isset($data[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
    }
} 