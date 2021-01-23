<?php

namespace Ayesh\InstagramDownload\Tests;

use Ayesh\InstagramDownload\InstagramDownload;
use PHPUnit\Framework\TestCase;

class UrlValidationTest extends TestCase {
  public function getUrlValidations_Invalid() {
    return [
      ['Invalid URL', 'foo'],
      ['Invalid URL', 'fo o'],
      ['Invalid URL', ''],
      ['Entered URL is not an instagram.com URL', 'https://facebook.com'],
      ['No image or video found in this URL', 'http://instagram.com'],
      ['No image or video found in this URL', 'https://instagram.com'],
      ['No image or video found in this URL', 'https://www.instagram.com'],
      ['No image or video found in this URL', 'https://www.instagram.com/'],
      ['No image or video found in this URL', 'https://www.instagram.com/about'],
      ['No image or video found in this URL', 'https://www.instagram.com/ayeshlive'],
      ['No image or video found in this URL', 'https://instagram.com/ayeshlive'],
      ['No image or video found in this URL', 'https://www.instagram.com/p/dsa'], // Post  argument min 5 chars.
      ['No image or video found in this URL', 'https://www.instagram.com/p/dsas'], // Post  argument min 5 chars.
      ['No image or video found in this URL', 'https://www.instagram.com/x/Bu1H3GSgR7o/?utm_source=p/dsas12345'], // p/dsas12345 pattern in query.
    ];
  }

  public function getUrlValidations_Valid() {
    return [
      ['https://www.instagram.com/p/dsas1'],
      ['http://instagram.com/p/dsas1'],
      ['http://www.instagram.com/p/dsas1'],
      ['https://www.instagram.com/p/dsas1'],
      ['https://instagram.com/p/dsadqdwq?taken-by=23'],
      ['https://instagram.com/p/dsadqdwq#dsadsa'],
      ['https://instagram.com/p/dsadqdwq?taken-by=23#sadsa'],
      ['https://www.instagram.com/p/BYJNGnVAF-Q/'],
      ['https://www.instagram.com/tv/BpzrDmjnlTm/'],
      ['https://www.instagram.com/tv/BpzrDmjnlTm'],
    ];
  }

  /**
   * @dataProvider getUrlValidations_Invalid
   */
  public function testUrlValidationFromDataStore(string $exception_message, string $source_url) {
    $this->expectExceptionMessage(\InvalidArgumentException::class);
    $this->expectExceptionMessage($exception_message);
    new InstagramDownload($source_url);
  }

  /**
   * @dataProvider getUrlValidations_Valid
   */
  public function testUrlValidationFromValidDataStore(string $url) {
    $dl = new InstagramDownload($url);
    $this->assertInstanceOf(InstagramDownload::class, $dl);
  }
}
