<?php

namespace Maschinenraum\InsuranceCreditCheck;

use Maschinenraum\InsuranceCreditCheck\Model\InsuranceAgentInterface;
use Maschinenraum\InsuranceCreditCheck\Model\InsuranceClientInterface;

class ApiService
{

    const OPERATION_MODE_PRODUCTION = 1;

    const OPERATION_MODE_TESTING = 0;

    /**
     * @var \SoapClient
     */
    protected $soapClient;

    public function __construct(int $operationMode)
    {
        if ($this->operationMode === self::OPERATION_MODE_PRODUCTION) {
            $wsdlUrl = 'https://ws.kredit-privat-webservice.de/bonipruef/Mietkaution/2014_1/?wsdl';
        } elseif ($this->operationMode === self::OPERATION_MODE_TESTING) {
            $wsdlUrl = 'https://ws.kredit-privat-webservice.de/bonipruef_test/Mietkaution/2014_1/?wsdl';
        } else {
            throw new \InvalidArgumentException('Invalid operation mode.', 1499251600);
        }
        $this->soapClient = new \SoapClient($wsdlUrl);
    }

    public function checkCredit(InsuranceAgentInterface $insuranceAgent, InsuranceClientInterface $insuranceClient): bool
    {
        $requestHeaders = [
            'prot:Service' => [
                'name' => 'Mietkaution',
                'version' => '2014_1',
                'method' => 'pruefeBonitaet',
            ],
            'prot:Identity' => [
                'Company' => [
                    'name' => $insuranceAgent->getName(),
                    'userId' => $insuranceAgent->getId(),
                ],
            ],
            'prot:Protocol' => [
                'version' => '1.0',
            ],
        ];
        $requestParameters = [
            'Kennung' => [
                'Benutzer' => $insuranceAgent->getId(),
                'Passwort' => $insuranceAgent->getPassword(),
            ],
            'Vorname' => $insuranceClient->getFirstName(),
            'Nachname' => $insuranceClient->getLastName(),
            'Strasse' => $insuranceClient->getAdressStreet(),
            'Hausnummer' => $insuranceClient->getAddressHouseNumber(),
            'Plz' => $insuranceClient->getAddressZip(),
            'Ort' => $insuranceClient->getAddressCity(),
            'Land' => $insuranceClient->getAddressCountryCode(),
        ];
        $response = $this->soapClient->__soapCall('pruefeBonitaet', $requestParameters, null, $requestHeaders, $responseHeaders);
    }

}
