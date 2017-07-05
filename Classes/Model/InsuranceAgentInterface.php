<?php
namespace Maschinenraum\InsuranceCreditCheck\Model;

interface InsuranceAgentInterface
{

    public function getId(): string;

    public function getName(): string;

    public function getPassword(): string;

}
