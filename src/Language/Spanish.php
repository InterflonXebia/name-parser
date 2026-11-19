<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Language;

class Spanish extends AbstractLanguage
{
    public const SUFFIXES = [
        'jr' => 'Jr.',
        'hijo' => 'hijo',
    ];

    public const SALUTATIONS = [
        'señor' => 'Señor',
        'sr' => 'Sr.',
        'señora' => 'Señora',
        'sra' => 'Sra.',
        'señorita' => 'Señorita',
        'srta' => 'Srta.',
        'don' => 'Don',
        'doña' => 'Doña',
        'dra' => 'Dra.',
        'lic' => 'Lic.',
        'licda' => 'Licda.',
        'ing' => 'Ing.',
        'profa' => 'Profa.',
    ];

    public const LASTNAME_PREFIXES = [
        'de' => 'de',
        'del' => 'del',
        'la' => 'la',
        'las' => 'las',
        'los' => 'los',
    ];
}
