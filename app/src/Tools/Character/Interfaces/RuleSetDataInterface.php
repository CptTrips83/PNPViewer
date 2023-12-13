<?php

namespace App\Tools\Character\Interfaces;

interface RuleSetDataInterface
{
    public function getData(int $id) : array;
    public function saveData(array $data) : void;
}