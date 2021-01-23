<?php

namespace Ayesh\InstagramDownload\Tests;

use Ayesh\InstagramDownload\InstagramDownload;
use PHPUnit\Framework\TestCase;

class UrlFetcherTest extends TestCase {
  public function getDownloadUrls() {
    return [
      ['https://www.instagram.com/p/BYJNGnVAF-Q/', 'image'],
      ['https://www.instagram.com/p/BYI48JxniO3/', 'video'],
      ['https://www.instagram.com/tv/BpzrDmjnlTm', 'video'],
    ];
  }

  public function getErrorDownloadUrls() {
    return [
      ['https://www.instagram.com/p/dsaqewds1/'],
    ];
  }

  /**
   * @dataProvider getDownloadUrls
   *
   * @param string $url
   * @param string $type
   */
  public function testDownloaderFetcher(string $url, string $type) {
    $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0';
    $dl = new InstagramDownload($url);
    $this->assertEquals($type, $dl->getType());
    $url = $dl->getDownloadUrl(false);
    $dl_url = $dl->getDownloadUrl(true);

    $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
    $this->assertContains('dl=1', $dl_url);
    $this->assertNotContains('dl=1', $url);

    $file = file_get_contents($url);
    //var_dump($file);
    $this->assertNotFalse($file);
  }

  /**
   * @dataProvider getErrorDownloadUrls
   *
   * @param string $url
   */
  public function testInvalidDownloadUrls(string $url) {
    $dl = new InstagramDownload($url);
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Could not fetch data.');
    $url = $dl->getDownloadUrl();
  }
}
