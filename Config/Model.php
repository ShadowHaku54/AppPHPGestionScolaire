<?php

namespace App\Config;

abstract class Model
{
    public abstract function toArray(): array;
    public abstract static function fromArray(array $data): Model;
    public abstract function toArrayView(): array;
    public abstract function format(string $pattern): string;
}