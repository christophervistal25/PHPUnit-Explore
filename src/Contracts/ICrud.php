<?php
namespace App\Contracts;

interface ICrud
{
    public function create(array $items);
    public function find(int $id, array $columns = []);
    public function update();
    public function delete();
}