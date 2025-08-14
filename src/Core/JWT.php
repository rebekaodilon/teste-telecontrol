<?php
namespace App\Core;
use Firebase\JWT\JWT as FJWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
class JWT {
    public static function encode(array $claims): string {
        $now = new DateTimeImmutable();
        $payload = array_merge([
            'iss'=>Env::get('JWT_ISS','telecontrol'),
            'aud'=>Env::get('JWT_AUD','telecontrol-users'),
            'iat'=>$now->getTimestamp(),
            'nbf'=>$now->getTimestamp(),
            'exp'=>$now->getTimestamp()+ (int)Env::get('JWT_EXP',3600),
        ], $claims);
        return FJWT::encode($payload, Env::get('JWT_SECRET'), 'HS256');
    }
    public static function decode(string $jwt): array {
        return (array) FJWT::decode($jwt, new Key(Env::get('JWT_SECRET'), 'HS256'));
    }
}
