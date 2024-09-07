<?php

namespace App\Providers;

use App\Articles\ArticlesRepository;
use App\Articles\ElasticsearchRepository;
use App\Articles\EloquentSearchRepository;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticlesRepository::class, function ($app){
            if (!config('services.search.enabled')) {
                return new EloquentSearchRepository();
            }
            return new ElasticsearchRepository(
                $app->make(Client::class)
            );
        });
        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            $config = $app['config']->get('services.search');
            $clientBuilder = ClientBuilder::create()
                ->setHosts($config['hosts']);

            // Добавление аутентификации, если она включена
            if (!empty($config['username']) && !empty($config['password'])) {
                $clientBuilder->setBasicAuthentication($config['username'], $config['password']);
            }

            return $clientBuilder->build();
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
