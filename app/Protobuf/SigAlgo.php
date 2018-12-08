<?php

namespace App\Protobuf;

/**
 * Auto generated from message.proto at 2018-12-08 16:38:56
 */

/**
 * SigAlgo enum
 */
final class SigAlgo
{
    const ECDSA = 0;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'ECDSA' => self::ECDSA,
        );
    }
}
