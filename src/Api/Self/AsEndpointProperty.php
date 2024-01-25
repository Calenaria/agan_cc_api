<?php

namespace App\Api\Self;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsEndpointProperty
{
    public function __construct(public bool $required = false) {}
}