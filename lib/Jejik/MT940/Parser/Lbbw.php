<?php

declare(strict_types=1);

/*
 * This file is part of the Jejik\MT940 library
 *
 * Copyright (c) 2020 Powercloud GmbH <d.richter@powercloud.de>
 * Licensed under the MIT license
 *
 * For the full copyright and license information, please see the LICENSE
 * file that was distributed with this source code.
 */

namespace Jejik\MT940\Parser;

/**
 * Sparkasse provides a parser for Sparkasse Bank
 * @package Jejik\MT940\Parser
 */
class Lbbw extends GermanBank
{
    /**
     * Test if the document can be read by the parser
     */
    public function accept(string $text): bool
    {
        $allowedUniqueIdentifiers = [
            ':20:LBBW'
        ];

        // unique identifier check
        $mt940Identifier = substr($text, 0, 8);
        if (in_array($mt940Identifier, $allowedUniqueIdentifiers)) {
            return true;
        }

        // if not check it's BLZ
        return $this->isBLZAllowed($text);
    }

    /**
     * Get an array of allowed BLZ for this bank
     */
    public function getAllowedBLZ(): array
    {
        return [
            '60050101'
        ];
    }

    /**
     * Get the contra account from a transaction
     *
     * @param array $lines The transaction text at offset 0 and the description at offset 1
     */
    protected function contraAccountNumber(array $lines): ?string
    {
        if (preg_match('/\?31(.*?)\?32/s', $lines[1], $match)) {
            return trim(preg_replace('/\s\s+/', '', $match[1]));
        }

        return null;
    }

    protected function contraAccountName(array $lines): ?string
    {
        if (preg_match('/\?32(.*?)\?34/s', $lines[1], $match)) {
            return trim(preg_replace('/\s\s+/', '', $match[1]));
        }

        return null;
    }

    //TODO: reformat :86: Mehrzweckfeld
    protected function description(?string $description): ?string
    {
        return parent::description($description); // TODO: Change the autogenerated stub
    }
}
