<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Artisan;

class CrawlerTest extends TestCase
{
    /**
     * @group crawler
     *
     * @return void
     */
    public function testCnnCrawler()
    {
        Redis::shouldReceive('lPush');
        Redis::shouldReceive('lTrim')->once();

        Artisan::call('crawl:cnn');
    }

    /**
     * @group crawler
     *
     * @return void
     */
    public function testTwitterCrawler()
    {
        // setup last tweet
        Redis::shouldReceive('lIndex')
            ->with('trumpt:tweets', 0)
            ->andReturn('');

        // fake return data
        \Twitter::shouldReceive('getUserTimeline')
            ->once()
            ->andReturn('[1,2,3]');
        Redis::shouldReceive('lPush');
        Redis::shouldReceive('lTrim')->once();

        Artisan::call('crawl:twitter');
    }
}
