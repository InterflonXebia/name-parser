<?php

namespace InterflonXebia\NameParser\Mapper;

use InterflonXebia\NameParser\Part\Salutation;
use InterflonXebia\NameParser\Part\Firstname;
use InterflonXebia\NameParser\Part\Lastname;
use InterflonXebia\NameParser\Part\Nickname;

class FirstnameMapperTest extends AbstractMapperTestCase
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
                    new Firstname('Peter'),
                    'Pan',
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
                    new Firstname('Peter'),
                    'Pan',
                ],
            ],
            [
                'input' => [
                    new Salutation('Mr'),
                    'Peter',
                    new Lastname('Pan'),
                ],
                'expectation' => [
                    new Salutation('Mr'),
                    new Firstname('Peter'),
                    new Lastname('Pan'),
                ],
            ],
            [
                'input' => [
                    'Alfonso',
                    new Salutation('Mr'),
                ],
                'expectation' => [
                    new Firstname('Alfonso'),
                    new Salutation('Mr'),
                ]
            ],
            'compound firstname' => [
                'input' => [
                    'María',
                    'José',
                    new Lastname('García'),
                    new Lastname('López'),
                ],
                'expectation' => [
                    new Firstname('María'),
                    new Firstname('José'),
                    new Lastname('García'),
                    new Lastname('López'),
                ],
                'arguments' => [
                    true,
                ],
            ],
            'compound firstname stops at mapped part' => [
                'input' => [
                    'María',
                    new Nickname('Majo'),
                    'José',
                    new Lastname('García'),
                ],
                'expectation' => [
                    new Firstname('María'),
                    new Nickname('Majo'),
                    'José',
                    new Lastname('García'),
                ],
                'arguments' => [
                    true,
                ],
            ],
            'without compound only first given name' => [
                'input' => [
                    'María',
                    'José',
                    new Lastname('García'),
                ],
                'expectation' => [
                    new Firstname('María'),
                    'José',
                    new Lastname('García'),
                ],
            ],
        ];
    }

    protected function getMapper($compound = false)
    {
        return new FirstnameMapper($compound);
    }
}
