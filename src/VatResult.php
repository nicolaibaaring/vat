<?php
declare(strict_types=1);

namespace Ibericode\Vat;

use DateTime;

final class VatResult
{
    public bool $isValid;
    public string $countryCode;
    public string $vatNumber;
    public string $name;
    public string $address;
    public DateTime $requestedAt;

    public static function fromVies($result)
    {
        return new static(
            $result->valid,
            $result->countryCode,
            $result->vatNumber,
            $result->name,
            str_replace('"', '', $result->address),
            DateTime::createFromFormat('!Y-m-dP', $result->requestDate)
        );
    }

    public function __construct(
        bool $isValid,
        string $countryCode,
        string $vatNumber,
        string $name,
        string $address,
        DateTime $requestedAt
    ) {
        $this->isValid = $isValid;
        $this->countryCode = $countryCode;
        $this->vatNumber = $vatNumber;
        $this->name = $name;
        $this->address = $address;
        $this->requestedAt = $requestedAt;
    }
}
