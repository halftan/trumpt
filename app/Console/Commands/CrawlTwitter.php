<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class CrawlTwitter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:twitter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl Twitter for Trumpts';

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
        // latest tweet id stored in redis
        $lastTweet = Redis::lIndex('trumpt:tweets', 0);
        if (!empty($lastTweet)) {
            $lastId = data_get(@json_decode($lastTweet, true), 'id');
        }
        // fetch tweets
        $tweets = json_decode(\Twitter::getUserTimeline([
            'screen_name' => 'realDonaldTrump',
            'count' => 25,
            'format' => 'json',
            'trim_user' => 1,
        ] + (empty($lastId) ? [] : ['since_id' => $lastId])), true);

        if (empty($tweets)) {
            $this->error('Empty tweets.');
            return;
        }

        // process tweets
        $this->info('Crawled ' . count($tweets) . ' tweets.');

        // save to redis
        while (($tt = array_pop($tweets))) {
            if (app()->environment() != 'testing') {
                // tweet bot's action
                $bot = resolve('botService');
                $bot->retweet($tt['id']);
            }
            Redis::lPush('trumpt:tweets', json_encode($tt));
        }
        Redis::lTrim('trumpt:tweets', 0, 24);
    }
}
