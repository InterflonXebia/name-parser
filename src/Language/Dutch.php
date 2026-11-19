<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Language;

class Dutch extends AbstractLanguage
{
    public const SUFFIXES = [
        'jr' => 'jr.',
        'sr' => 'sr.',
        'bsc' => 'BSc',
        'msc' => 'MSc',
        'ma' => 'MA',
        'llm' => 'LLM',
        'phd' => 'PhD',
    ];

    public const SALUTATIONS = [
        'de heer' => 'De heer',
        'dhr' => 'Dhr.',
        'heer' => 'Heer',
        'mevrouw' => 'Mevrouw',
        'mevr' => 'Mevr.',
        'mw' => 'Mw.',
        'mej' => 'Mej.',
        'juffrouw' => 'Juffrouw',
        'drs' => 'Drs.',
        'ir' => 'Ir.',
        'ing' => 'Ing.',
        'mr' => 'Mr.',
    ];

    /**
     * the "tussenvoegsels" (e.g. van, de, van der, ten);
     * "'t" is omitted on purpose, because a leading apostrophe
     * is treated as a nickname delimiter
     */
    public const LASTNAME_PREFIXES = [
        'aan' => 'aan',
        'bij' => 'bij',
        'de' => 'de',
        'den' => 'den',
        'der' => 'der',
        'het' => 'het',
        'in' => 'in',
        'onder' => 'onder',
        'op' => 'op',
        'te' => 'te',
        'ten' => 'ten',
        'ter' => 'ter',
        'tot' => 'tot',
        'uit' => 'uit',
        'van' => 'van',
        'vd' => 'v.d.',
        'oude' => 'Oude',
    ];
}
