<?php

namespace App\Providers;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Contracts\Services\ClientServiceInterface;
use App\Contracts\Services\ContactServiceInterface;
use App\Repositories\ClientRepository;
use App\Repositories\ContactRepository;
use App\Services\ClientService;
use App\Services\ContactService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
    }
}
