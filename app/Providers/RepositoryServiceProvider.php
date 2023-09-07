<?php

namespace App\Providers;

use App\Repositories\AddressRepository;
use App\Repositories\AddressRepositoryInterface;
use App\Repositories\BusinessTypeRepository;
use App\Repositories\BusinessTypeRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\ContactRepository;
use App\Repositories\ContactRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\DocumentRepository;
use App\Repositories\DocumentRepositoryInterface;
use App\Repositories\InvoiceRepository;
use App\Repositories\InvoiceRepositoryInterface;
use App\Repositories\OpportunityRepository;
use App\Repositories\OpportunityRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\SalesforceOrderItemRepository;
use App\Repositories\SalesforceOrderItemRepositoryInterface;
use App\Repositories\PackageRepository;
use App\Repositories\PackageRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\RetailerRepository;
use App\Repositories\RetailerRepositoryInterface;
use App\Repositories\SkuRepository;
use App\Repositories\SkuRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\SupportCaseRepository;
use App\Repositories\SupportCaseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
