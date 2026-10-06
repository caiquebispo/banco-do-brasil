<?php

namespace CaiqueBispo\BancoDoBrasil\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \CaiqueBispo\BancoDoBrasil\BancoDoBrasil conta(string $slug)
 * @method static \CaiqueBispo\BancoDoBrasil\BancoDoBrasil environment(\CaiqueBispo\BancoDoBrasil\Enums\Environment $environment)
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Cobranca\CobrancaResource cobrancas()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Pix\PixCobResource pixCob()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Pix\PixCobVResource pixCobV()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Pix\PixLoteCobVResource pixLoteCobV()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Pix\PixResource pix()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Pix\PixWebhookResource pixWebhook()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Pix\PixLocationResource pixLocation()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Pagamento\PagamentoResource pagamentos()
 * @method static \CaiqueBispo\BancoDoBrasil\Resources\Extrato\ExtratoResource extratos()
 *
 * @see \CaiqueBispo\BancoDoBrasil\BancoDoBrasil
 */
class BB extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'banco-do-brasil';
    }
}
