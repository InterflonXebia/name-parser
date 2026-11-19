<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use InterflonXebia\NameParser\Language\Spanish;

class SpanishParserTest extends TestCase
{
    public static function provider(): array
    {
        return [
            [
                'Sr. Juan Pérez',
                [
                    'salutation' => 'Sr.',
                    'firstname' => 'Juan',
                    'lastname' => 'Pérez',
                ],
            ],
            [
                'Sra. María de la Cruz',
                [
                    'salutation' => 'Sra.',
                    'firstname' => 'María',
                    'lastname' => 'de la Cruz',
                ],
            ],
            [
                'Don Diego de la Vega',
                [
                    'salutation' => 'Don',
                    'firstname' => 'Diego',
                    'lastname' => 'de la Vega',
                ],
            ],
            [
                'Doña Ana del Río',
                [
                    'salutation' => 'Doña',
                    'firstname' => 'Ana',
                    'lastname' => 'del Río',
                ],
            ],
            [
                'Dra. Lucía Fernández',
                [
                    'salutation' => 'Dra.',
                    'firstname' => 'Lucía',
                    'lastname' => 'Fernández',
                ],
            ],
            [
                'Lic. Carlos de los Santos',
                [
                    'salutation' => 'Lic.',
                    'firstname' => 'Carlos',
                    'lastname' => 'de los Santos',
                ],
            ],
            [
                'Señor José Luis de las Heras',
                [
                    'salutation' => 'Señor',
                    'firstname' => 'José Luis',
                    'lastname' => 'de las Heras',
                ],
            ],
            [
                'Juan Pérez hijo',
                [
                    'firstname' => 'Juan',
                    'lastname' => 'Pérez',
                    'suffix' => 'hijo',
                ],
            ],
            [
                'Pérez, Juan',
                [
                    'firstname' => 'Juan',
                    'lastname' => 'Pérez',
                ],
            ],
            [
                'María José García López',
                [
                    'firstname' => 'María José',
                    'lastname' => 'García López',
                ],
            ],
            [
                'García López, María José',
                [
                    'firstname' => 'María José',
                    'lastname' => 'García López',
                ],
            ],
            [
                'Gabriel García Márquez',
                [
                    'firstname' => 'Gabriel',
                    'lastname' => 'García Márquez',
                ],
            ],
            [
                'Sra. María Fernanda Ruiz Díaz',
                [
                    'salutation' => 'Sra.',
                    'firstname' => 'María Fernanda',
                    'lastname' => 'Ruiz Díaz',
                ],
            ],
            [
                'María García',
                [
                    'firstname' => 'María',
                    'lastname' => 'García',
                ],
            ],
            [
                'María (Majo) García López',
                [
                    'firstname' => 'María',
                    'nickname' => 'Majo',
                    'lastname' => 'García López',
                ],
            ],
            [
                'María J. García López',
                [
                    'firstname' => 'María',
                    'initials' => 'J.',
                    'lastname' => 'García López',
                ],
            ],
        ];
    }

    #[DataProvider('provider')]
    public function testParse(string $input, array $expectation): void
    {
        $parser = new Parser([
            new Spanish(),
        ]);

        $this->assertEquals($expectation, $parser->parse($input)->getAll());
    }
}
