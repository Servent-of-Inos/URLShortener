<?php

namespace App\BijectiveFunction;

/**
 * Encodes any integer into a base(n) string where n is alphabet's length.
 */
class Bijective
{
    const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    protected $base;
    protected $alphabet;

    public function __construct($alphabet = self::ALPHABET)
    {
        $this->alphabet = $alphabet;
        $this->base = strlen($this->alphabet);
    }

    public function alphabet()
    {
        return $this->alphabet;
    }

    /**
     * Encodes integer into its associated string.
     *
     * @param  integer $int
     * @return string
     */
    public function encode($int)
    {
        if (0 === $int) {
            return $this->alphabet[0];
        }

        $str = '';
        while (0 < $int) {
            $remainder = $int % $this->base;
            $str = $str . $this->alphabet[$remainder];
            $int = ($int - $remainder) / $this->base;
        }

        return strrev($str);
    }

    /**
     * Decodes string into its associated integer.
     *
     * @param  string $str
     * @return integer
     * @throws \LogicException if provided string contains unsupported char.
     */
    public function decode($str)
    {
        if ($str[0] === $this->alphabet[0]) {
            throw BijectiveException::onZeroValueAsFirstChar($this);
        }

        $int = 0;
        foreach (str_split($str) as $char) {
            if (false === $pos = strpos($this->alphabet, $char)) {
                throw BijectiveException::onUnsupportedChar($this, $char);
            }

            $int = ($int * $this->base) + $pos;
        }

        return $int;
    }
}
