<?php

namespace InterflonXebia\NameParser\Mapper;

use InterflonXebia\NameParser\Language\English;
use InterflonXebia\NameParser\Part\Salutation;
use InterflonXebia\NameParser\Part\Firstname;
use InterflonXebia\NameParser\Part\Lastname;
use InterflonXebia\NameParser\Part\LastnamePrefix;

class LastnameMapperTest extends AbstractMapperTestCase
{
    /**
     * @return array
     */
    public static function provider(): array
    {
        return [
            [
                'input' => [
                    'Peter',
                    'Pan',
                ],
                'expectation' => [
                    'Peter',
                    new Lastname('Pan'),
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Peter',
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    'Peter',
                    new Lastname('Pan'),
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    new Firstname('Peter'),
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    new Firstname('Peter'),
                    new Lastname('Pan'),
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Lars',
                    'van',
                    'Trier',
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    'Lars',
                    new LastnamePrefix('van'),
                    new Lastname('Trier'),
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Dan',
                    'Huong',
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    'Dan',
                    new Lastname('Huong'),
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Von',
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    new Lastname('Von'),
                ],
            ],
            [
                'input' => [
                    'Mr',
                    'Von',
                ],
                'expectation' => [
                    'Mr',
                    new Lastname('Von'),
                ],
            ],
            [
                'input' => [
                    'Kirk'
                ],
                'expectation' => [
                    'Kirk'
                ],
            ],
            [
                'input' => [
                    'Kirk',
                ],
                'expectation' => [
                    new Lastname('Kirk'),
                ],
                'arguments' => [
                    true
                ],
            ],
            'two lastname parts' => [
                'input' => [
                    'María',
                    'José',
                    'García',
                    'López',
                ],
                'expectation' => [
                    'María',
                    'José',
                    new Lastname('García'),
                    new Lastname('López'),
                ],
                'arguments' => [
                    false,
                    2,
                ],
            ],
            'two lastname parts keep one part for firstname' => [
                'input' => [
                    'María',
                    'García',
                ],
                'expectation' => [
                    'María',
                    new Lastname('García'),
                ],
                'arguments' => [
                    false,
                    2,
                ],
            ],
            'two lastname parts in lastname segment' => [
                'input' => [
                    'García',
                    'López',
                ],
                'expectation' => [
                    new Lastname('García'),
                    new Lastname('López'),
                ],
                'arguments' => [
                    true,
                    2,
                ],
            ],
            'lastname with prefix is complete' => [
                'input' => [
                    'Juan',
                    'Pablo',
                    'de',
                    'Vega',
                ],
                'expectation' => [
                    'Juan',
                    'Pablo',
                    new LastnamePrefix('de', 'de'),
                    new Lastname('Vega'),
                ],
                'arguments' => [
                    false,
                    2,
                ],
            ],
        ];
    }

    protected function getMapper($matchSingle = false, $partCount = 1)
    {
        $english = new English();

        return new LastnameMapper($english->getLastnamePrefixes(), $matchSingle, $partCount);
    }
}
