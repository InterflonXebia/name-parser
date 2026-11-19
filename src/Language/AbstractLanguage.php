<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Language;

use InterflonXebia\NameParser\LanguageInterface;

/**
 * base class for language definitions
 *
 * Combines the entries every language shares (COMMON_* constants) with the
 * language specific ones (SUFFIXES, SALUTATIONS, LASTNAME_PREFIXES) defined
 * by the concrete language. All arrays map a lowercase lookup key (without
 * dots) to the normalized output value.
 *
 * Language specific entries take precedence over common ones with the same key.
 * A language can drop a common rule by overriding the COMMON_* constant.
 */
abstract class AbstractLanguage implements LanguageInterface
{
    /**
     * generational suffixes used in all supported languages
     */
    public const COMMON_SUFFIXES = [
        'i' => 'I',
        'ii' => 'II',
        'iii' => 'III',
        'iv' => 'IV',
        'v' => 'V',
    ];

    /**
     * academic titles used in all supported languages
     */
    public const COMMON_SALUTATIONS = [
        'dr' => 'Dr.',
        'prof' => 'Prof.',
    ];

    public const COMMON_LASTNAME_PREFIXES = [];

    public const SUFFIXES = [];

    public const SALUTATIONS = [];

    public const LASTNAME_PREFIXES = [];

    public function getSuffixes(): array
    {
        return static::SUFFIXES + static::COMMON_SUFFIXES;
    }

    public function getSalutations(): array
    {
        return static::SALUTATIONS + static::COMMON_SALUTATIONS;
    }

    public function getLastnamePrefixes(): array
    {
        return static::LASTNAME_PREFIXES + static::COMMON_LASTNAME_PREFIXES;
    }
}
