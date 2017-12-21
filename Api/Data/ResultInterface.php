<?php

namespace ClawRock\AgeVerification\Api\Data;

interface ResultInterface
{
    public function isAuthorized(): bool;
}
