<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Language;

class English extends AbstractLanguage
{
    public const SUFFIXES = [
        '1st' => '1st',
        '2nd' => '2nd',
        '3rd' => '3rd',
        '4th' => '4th',
        '5th' => '5th',
        'apr' => 'APR',
        'cme' => 'CME',
        'dds' => 'DDS',
        'dmd' => 'DMD',
        'dvm' => 'DVM',
        'esq' => 'Esq',
        'jr' => 'Jr',
        'junior' => 'Junior',
        'ma' => 'MA',
        'md' => 'MD',
        'pe' => 'PE',
        'phd' => 'PhD',
        'rph' => 'RPh',
        'senior' => 'Senior',
        'sr' => 'Sr',
    ];

    public const SALUTATIONS = [
        'fr' => 'Fr.',
        'madam' => 'Madam',
        'master' => 'Mr.',
        'miss' => 'Miss',
        'mister' => 'Mr.',
        'mr' => 'Mr.',
        'mrs' => 'Mrs.',
        'ms' => 'Ms.',
        'mx' => 'Mx.',
        'rev' => 'Rev.',
        'sir' => 'Sir',
        'his honour' => 'His Honour',
        'her honour' => 'Her Honour'
    ];

    public const LASTNAME_PREFIXES = [
        'da' => 'da',
        'de' => 'de',
        'del' => 'del',
        'della' => 'della',
        'der' => 'der',
        'di' => 'di',
        'du' => 'du',
        'la' => 'la',
        'pietro' => 'pietro',
        'st' => 'st.',
        'ter' => 'ter',
        'van' => 'van',
        'vanden' => 'vanden',
        'vere' => 'vere',
        'von' => 'von',
    ];
}
