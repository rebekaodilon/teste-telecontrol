<?php
namespace App\Services;

use App\Repositories\ProductRepository;
use App\Core\Validator;

class ProductService {
    private ProductRepository $repo;

    public function __construct() {
        $this->repo = new ProductRepository();
    }

    public function create(array $in): array {
        
        $code   = trim(Validator::sanitizeString($in['code'] ?? ''));
        $desc   = trim(Validator::sanitizeString($in['description'] ?? ''));
        $status = trim(Validator::sanitizeString($in['status'] ?? 'inactive'));
        $wm     = (int)($in['warranty_months'] ?? 0);

        if ($code === '' || $desc === '') {
            throw new \InvalidArgumentException('code, description required');
        }
        if (!in_array($status, ['active', 'inactive'], true)) {
            throw new \InvalidArgumentException('invalid status');
        }
        if ($wm < 0) {
            throw new \InvalidArgumentException('warranty_months must be >= 0');
        }

        if ($this->repo->findByCode($code)) {
            throw new \InvalidArgumentException('code already exists');
        }

        try {
            $id = $this->repo->create([
                'code'            => $code,
                'description'     => $desc,
                'status'          => $status,
                'warranty_months' => $wm
            ]);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000' && str_contains($e->getMessage(), '1062')) {
                throw new \InvalidArgumentException('code already exists');
            }
            throw $e;
        }

        return $this->repo->find($id);
    }

    public function update(int $id, array $in): ?array {
        $cur = $this->repo->find($id);
        if (!$cur) return null;

        $desc   = trim(Validator::sanitizeString($in['description'] ?? $cur['description']));
        $status = trim(Validator::sanitizeString($in['status'] ?? $cur['status']));
        $wm     = isset($in['warranty_months']) ? (int)$in['warranty_months'] : (int)$cur['warranty_months'];

        if (!in_array($status, ['active', 'inactive'], true)) {
            throw new \InvalidArgumentException('invalid status');
        }
        if ($wm < 0) {
            throw new \InvalidArgumentException('warranty_months must be >= 0');
        }

        $this->repo->update($id, [
            'description'     => $desc,
            'status'          => $status,
            'warranty_months' => $wm
        ]);

        return $this->repo->find($id);
    }

    public function delete(int $id): bool { return $this->repo->delete($id); }
    public function find(int $id): ?array { return $this->repo->find($id); }
    public function list(array $f = []): array { return $this->repo->list($f); }
}
