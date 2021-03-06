<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $articles = collect(Redis::lRange('trumpt:cnn-articles', 0, 24))->map(function ($item) {
        return json_decode($item, true);
    });
    $tweets = collect(Redis::lRange('trumpt:tweets', 0, 24))->map(function ($item) {
        return json_decode($item, true);
    });
    return view('trumpt', compact('articles', 'tweets'));
});

Route::get('/tweet/{id}', function ($tweetId) {
    if (Cache::has($tweetId)) {
        $resp = Cache::get($tweetId);
    } else {
        try {
            $url = 'https://twitter.com/Interior/status/' . $tweetId;
            $resp = Twitter::getOembed([
                'url' => $url,
                'hide_thread' => 1,
                'omit_script' => 1,
            ]);
            Cache::put($tweetId, $resp, 1440);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => 1], $e->getCode());
        }
    }

    return response()->json($resp);
});

Route::get('/article/{id}', function ($articleId) {
    $articles = collect(Redis::lRange('trumpt:cnn-articles', 0, 24))->map(function ($item) {
        return json_decode($item, true);
    });
    $article = $articles->where('_id', $articleId)->first();

    return response()->json($article);
});

Route::get('/feed', function (Request $request) {
    if (Cache::has('feed')) {
        $entries = Cache::get('feed');
    } else {
        $articles = collect(Redis::lRange('trumpt:cnn-articles', 0, 24))->map(function ($item) {
            return json_decode($item, true);
        })->map(function ($item) {
            return [
                'title' => $item['url'],
                'link' => $item['headline'],
                'description' => $item['body'],
            ];
        });
        $tweets = collect(Redis::lRange('trumpt:tweets', 0, 24))->map(function ($item) {
            return json_decode($item, true);
        })->map(function ($item) {
            return [
                'title' => $item['text'],
                'link' => array_get($item, 'entities.urls.expanded_url'),
                'description' => $item['text'],
            ];
        });
        $entries = $articles->merge($tweets);
        Cache::put('feed', $entries, 5);
    }


    $rssGenerator = resolve('rssGenerator');
    $rssGenerator->setEntries($entries);

    return response($rssGenerator->toXml(), 200)
            ->header('Content-Type', 'application/rss+xml');

});
