<?php

namespace takisrs\Core;

use \Firebase\JWT\JWT;

/**
 * A class that handles the tasks related to JWT authorization
 * @author Panagiots Pantazopoulos <takispadaz@gmail.com>
 */
class Authenticator
{

    /**
     * The key to be used for the token encoding, decoding
     */
    const KEY = "aSUPERsecureKEY";

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
        return JWT::encode($payload, self::KEY);
    }

    /**
     * Receives a token, decodes it and returns the data
     *
     * @param string $jwt A JWT token
     * @return array The decoded data
     */
    public static function decodeToken(string $jwt): array
    {
        return (array) JWT::decode($jwt, self::KEY, self::ALLOWED_ALGORITHMS);
    }

}
