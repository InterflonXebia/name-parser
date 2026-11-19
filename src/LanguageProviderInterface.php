<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser;

use InvalidArgumentException;

/**
 * creates language definitions by country code
 */
interface LanguageProviderInterface
{
    /**
     * create the language definition for the given country code
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code, case-insensitive (e.g. "NL", "es")
     * @throws InvalidArgumentException if no language is available for the country code
     */
    public function create(string $countryCode): LanguageInterface;

    /**
     * check if a language is available for the given country code
     */
    public function supports(string $countryCode): bool;
}
