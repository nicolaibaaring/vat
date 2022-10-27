<?php
declare(strict_types=1);

namespace Ibericode\Vat\Vies;

use Ibericode\Vat\VatResult;
use SoapClient;
use SoapFault;

class Client
{

    /**
     * @const string
     */
    const URL = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * @var SoapClient
     */
    private $client;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * Client constructor.
     *
     * @param int $timeout How long should we wait before aborting the request to VIES?
     */
    public function __construct(int $timeout = 10)
    {
        $this->timeout = $timeout;
    }

    /**
     * @param string $countryCode
     * @param string $vatNumber
     *
     * @return bool
     *
     * @throws ViesException
     */
    public function checkVat(string $countryCode, string $vatNumber) : bool
    {
        $response = $this->performCheckVatRequest($countryCode, $vatNumber);

        return (bool) $response->valid;
    }

    /**
     * @param string $countryCode
     * @param string $vatNumber
     *
     * @return VatResult
     *
     * @throws ViesException
     */
    public function fetchValidation(string $countryCode, string $vatNumber) : VatResult
    {
        $response = $this->performCheckVatRequest($countryCode, $vatNumber);

        return VatResult::fromVies($response);
    }

    protected function performCheckVatRequest(string $countryCode, string $vatNumber): object
    {
        try {
            $response = $this->getClient()->checkVat(
                array(
                    'countryCode' => $countryCode,
                    'vatNumber' => $vatNumber
                )
            );
        } catch (SoapFault $e) {
            throw new ViesException($e->getMessage(), $e->getCode());
        }

        return $response;
    }

    /**
     * @return SoapClient
     */
    protected function getClient() : SoapClient
    {
        if ($this->client === null) {
            $this->client = new SoapClient(self::URL, ['connection_timeout' => $this->timeout]);
        }

        return $this->client;
    }
}
