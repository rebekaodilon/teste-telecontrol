<?php
namespace App\Controllers;

use App\Core\Response;
use App\Middleware\AuthMiddleware;
use App\Services\OrderService;

class OrderController {
    private OrderService $svc;

    public function __construct() {
        $this->svc = new OrderService();
    }

    public function index() {
        if (!($u = AuthMiddleware::verify())) return;
        $filters = ['q' => $_GET['q'] ?? null];
        Response::json($this->svc->list($filters));
    }

    public function show(array $p) {
        if (!($u = AuthMiddleware::verify())) return;
        $id = (int)($p['id'] ?? 0);
        $row = $this->svc->find($id);
        if (!$row) return Response::json(['error' => 'Not found'], 404);
        Response::json($row);
    }

    public function store() {
        if (!($u = AuthMiddleware::verify())) return;
        $in = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        try {
            $row = $this->svc->create($in, (int)$u['id']);
            Response::json($row, 201);
        } catch (\InvalidArgumentException $e) {
            Response::json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(array $p) {
        if (!($u = AuthMiddleware::verify())) return;
        $id = (int)($p['id'] ?? 0);
        $in = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        try {
            $row = $this->svc->update($id, $in, (int)$u['id']);
            if (!$row) return Response::json(['error' => 'Not found'], 404);
            Response::json($row);
        } catch (\InvalidArgumentException $e) {
            Response::json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(array $p) {
        if (!($u = AuthMiddleware::verify(['admin']))) return;
        $id = (int)($p['id'] ?? 0);
        if ($this->svc->delete($id, (int)$u['id'])) {
            Response::json(['ok' => true]);
        } else {
            Response::json(['error' => 'Not found'], 404);
        }
    }
}
