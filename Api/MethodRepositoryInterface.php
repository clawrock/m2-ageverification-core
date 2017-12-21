<?php

namespace ClawRock\AgeVerification\Api;

use ClawRock\AgeVerification\Api\Data\MethodInterface;

interface MethodRepositoryInterface
{
    /**
     * Returns all active methods.
     *
     * @return MethodInterface[]
     */
    public function getAll(): array;

    /**
     * Gets method by code.
     *
     * @param string $code
     *
     * @throws \ClawRock\AgeVerification\Exception\MissingMethodException
     *
     * @return MethodInterface
     */
    public function getByCode(string $code): MethodInterface;
}
