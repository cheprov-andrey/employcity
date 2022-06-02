<?php

namespace App\Utils;

use App\Utils\RbcSerializer\RbcSerializer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RBCSerializerFactory
{
    public static function createRBCParser(ParameterBagInterface $parameterBag, string $type)
    {
        switch ($type) {
            case 'rbc':
                return new RbcSerializer($parameterBag);
            case 'pro':
                return new ProRbcSerializer();
            default:
                throw new \Exception('undefined type parser');
        }
    }
}
