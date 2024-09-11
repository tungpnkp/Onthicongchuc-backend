<?php


namespace App\Services;

use App\Repositories\NotificationRepositoryEloquent;
use Illuminate\Support\Facades\Auth;

class NotificationService implements AppService
{
    protected $repository;

    public function __construct(NotificationRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function pluck($column, $key = null)
    {
        return $this->repository->pluck($column, $key);
    }

    public function sync($id, $relation, $attributes, $detaching = true)
    {
        return $this->repository->sync($id, $relation, $attributes, $detaching);
    }

    public function syncWithoutDetaching($id, $relation, $attributes)
    {
        return $this->repository->syncWithoutDetaching($id, $relation, $attributes);
    }

    public function all($columns = ['*'])
    {
        return $this->repository->all($columns);
    }

    public function paginate($limit = null, $columns = ['*'])
    {
        return $this->repository->paginate($limit, $columns);
    }

    public function simplePaginate($limit = null, $columns = ['*'])
    {
        return $this->repository->simplePaginate($limit, $columns);
    }

    public function find($id, $columns = ['*'])
    {
        return $this->repository->find($id, $columns);
    }

    public function findByField($field, $value, $columns = ['*'])
    {
        return $this->repository->findByField($field, $value, $columns);
    }

    public function findWhere(array $where, $columns = ['*'])
    {
        return $this->repository->findWhere($where, $columns);
    }

    public function create(array $attributes)
    {
        return $this->repository->create($attributes);
    }

    public function update(array $attributes, $id)
    {
        return $this->repository->update($attributes, $id);
    }

    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->repository->updateOrCreate($attributes, $values);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function orderBy($column, $direction = 'asc')
    {
        return $this->repository->orderBy($column, $direction);
    }

    public function with($relations)
    {
        return $this->repository->with($relations);
    }

    public function firstOrNew(array $attributes = [])
    {
        return $this->repository->firstOrNew($attributes);
    }

    public function firstOrCreate(array $attributes = [])
    {
        return $this->repository->firstOrCreate($attributes);
    }

    public function whereHas($relation, $closure)
    {
        return $this->repository->whereHas($relation, $closure);
    }

    public function getNotificationsOfUser()
    {
        $user = Auth::user()->with('notifications')->first();
        return $user->notifications;
    }

    public function getFirstNotificationOfUser($id)
    {
        $user = Auth::user()->with('notifications')->first();
        return $user->notifications->find($id);
    }

    public function UpdateStatusPivot($id)
    {
        $notification = $this->getFirstNotificationOfUser($id);
        if (is_object($notification)) {
            if ($notification->pivot->status == 0) {
                Auth::user()->notifications()->updateExistingPivot($id, ['status' => 1]);
                return true;
            }
        }
        return false;
    }

}
