<?php

namespace App\Providers;

use App\Services\Icandoit\HttpIcandoitConnector;
use App\Services\Icandoit\IcandoitConnector;
use App\Services\Icandoit\NullIcandoitConnector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Chọn driver kết nối ICANDOIT theo cấu hình. Mặc định là 'null'
        // (chưa có API thật) — đổi sang 'http' khi ICANDOIT cung cấp endpoint.
        $this->app->bind(IcandoitConnector::class, function () {
            return match (config('icandoit.driver', 'null')) {
                'http' => new HttpIcandoitConnector(config('icandoit.http', [])),
                default => new NullIcandoitConnector,
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
