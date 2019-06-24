<?php
namespace App\Contracts;

interface ModelBehavior
{
    public function create(array $items);
    public function find(int $id, array $columns = []);
    public function update();
    public function delete();
    public function get(array $columns = ['*']);
    public function getOne(array $columns = ['*']);
}