<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ToolsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // If the Google Custom Search API is not enabled
        if (!config('settings.gcs')) {
            // Update the Tools list
            config(['tools' => array_filter(config('tools'), function ($item) {
                if (!in_array($item['route'], ['tools.serp_checker', 'tools.indexed_pages_checker'])) {
                    return $item;
                }
                return false;
            })]);
        }

        // If the KeywordsEverywhere API is not enabled
        if (!config('settings.ke')) {
            // Update the Tools list
            config(['tools' => array_filter(config('tools'), function ($item) {
                if ($item['route'] !== 'tools.keyword_research') {
                    return $item;
                }
                return false;
            })]);
        }
    }
}
