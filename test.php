<?php

require_once __DIR__ . '/vendor/autoload.php';

class Province {

}

class Country {
    private $provinces = [];

    public function getProvinces(): array
    {
        return $this->provinces;
    }

    public function hasProvinces(): bool
    {
        return [] !== $this->provinces;
    }

    public function addProvince(Province $province): void
    {
        if (!$this->hasProvince($province)) {
            $this->provinces[] = $province;
        }
    }

    public function removeProvince(Province $province): void
    {
        if ($this->hasProvince($province)) {
            unset($this->provinces[array_search($province, $this->provinces, true)]);
        }
    }

    public function hasProvince(Province $province): bool
    {
        return in_array($province, $this->provinces, true);
    }
}

$reflectionExtractor = new \Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor();

/**
 * Both Symfony 4 and 5 lists two properties: provinces and province
 */
$properties = $reflectionExtractor->getProperties(Country::class);

$provincesListed = in_array('provinces', $properties, true);
echo 'Property "provinces" ' . ($provincesListed ? 'is' : 'is not') . ' listed in properties' . PHP_EOL;
if (false === $provincesListed) {
    throw new \Exception('Property "provinces" does not exist in Country class but it should');
}

$provinceListed = in_array('provinces', $properties, true);
echo 'Property "province" ' . ($provinceListed ? 'is' : 'is not') . ' listed in properties' . PHP_EOL;
if (false === $provinceListed) {
    throw new \Exception('Property "province" does not exist in Country class but it should');
}

echo PHP_EOL;

/**
 * Symfony 4:
 *  - "provinces" is readable
 *  - "province" is not readable
 *
 * Symfony 5:
 *   - "provinces" is readable
 *   - "province" is readable
 *
 * Assertions are made for Symfony 4.
 */
$provincesReadable = $reflectionExtractor->isReadable(Country::class, 'provinces');
echo 'Property "provinces" ' . ($provincesReadable ? 'is' : 'is not') . ' readable' . PHP_EOL;
if (false === $provincesReadable) {
    throw new \Exception('Property "provinces" is not readable but it should');
}

$provinceReadable = $reflectionExtractor->isReadable(Country::class, 'province');
echo 'Property "province" ' . ($provinceReadable ? 'is' : 'is not') . ' readable' . PHP_EOL;
if (true === $reflectionExtractor->isReadable(Country::class, 'province')) {
    throw new \Exception('Property "province" is readable but it should not');
}
