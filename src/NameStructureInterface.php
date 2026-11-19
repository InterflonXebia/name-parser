<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser;

/**
 * describes how names are structured in a language
 *
 * Optional for language definitions: the parser uses the name structure
 * of its first (primary) language if that language implements this interface,
 * otherwise the English structure (one lastname part, firstname plus middlenames).
 */
interface NameStructureInterface
{
    /**
     * number of lastname parts a name usually has, e.g. 2 for Spanish
     * names with paternal and maternal surname ("García López")
     *
     * Lastname prefixes (e.g. "de la") are not counted. At least one part
     * is always left for the firstname.
     */
    public function getLastnamePartCount(): int;

    /**
     * whether all given names form a compound firstname (e.g. "María José")
     * instead of a firstname followed by middlenames
     */
    public function hasCompoundFirstname(): bool;
}
