# About spotifyCrawler
it's a PHP library for access to spotify contents with spotify’s internal APIs . by using this library you don't need create spotify developer account and run oAuth2 proccess

## How use
you need a HTTPS connection for access to spotify's resources then you must include spotifyCrawler.php into your file and use library methods
```php
require 'spotifyCrawler.php';
```
check examples.php for testing library

## a simple request
```php
require 'spotifyCrawler.php';
$spotify = new spotifyCrawler();
$track_data = $spotify->track('SPOTIFY_TRACK_ID');
```

## spotify contents methods
```php
require 'spotifyCrawler.php';
$spotify = new spotifyCrawler();
$track_data = $spotify->track('SPOTIFY_TRACK_ID');
$album_data = $spotify->album('SPOTIFY_ALBUM_ID');
$artist_data = $spotify->artist('SPOTIFY_ARTIST_ID');
$artist_albums_data = $spotify->artistAlbums('SPOTIFY_ARTIST_ID');
```

## search into spotify
```php 
search(string $text, int $offset = 0, int $limit = 10) : array
```
```php
require 'spotifyCrawler.php';
$spotify = new spotifyCrawler();
$search_data = $spotify->search('Future');
$search_data = $spotify->search('Future',20);
$search_data = $spotify->search('Future',32,4);
```

## create client
`client_id` and it `access_token` are automatically generate
when using contents methods like `track()` , `album()` … whatever you can generate new `client_id` and it `access_token` by these methods
```php
require 'spotifyCrawler.php';
$spotify = new spotifyCrawler();
$spotify->createClient();

$client_id = $spotify->clientId;
$clien_token = $spotify->clientToken;
```

## create access token for client
```php
require 'spotifyCrawler.php';
$spotify = new spotifyCrawler();
$spotify->getAccessToken();

$client_access_token = $spotify->accessToken;
```

## change user agent
maybe spotify block your `user-agent` , for resolve this problem you can use `setUserAgent()` method and introduce new `user-agent` value
```php
require 'spotifyCrawler.php';
$spotify = new spotifyCrawler();
$spotify->setUserAgent('USER_AGENT_TEXT');
```

