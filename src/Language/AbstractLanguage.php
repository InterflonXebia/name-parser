<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Language;

use InterflonXebia\NameParser\LanguageInterface;
use InterflonXebia\NameParser\NameStructureInterface;

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
 *
 * The name structure defaults to the English one and can be changed
 * via LASTNAME_PART_COUNT and COMPOUND_FIRSTNAME.
 */
abstract class AbstractLanguage implements LanguageInterface, NameStructureInterface
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

    /**
     * number of lastname parts a name usually has (prefixes not counted)
     */
    public const LASTNAME_PART_COUNT = 1;

    /**
     * whether all given names form the firstname (no middlenames)
     */
    public const COMPOUND_FIRSTNAME = false;

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

    public function getLastnamePartCount(): int
    {
        return static::LASTNAME_PART_COUNT;
    }

    public function hasCompoundFirstname(): bool
    {
        return static::COMPOUND_FIRSTNAME;
    }
}
