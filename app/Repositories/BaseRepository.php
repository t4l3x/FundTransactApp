<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getById(string $id): ?Model
    {
        return $this->model->find($id);
    }

}
