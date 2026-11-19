<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser;

use InterflonXebia\NameParser\Language\English;
use InterflonXebia\NameParser\Mapper\AbstractMapper;
use InterflonXebia\NameParser\Mapper\FirstnameMapper;
use InterflonXebia\NameParser\Mapper\InitialMapper;
use InterflonXebia\NameParser\Mapper\LastnameMapper;
use InterflonXebia\NameParser\Mapper\MiddlenameMapper;
use InterflonXebia\NameParser\Mapper\NicknameMapper;
use InterflonXebia\NameParser\Mapper\SalutationMapper;
use InterflonXebia\NameParser\Mapper\SuffixMapper;

class Parser
{
    protected string $whitespace = " \r\n\t";

    /**
     * @var AbstractMapper[]
     */
    protected array $mappers = [];

    /**
     * whether the mappers were set explicitly via setMappers()
     * (only the default mappers are rebuilt when the configuration changes)
     */
    private bool $hasCustomMappers = false;

    /**
     * creates languages for addLanguageByCountryCode(), defaults to a LanguageRegistry
     */
    protected ?LanguageProviderInterface $languageProvider = null;

    /**
     * @var LanguageInterface[]
     */
    protected array $languages = [];

    /**
     * @var array<string, string> opening => closing delimiter
     */
    protected array $nicknameDelimiters = [];

    protected int $maxSalutationIndex = 0;

    protected int $maxCombinedInitials = 2;

    /**
     * @param LanguageInterface[] $languages defaults to English
     * @param LanguageProviderInterface|null $languageProvider used by addLanguageByCountryCode(),
     *                                                         defaults to a LanguageRegistry with the built-in languages
     */
    public function __construct(array $languages = [], ?LanguageProviderInterface $languageProvider = null)
    {
        $this->languages = $languages ?: [new English()];
        $this->languageProvider = $languageProvider;
    }

    /**
     * split full names into the following parts:
     * - prefix / salutation  (Mr., Mrs., etc)
     * - given name / first name
     * - middle initials
     * - surname / last name
     * - suffix (II, Phd, Jr, etc)
     */
    public function parse(string $name): Name
    {
        $name = $this->normalize($name);

        $segments = explode(',', $name);

        if (1 < count($segments)) {
            return $this->parseSplitName($segments[0], $segments[1], $segments[2] ?? '');
        }

        $parts = explode(' ', $name);

        foreach ($this->getMappers() as $mapper) {
            $parts = $mapper->map($parts);
        }

        return new Name($parts);
    }

    /**
     * handles split-parsing of comma-separated name parts
     *
     * @param string $first the name part left of the first comma
     * @param string $second the name part right of the first comma
     * @param string $third the name part right of the second comma
     */
    protected function parseSplitName(string $first, string $second, string $third): Name
    {
        $parts = array_merge(
            $this->getFirstSegmentParser()->parse($first)->getParts(),
            $this->getSecondSegmentParser()->parse($second)->getParts(),
            $this->getThirdSegmentParser()->parse($third)->getParts()
        );

        return new Name($parts);
    }

    protected function getFirstSegmentParser(): Parser
    {
        return (new Parser())->setMappers([
            new SalutationMapper($this->getSalutations(), $this->getMaxSalutationIndex()),
            new SuffixMapper($this->getSuffixes(), false, 2),
            new LastnameMapper($this->getPrefixes(), true, $this->getLastnamePartCount()),
            new FirstnameMapper($this->hasCompoundFirstname()),
            new MiddlenameMapper(),
        ]);
    }

    protected function getSecondSegmentParser(): Parser
    {
        return (new Parser())->setMappers([
            new SalutationMapper($this->getSalutations(), $this->getMaxSalutationIndex()),
            new SuffixMapper($this->getSuffixes(), true, 1),
            new NicknameMapper($this->getNicknameDelimiters()),
            new InitialMapper($this->getMaxCombinedInitials(), true),
            new FirstnameMapper($this->hasCompoundFirstname()),
            new MiddlenameMapper(true),
        ]);
    }

    protected function getThirdSegmentParser(): Parser
    {
        return (new Parser())->setMappers([
            new SuffixMapper($this->getSuffixes(), true, 0),
        ]);
    }

    /**
     * get the mappers for this parser
     *
     * @return AbstractMapper[]
     */
    public function getMappers(): array
    {
        if (empty($this->mappers)) {
            $this->mappers = [
                new NicknameMapper($this->getNicknameDelimiters()),
                new SalutationMapper($this->getSalutations(), $this->getMaxSalutationIndex()),
                new SuffixMapper($this->getSuffixes()),
                new InitialMapper($this->getMaxCombinedInitials()),
                new LastnameMapper($this->getPrefixes(), false, $this->getLastnamePartCount()),
                new FirstnameMapper($this->hasCompoundFirstname()),
                new MiddlenameMapper(),
            ];
        }

        return $this->mappers;
    }

    /**
     * set the mappers for this parser
     *
     * @param AbstractMapper[] $mappers
     */
    public function setMappers(array $mappers): static
    {
        $this->mappers = $mappers;
        $this->hasCustomMappers = !empty($mappers);

        return $this;
    }

    /**
     * drop the cached default mappers, so they are rebuilt with the
     * current configuration on the next parse() call
     */
    private function resetDefaultMappers(): void
    {
        if (!$this->hasCustomMappers) {
            $this->mappers = [];
        }
    }

