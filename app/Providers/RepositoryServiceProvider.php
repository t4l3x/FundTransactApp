<?php
declare(strict_types=1);

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\EloquentRepositoryInterface;
use App\Repositories\Contracts\IAccountRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(IAccountRepository::class, AccountRepository::class);
    }
}
