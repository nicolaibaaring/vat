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
    public ?string $requestIdentifier = null;

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

    public static function fromViesApprox($result)
    {
        return new static(
            $result->valid,
            $result->countryCode,
            $result->vatNumber,
            $result->traderName ?? '',
            str_replace('"', '', $result->traderAddress ?? ''),
            DateTime::createFromFormat('!Y-m-dP', $result->requestDate),
            $result->requestIdentifier
        );
    }

    public function __construct(
        bool $isValid,
        string $countryCode,
        string $vatNumber,
        string $name,
        string $address,
        DateTime $requestedAt,
        ?string $requestIdentifier = null
    ) {
        $this->isValid = $isValid;
        $this->countryCode = $countryCode;
        $this->vatNumber = $vatNumber;
        $this->name = $name;
        $this->address = $address;
        $this->requestedAt = $requestedAt;
        $this->requestIdentifier = $requestIdentifier;
    }
}
