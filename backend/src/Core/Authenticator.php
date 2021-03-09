<?php

namespace takisrs\Core;

use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;

/**
 * A class that handles the tasks related to JWT authorization
 * 
 * @author Panagiots Pantazopoulos <takispadaz@gmail.com>
 */
class Authenticator
{
    /**
     * Allowed algorithms for decoding a token
     */
    const ALLOWED_ALGORITHMS = ['HS256'];

    /**
     * Encodes the given payload and returns a JWT token
     *
     * @param array $payload The data to be used for the token encoding
     * @return string a JWT token
     */
    public static function getToken(array $payload): string
    {
        return JWT::encode($payload, $_ENV['JWT_SECRET']);
    }

    /**
     * Receives a token, decodes it and returns the data
     *
     * @param string $jwt A JWT token
     * @return array The decoded data
     */
    public static function decodeToken(string $jwt): array
    {
        try {
            return (array) JWT::decode($jwt, $_ENV['JWT_SECRET'], self::ALLOWED_ALGORITHMS);
        } catch (ExpiredException $e) {
            throw new HttpException(401, $e->getMessage());
        }
    }
}
