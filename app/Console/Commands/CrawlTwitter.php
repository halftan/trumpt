<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
    protected $description = 'Command description';

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
        //
        $tweets = json_decode(\Twitter::getUserTimeline([
            'screen_name' => 'realDonaldTrump',
            'count' => 25,
            'format' => 'json',
        ]), true);

        dd($tweets);
    }
}
