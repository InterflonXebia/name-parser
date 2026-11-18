<?php

namespace InterflonXebia\NameParser\Mapper;

use InterflonXebia\NameParser\Part\Salutation;
use InterflonXebia\NameParser\Part\Firstname;
use InterflonXebia\NameParser\Part\Lastname;

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
            ]
        ];
    }

    protected function getMapper()
    {
        return new FirstnameMapper();
    }
}
