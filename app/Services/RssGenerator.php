<?php
namespace App\Services;

class RssGenerator
{
    private $entries = [];

    public function getHeader()
    {
        $url = \Config::get('app.url');

        return <<<EOT
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">

<channel>
  <title>Trumpt</title>
  <link>$url</link>
  <description>All tool to keep track on Donald Trump's news and tweets.</description>
EOT;
    }

    public function getFooter()
    {
        return "</channel>\n</rss>\n";
    }

    public function setEntries($entries)
    {
        $this->entries = $entries;
    }

    public function getContent()
    {
        $xml = '';
        foreach ($this->entries as $entry) {
            $xml .= <<<EOT
  <item>
    <title>{$entry['title']}</title>
    <link>{$entry['link']}</link>
    <description>{$entry['description']}</description>
  </item>
EOT;
        }

        return $xml;
    }

    public function toXml()
    {
        return $this->getHeader() . $this->getContent() . $this->getFooter();
    }
}
