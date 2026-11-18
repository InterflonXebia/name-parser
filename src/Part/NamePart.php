<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Part;

abstract class NamePart extends AbstractPart
{
    /**
     * camelcase the lastname
     */
    public function normalize(): string
    {
        return $this->camelcase($this->getValue());
    }
}
