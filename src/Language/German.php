<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Language;

class German extends AbstractLanguage
{
    public const SUFFIXES = [
        '1.' => '1.',
        '2.' => '2.',
        '3.' => '3.',
        '4.' => '4.',
        '5.' => '5.',
    ];

    public const SALUTATIONS = [
        'herr' => 'Herr',
        'hr' => 'Herr',
        'frau' => 'Frau',
        'fr' => 'Frau'
    ];

    public const LASTNAME_PREFIXES = [
        'der' => 'der',
        'von' => 'von',
    ];
}
