<?php

namespace App\BijectiveFunction;

/**
 * @author Eric Chau <eriic.chau@gmail.com>
 */
class BijectiveException extends \LogicException
{
    public static function onZeroValueAsFirstChar(Bijective $bijective)
    {
        $alphabet = $bijective->alphabet();

        return new static(
            "Must not use '{$alphabet[0]}' as first char cause it is the "
            . "zero value in current bijective's alphabet ('$alphabet')."
        );
    }

    public static function onUnsupportedChar(Bijective $bijective, $char)
    {
        $alphabet = $bijective->alphabet();

        return new static(
            "'$char' is not supported by bijective with alphabet '$alphabet'."
        );
    }
}
