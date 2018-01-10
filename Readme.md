# About trumpt

This is a web crawler + a simple website that displays what it has crawled, that crawls Donald Trump's news and tweets.

## Features

### CNN news about Trump

News are updated every 15 minutes. Check `App\Console\Commands\CrawlCnn` and `App\Console\Kernel` for detail.

### Tweets from Trump

Tweets are update every 5 minutes. Check `App\Console\Commands\CrawlTwitter` and `App\Console\Kernel` for detail.

### RSS feeds

You can subscribe trumpt's rss feed by clicking on `#RSS` at the right-most of the navbar.

### @trumptBot

There's a twitter bot that retweets Mr.Trump's every single tweet and posts status about his news on CNN. Please check [@trumptBot](https://twitter.com/trumptBot)

## How to run this site

Given that you have redis up and running at `localhost:6379`.

Use [Composer](https://getcomposer.org):

```bash
$ composer install
```

and [yarn](https://yarnpkg.com):

```bash
$ yarn install && yarn run prod
```

then PHP (version >= 7):

```bash
$ php artisan serve
```

Now you have the site up.

### Scheduled crawler

Add the following Cron entry:

```
* * * * * php /path-to-trumpt-project-directory/artisan schedule:run >> /dev/null 2>&1
```
