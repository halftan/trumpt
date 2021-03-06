<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class CrawlCnn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:cnn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl CNN for Donuts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // fetch articles from CNN
        $client = new \GuzzleHttp\Client();
        /** @var $resp \Psr\Http\Message\ResponseInterface */
        $resp = $client->get('https://search.api.cnn.io/content', [
            'query' => [
                'size' => 25,
                'q' => 'Donald Trump',
                'category' => 'us,politics,world,opinion',
                'type' => 'article',
            ],
        ]);
        if ($resp->getStatusCode() != 200) {
            $this->error('Error when requesting CNN API. Got status ' . $resp->getStatusCode());
            return;
        }
        $cnnNews = data_get(json_decode($resp->getBody(), true), 'result', []);

        if (empty($cnnNews)) {
            $this->error('Result is empty.');
            return;
        }

        // process the articles
        $currentIds = collect(Redis::lRange('trumpt:cnn-articles', 0, -1))->map(function ($item) {
            return array_get(json_decode($item, true), '_id');
        })->filter();

        // save to redis
        while (($article = array_pop($cnnNews))) {
            if ($currentIds->search($article['_id']) !== false) {
                // already exists
                continue;
            }
            Redis::lPush('trumpt:cnn-articles', json_encode($article));
            if (app()->environment() != 'testing') {
                // tweet bot's action
                $bot = resolve('botService');
                $bot->tweetArticle($article);
            }
        }
        Redis::lTrim('trumpt:cnn-articles', 0, 24);
    }
}
