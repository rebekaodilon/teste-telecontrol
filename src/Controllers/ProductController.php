<?php
namespace App\Controllers;
use App\Core\Response;
use App\Services\ProductService;

class ProductController {
    private ProductService $svc;

    public function __construct() {
        $this->svc = new ProductService();
    }

    public function index() {
        $f = [
            'q' => $_GET['q'] ?? null,
            'status' => $_GET['status'] ?? null,
            'warranty_min' => isset($_GET['warranty_min']) ? (int)$_GET['warranty_min'] : null,
            'warranty_max' => isset($_GET['warranty_max']) ? (int)$_GET['warranty_max'] : null,
        ];
        Response::json($this->svc->list($f));
    }

    public function show(array $p) {
        $id = (int)($p['id'] ?? 0);
        $row = $this->svc->find($id);
        if (!$row) return Response::json(['error' => 'Not found'], 404);
        Response::json($row);
    }

    public function store() {
        $in = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        try {
            $row = $this->svc->create($in);
            Response::json($row, 201);
        } catch (\InvalidArgumentException $e) {
            Response::json(['error' => $e->getMessage()], 422);
        }
    }

    public function update(array $p) {
        $id = (int)($p['id'] ?? 0);
        $in = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        try {
            $row = $this->svc->update($id, $in);
            if (!$row) return Response::json(['error' => 'Not found'], 404);
            Response::json($row);
        } catch (\InvalidArgumentException $e) {
            Response::json(['error' => $e->getMessage()], 422);
        }
    }

    public function destroy(array $p) {
        $id = (int)($p['id'] ?? 0);
        if ($this->svc->delete($id)) Response::json(['ok' => true]);
        else Response::json(['error' => 'Not found'], 404);
    }
}
