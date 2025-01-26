<?php
namespace Services;

class ResponseService {
    public function send($data, $status = 200, $message = '') {
        http_response_code($status);
        echo json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    public function error($message, $status = 400) {
        $this->send(null, $status, $message);
    }

    public function success($data = null, $message = 'Success') {
        $this->send($data, 200, $message);
    }
} 