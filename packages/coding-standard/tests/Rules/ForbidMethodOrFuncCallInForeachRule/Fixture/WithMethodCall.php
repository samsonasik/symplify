<?php

declare(strict_types=1);

namespace Symplify\CodingStandard\Tests\Rules\ForbidMethodOrFuncCallInForeachRule\Fixture;

class WithMethodCall
{
    public function getData()
    {
        return [];
    }

    public function execute()
    {
        foreach ($this->getData() as $key => $item) {

        }
    }

}