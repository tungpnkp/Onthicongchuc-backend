<?php


namespace App\Services;

interface AppService
{
    public function pluck($column, $key = null);

    public function sync($id, $relation, $attributes, $detaching = true);

    public function whereHas($relation, $closure);

    public function syncWithoutDetaching($id, $relation, $attributes);

    public function all($columns = ['*']);

    public function paginate($limit = null, $columns = ['*']);

    public function simplePaginate($limit = null, $columns = ['*']);

    public function find($id, $columns = ['*']);

    public function findByField($field, $value, $columns = ['*']);

    public function findWhere(array $where, $columns = ['*']);

    public function create(array $attributes);

    public function update(array $attributes, $id);

    public function updateOrCreate(array $attributes, array $values = []);

    public function delete($id);

    public function orderBy($column, $direction = 'asc');

    public function with($relations);

    public function firstOrNew(array $attributes = []);

    public function firstOrCreate(array $attributes = []);
}
