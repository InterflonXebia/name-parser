<?php

namespace InterflonXebia\NameParser\Mapper;

use InterflonXebia\NameParser\Language\English;
use InterflonXebia\NameParser\Part\Salutation;
use InterflonXebia\NameParser\Part\Firstname;
use InterflonXebia\NameParser\Part\Lastname;

class SalutationMapperTest extends AbstractMapperTestCase
{
    /**
     * @return array
     */
    public static function provider(): array
    {
        return [
            [
                'input' => [
                    'Mr.',
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr.', 'Mr.'),
                    'Pan',
                ],
            ],
            [
                'input' => [
                    'Mr',
                    'Peter',
                    'Pan',
                ],
                'expectation' => [
                    new Salutation('Mr', 'Mr.'),
                    'Peter',
                    'Pan',
                ],
            ],
            [
                'input' => [
                    'Mr',
                    new Firstname('James'),
                    'Miss',
                ],
                'expectation' => [
                    new Salutation('Mr', 'Mr.'),
                    new Firstname('James'),
                    'Miss',
                ],
            ],
        ];
    }

    protected function getMapper()
    {
        $english = new English();

        return new SalutationMapper($english->getSalutations());
    }
}
