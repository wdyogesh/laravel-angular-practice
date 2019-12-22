<?php
namespace App\Repository;

interface RepositoryInterface
{
    public function getData($conditions, $method, $withArr = []);

    public function createUpdateData($conditions, $parameters);

    public function getCount($parameters);

    public function getValue($conditions, $field);

    public function pluckValue($conditions, $field);
}