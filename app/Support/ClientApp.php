<?php

namespace App\Support;

class ClientApp
{
    private static $recoverPasswordFormat = "/auth/password/recover?token=%s";

    public static function getRecoverPasswordLink(string $token, string $email)
    {
        return url(config("client_app.url") . sprintf(self::$recoverPasswordFormat, $token));
    }
}
