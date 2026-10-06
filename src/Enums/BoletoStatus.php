<?php

namespace CaiqueBispo\BancoDoBrasil\Enums;

enum BoletoStatus: int
{
    case Normal = 1;
    case Movimento = 2;
    case EmCartorio = 3;
    case TituloComOcorrencia = 4;
    case Protestado = 5;
    case Liquidado = 6;
    case Baixado = 7;
    case TituloComPendenciaDeCartorio = 8;
}
