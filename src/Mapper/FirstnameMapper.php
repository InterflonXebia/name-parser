<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser\Mapper;

use InterflonXebia\NameParser\Part\AbstractPart;
use InterflonXebia\NameParser\Part\Firstname;
use InterflonXebia\NameParser\Part\Initial;
use InterflonXebia\NameParser\Part\Lastname;
use InterflonXebia\NameParser\Part\Salutation;

class FirstnameMapper extends AbstractMapper
{
    /**
     * @param bool $compound map all consecutive given names as firstname
     *                       (e.g. "María José") instead of only the first one
     */
    public function __construct(
        protected readonly bool $compound = false,
    ) {
    }

    /**
     * map firstnames in parts array
     *
     * @param array<int, AbstractPart|string> $parts the parts
     * @return array<int, AbstractPart|string> the mapped parts
     */
    public function map(array $parts): array
    {
        if (count($parts) < 2) {
            return [$this->handleSinglePart($parts[0])];
        }

        $pos = $this->findFirstnamePosition($parts);

        if (null === $pos) {
            return $parts;
        }

        $parts[$pos] = new Firstname($parts[$pos]);

        if ($this->compound) {
            $parts = $this->mapFollowingGivenNames($parts, $pos + 1);
        }

        return $parts;
    }

    protected function handleSinglePart(AbstractPart|string $part): AbstractPart
    {
        if ($part instanceof AbstractPart) {
            return $part;
        }

        return new Firstname($part);
    }

    /**
     * map the unmapped parts directly following the firstname as firstname,
     * stopping at the first already mapped part (e.g. lastname, nickname, initial)
     *
     * @param array<int, AbstractPart|string> $parts
     * @return array<int, AbstractPart|string>
     */
    protected function mapFollowingGivenNames(array $parts, int $start): array
    {
        $length = count($parts);

        for ($k = $start; $k < $length && !($parts[$k] instanceof AbstractPart); $k++) {
            $parts[$k] = new Firstname($parts[$k]);
        }

        return $parts;
    }

    /**
     * @param array<int, AbstractPart|string> $parts
     */
    protected function findFirstnamePosition(array $parts): ?int
    {
        $pos = null;

        $length = count($parts);
        $start = $this->getStartIndex($parts);

        for ($k = $start; $k < $length; $k++) {
            $part = $parts[$k];

            if ($part instanceof Lastname) {
                break;
            }

            if ($part instanceof Initial && null === $pos) {
                $pos = $k;
            }

            if ($part instanceof AbstractPart) {
                continue;
            }

            return $k;
        }

        return $pos;
    }

    /**
     * @param array<int, AbstractPart|string> $parts
     */
    protected function getStartIndex(array $parts): int
    {
        $index = $this->findFirstMapped(Salutation::class, $parts);

        if (false === $index) {
            return 0;
        }

        if ($index === count($parts) - 1) {
            return 0;
        }

        return $index + 1;
    }
}
