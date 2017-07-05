<?php
namespace Maschinenraum\InsuranceCreditCheck\Model;

interface InsuranceClientInterface
{

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getAdressStreet(): string;

    public function getAddressHouseNumber(): string;

    public function getAddressZip(): string;

    public function getAddressCity(): string;

    /**
     * Return 3 digits numeric country code according to ISO 3166-1
     *
     * @return int
     */
    public function getAddressCountryCode(): int;

}
