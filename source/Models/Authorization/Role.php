<?php


namespace Source\Models\Authorization;


use JetBrains\PhpStorm\Pure;
use Toniette\DataLayer\DataLayer;

/**
 * Class Role
 * @package Source\Models\Authorization
 */
class Role extends DataLayer
{
    /**
     * Role constructor.
     */
    #[Pure] public function __construct()
    {
        parent::__construct("roles", ["name"]);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}