    /**
     * normalize the name: trim and collapse whitespace into single spaces
     */
    protected function normalize(string $name): string
    {
        $whitespace = preg_quote($this->getWhitespace(), '/');

        return preg_replace('/[' . $whitespace . ']+/', ' ', trim($name));
    }

    /**
     * get a string of characters that are supposed to be treated as whitespace
     */
    public function getWhitespace(): string
    {
        return $this->whitespace;
    }

    /**
     * set the string of characters that are supposed to be treated as whitespace
     */
    public function setWhitespace(string $whitespace): static
    {
        $this->whitespace = $whitespace;

        return $this;
    }

    /**
     * the name structure is defined by the first (primary) language
     */
    protected function getNameStructure(): ?NameStructureInterface
    {
        $primary = $this->languages[0] ?? null;

        return $primary instanceof NameStructureInterface ? $primary : null;
    }

    protected function getLastnamePartCount(): int
    {
        return $this->getNameStructure()?->getLastnamePartCount() ?? 1;
    }

    protected function hasCompoundFirstname(): bool
    {
        return $this->getNameStructure()?->hasCompoundFirstname() ?? false;
    }

    /**
     * @return array<string, string>
     */
    protected function getPrefixes(): array
    {
        $prefixes = [];

        foreach ($this->languages as $language) {
            $prefixes += $language->getLastnamePrefixes();
        }

        return $prefixes;
    }

    /**
     * @return array<string, string>
     */
    protected function getSuffixes(): array
    {
        $suffixes = [];

        foreach ($this->languages as $language) {
            $suffixes += $language->getSuffixes();
        }

        return $suffixes;
    }

    /**
     * @return array<string, string>
     */
    protected function getSalutations(): array
    {
        $salutations = [];

        foreach ($this->languages as $language) {
            $salutations += $language->getSalutations();
        }

        return $salutations;
    }

    /**
     * @return array<string, string>
     */
    public function getNicknameDelimiters(): array
    {
        return $this->nicknameDelimiters;
    }

    /**
     * @param array<string, string> $nicknameDelimiters opening => closing delimiter
     */
    public function setNicknameDelimiters(array $nicknameDelimiters): static
    {
        $this->nicknameDelimiters = $nicknameDelimiters;
        $this->resetDefaultMappers();

        return $this;
    }

    public function getMaxSalutationIndex(): int
    {
        return $this->maxSalutationIndex;
    }

    public function setMaxSalutationIndex(int $maxSalutationIndex): static
    {
        $this->maxSalutationIndex = $maxSalutationIndex;
        $this->resetDefaultMappers();

        return $this;
    }

    public function getMaxCombinedInitials(): int
    {
        return $this->maxCombinedInitials;
    }

    public function setMaxCombinedInitials(int $maxCombinedInitials): static
    {
        $this->maxCombinedInitials = $maxCombinedInitials;
        $this->resetDefaultMappers();

        return $this;
    }

    /**
     * @return LanguageInterface[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * replace all languages
     *
     * @param LanguageInterface[] $languages
     */
    public function setLanguages(array $languages): static
    {
        $this->languages = $languages;
        $this->resetDefaultMappers();

        return $this;
    }

    /**
     * add a language; languages added earlier take precedence
     * when they define the same salutation, suffix or prefix
     */
    public function addLanguage(LanguageInterface $language): static
    {
        $this->languages[] = $language;
        $this->resetDefaultMappers();

        return $this;
    }

    /**
     * add the language for the given country code, created by the language provider
     *
     * Falls back to English if the language provider does not support the
     * country code. A language of the same class that was added before is
     * not added a second time.
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code, case-insensitive (e.g. "NL", "es")
     */
    public function addLanguageByCountryCode(string $countryCode): static
    {
        $provider = $this->getLanguageProvider();

        $language = $provider->supports($countryCode)
            ? $provider->create($countryCode)
            : new English();

        if ($this->hasLanguage($language::class)) {
            return $this;
        }

        return $this->addLanguage($language);
    }

    /**
     * replace all languages with the ones for the given country codes
     *
     * The first country code becomes the primary language, which also defines
     * the name structure (e.g. two surnames for "ES"). Like addLanguageByCountryCode(),
     * unsupported country codes fall back to English and every language is added once.
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code of the primary language, case-insensitive
     * @param string ...$additionalCountryCodes country codes of fallback languages, in order of precedence
     */
    public function setLanguagesByCountryCode(string $countryCode, string ...$additionalCountryCodes): static
    {
        $this->setLanguages([]);

        foreach ([$countryCode, ...$additionalCountryCodes] as $code) {
            $this->addLanguageByCountryCode($code);
        }

        return $this;
    }

    /**
     * check if a language of the given class was added to the parser
     *
     * @param class-string<LanguageInterface> $languageClass
     */
    public function hasLanguage(string $languageClass): bool
    {
        foreach ($this->languages as $language) {
            if ($language::class === $languageClass) {
                return true;
            }
        }

        return false;
    }

    public function getLanguageProvider(): LanguageProviderInterface
    {
        return $this->languageProvider ??= new LanguageRegistry();
    }

    public function setLanguageProvider(LanguageProviderInterface $languageProvider): static
    {
        $this->languageProvider = $languageProvider;

        return $this;
    }
}
