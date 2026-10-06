<?php

namespace CaiqueBispo\BancoDoBrasil\Enums;

enum Environment: string
{
    case Sandbox = 'sandbox';
    case Homologacao = 'homologacao';
    case Producao = 'producao';

    public function apiBaseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://api.sandbox.bb.com.br',
            self::Homologacao => 'https://api.hm.bb.com.br',
            self::Producao => 'https://api.bb.com.br',
        };
    }

    public function oauthBaseUrl(): string
    {
        return match ($this) {
            self::Sandbox => 'https://oauth.sandbox.bb.com.br',
            self::Homologacao => 'https://oauth.hm.bb.com.br',
            self::Producao => 'https://oauth.bb.com.br',
        };
    }
}
