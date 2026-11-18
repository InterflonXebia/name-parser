<?php

declare(strict_types=1);

namespace TheIconic\NameParser;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use TheIconic\NameParser\Language\Dutch;

class DutchParserTest extends TestCase
{
    public static function provider(): array
    {
        return [
            [
                'Dhr. Jan de Vries',
                [
                    'salutation' => 'Dhr.',
                    'firstname' => 'Jan',
                    'lastname' => 'de Vries',
                ],
            ],
            [
                'Mevr. Anna van der Berg',
                [
                    'salutation' => 'Mevr.',
                    'firstname' => 'Anna',
                    'lastname' => 'van der Berg',
                ],
            ],
            [
                'De heer Pieter van den Heuvel',
                [
                    'salutation' => 'De heer',
                    'firstname' => 'Pieter',
                    'lastname' => 'van den Heuvel',
                ],
            ],
            [
                'Ir. Willem ter Horst',
                [
                    'salutation' => 'Ir.',
                    'firstname' => 'Willem',
                    'lastname' => 'ter Horst',
                ],
            ],
            [
                'Kees van Dijk jr.',
                [
                    'firstname' => 'Kees',
                    'lastname' => 'van Dijk',
                    'suffix' => 'jr.',
                ],
            ],
            [
                'Johan v.d. Berg',
                [
                    'firstname' => 'Johan',
                    'lastname' => 'v.d. Berg',
                ],
            ],
            [
                'Prof. Maria Bakker MSc',
                [
                    'salutation' => 'Prof.',
                    'firstname' => 'Maria',
                    'lastname' => 'Bakker',
                    'suffix' => 'MSc',
                ],
            ],
            [
                'Thomas uit den Bogaard',
                [
                    'firstname' => 'Thomas',
                    'lastname' => 'uit den Bogaard',
                ],
            ],
        ];
    }

    #[DataProvider('provider')]
    public function testParse(string $input, array $expectation): void
    {
        $parser = new Parser([
            new Dutch(),
        ]);

        $this->assertEquals($expectation, $parser->parse($input)->getAll());
    }
}
