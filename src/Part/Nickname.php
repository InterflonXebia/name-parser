<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Part;

class Nickname extends AbstractPart
{
    /**
     * camelcase the nickname for normalization
     */
    public function normalize(): string
    {
        return $this->camelcase($this->getValue());
    }
}
