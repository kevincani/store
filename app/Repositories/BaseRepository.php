<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    public function getAll()
    {
        return $this->query()->get();
    }

    public function create(array $input)
    {
        return $this->query()->create($input);
    }

    public function update(Model $model, array $input)
    {
        return $model->update($input);
    }

    public function delete(Model $model)
    {
        return $model->delete();
    }

    /**
     * Get Paginated.
     *
     * @param $per_page
     * @param string $active
     * @param string $order_by
     * @param string $sort
     *
     * @return mixed
     */
    public function getPaginated($per_page, $active = '', $order_by = 'id', $sort = 'asc')
    {
        if ($active) {
            return $this->query()->where('status', $active)
                ->orderBy($order_by, $sort)
                ->paginate($per_page);
        }

        return $this->query()->orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    public function getCount()
    {
        return $this->query()->count();
    }

    public function find($id)
    {
        return $this->query()->findOrFail($id);
    }

    public function query()
    {
        return call_user_func(static::MODEL . '::query');
    }


}
