<?php
namespace App\Core;
class Validator {
    public static function sanitizeString(?string $s): string { return trim(strip_tags($s ?? '')); }
    public static function isValidCPF(string $cpf): bool {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf)!=11 || preg_match('/^(\d)\1{10}$/', $cpf)) return false;
        for($t=9;$t<11;$t++){
            $d=0; for($c=0;$c<$t;$c++){ $d += $cpf[$c] * (($t+1)-$c); }
            $d = ((10*$d)%11)%10; if ($cpf[$c] != $d) return false;
        }
        return true;
    }
}
