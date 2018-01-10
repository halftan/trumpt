<?php
namespace App\Services;

use Thujohn\Twitter\Facades\Twitter;

class BotService
{
    public function tweetArticle($article)
    {
        $text = $article['headline'] . '…' . $article['url'];
        return Twitter::postTweet([
            'status' => $text,
            'format' => 'json',
        ]);
    }

    public function retweet($tweetId)
    {
        try {
            return Twitter::postRt($tweetId);
        } catch (\RuntimeException $e) {
            // already rt-ed, ignore the exception
            return;
        }
    }
}
