<?php

declare(strict_types=1);

namespace InterflonXebia\NameParser;

use InvalidArgumentException;
use InterflonXebia\NameParser\Language\Dutch;
use InterflonXebia\NameParser\Language\English;
use InterflonXebia\NameParser\Language\German;
use InterflonXebia\NameParser\Language\Spanish;

/**
 * registry of language definitions, keyed by ISO 3166-1 alpha-2 country code
 */
final class LanguageRegistry implements LanguageProviderInterface
{
    /**
     * built-in languages
     *
     * Countries with several official languages are mapped to the language
     * most commonly used for names there.
     *
     * @var array<string, class-string<LanguageInterface>>
     */
    public const DEFAULT_LANGUAGES = [
        // English
        'GB' => English::class,
        'UK' => English::class, // common alias, reserved in ISO 3166-1 for the United Kingdom
        'US' => English::class,
        'IE' => English::class,
        'AU' => English::class,
        'NZ' => English::class,
        'CA' => English::class,
        'ZA' => English::class,
        // German
        'DE' => German::class,
        'AT' => German::class,
        'CH' => German::class,
        'LI' => German::class,
        // Dutch
        'NL' => Dutch::class,
        'BE' => Dutch::class,
        'SR' => Dutch::class,
        // Spanish
        'ES' => Spanish::class,
        'MX' => Spanish::class,
        'AR' => Spanish::class,
        'BO' => Spanish::class,
        'CL' => Spanish::class,
        'CO' => Spanish::class,
        'CR' => Spanish::class,
        'CU' => Spanish::class,
        'DO' => Spanish::class,
        'EC' => Spanish::class,
        'GT' => Spanish::class,
        'HN' => Spanish::class,
        'NI' => Spanish::class,
        'PA' => Spanish::class,
        'PE' => Spanish::class,
        'PR' => Spanish::class,
        'PY' => Spanish::class,
        'SV' => Spanish::class,
        'UY' => Spanish::class,
        'VE' => Spanish::class,
    ];

    /**
     * @var array<string, class-string<LanguageInterface>>
     */
    private array $languages = [];

    /**
     * @param array<string, class-string<LanguageInterface>> $languages country code => language class
     * @throws InvalidArgumentException if a country code or language class is invalid
     */
    public function __construct(array $languages = self::DEFAULT_LANGUAGES)
    {
        foreach ($languages as $countryCode => $languageClass) {
            $this->register((string) $countryCode, $languageClass);
        }
    }

    /**
     * register (or replace) the language for the given country code
     *
     * @param class-string<LanguageInterface> $languageClass
     * @throws InvalidArgumentException if the country code or language class is invalid
     */
    public function register(string $countryCode, string $languageClass): self
    {
        $key = $this->normalize($countryCode);

        if (!preg_match('/^[A-Z]{2}$/', $key)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid country code "%s", expected an ISO 3166-1 alpha-2 code (e.g. "NL")',
                $countryCode
            ));
        }

        if (!is_subclass_of($languageClass, LanguageInterface::class)) {
            throw new InvalidArgumentException(sprintf(
                'Language class "%s" must implement %s',
                $languageClass,
                LanguageInterface::class
            ));
        }

        $this->languages[$key] = $languageClass;

        return $this;
    }

    public function create(string $countryCode): LanguageInterface
    {
        $key = $this->normalize($countryCode);

        if (!isset($this->languages[$key])) {
            throw new InvalidArgumentException(sprintf(
                'No language registered for country code "%s". Supported country codes: %s',
                $countryCode,
                implode(', ', $this->getCountryCodes())
            ));
        }

        return new $this->languages[$key]();
    }

    public function supports(string $countryCode): bool
    {
        return isset($this->languages[$this->normalize($countryCode)]);
    }

    /**
     * @return string[] all country codes a language is registered for
     */
    public function getCountryCodes(): array
    {
        return array_keys($this->languages);
    }

    private function normalize(string $countryCode): string
    {
        return strtoupper(trim($countryCode));
    }
}
