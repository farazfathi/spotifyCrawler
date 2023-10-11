<?php
class spotifyCrawler
{
    private $userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36";
    private $clientId = null;
    private $clientToken = null;
    private $accessToken = null;
    private $trachSh256Hash = 'e101aead6d78faa11d75bec5e36385a07b2f1c4a0420932d374d89ee17c70dd6';
    private $albumSh256Hash = '46ae954ef2d2fe7732b4b2b4022157b2e18b7ea84f70591ceb164e4de1b5d5d3';
    private $artistSh256Hash = '35648a112beb1794e39ab931365f6ae4a8d45e65396d641eeda94e4003d41497';
    private $artistAlbumsSh256Hash = '983072ae655f5de212747a41be0d7c4cc49559aa9fe8e32836369b6f130cac33';
    private $searchSh256Hash = 'e39c543b006fc2a10d30d8e453e4acc0ec8cea8e851b96a6a37b9b6137742ecd';
    private function api($operationName, $vars, $hash)
    {
        if ($this->clientToken == null) $this->createClient();
        if ($this->accessToken == null) $this->getAccessToken();
        $ch = curl_init();
        $vars = urlencode(json_encode($vars));
        $exts = urlencode('{"persistedQuery":{"version":1,"sha256Hash":"' . $hash . '"}}');
        curl_setopt($ch, CURLOPT_URL, "https://api-partner.spotify.com/pathfinder/v1/query?operationName={$operationName}&variables={$vars}&extensions={$exts}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'authority: api-partner.spotify.com',
            'accept: application/json',
            'accept-language: en',
            'app-platform: WebPlayer',
            'authorization: Bearer ' . $this->clientToken,
            'client-token: ' . $this->accessToken,
            'content-type: application/json;charset=UTF-8',
            'origin: https://open.spotify.com',
            'referer: https://open.spotify.com/',
            'sec-ch-ua: "Google Chrome";v="117", "Not;A=Brand";v="8", "Chromium";v="117"',
            'sec-ch-ua-mobile: ?0',
            'sec-ch-ua-platform: "Windows"',
            'sec-fetch-dest: empty',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-site',
            'spotify-app-version: 1.2.23.69.g72818607',
            'user-agent: ' . $this->userAgent,
            'accept-encoding: gzip',
        ]);
        return json_decode(gzdecode(curl_exec($ch)), true);
    }
    public function setUserAgent(string $value)
    {
        $this->userAgent = $value;
    }
    public function createClient(): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://open.spotify.com');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'authority: open.spotify.com',
            'user-agent: ' . $this->userAgent,
            'accept-encoding: gzip',
        ]);
        $response = gzdecode(curl_exec($ch));
        $z1 = strpos($response, '<script id="session" data-testid="session" type="application/json">') + strlen('<script id="session" data-testid="session" type="application/json">');
        $fr = json_decode(substr($response, $z1, strpos($response, '</script>', $z1) - $z1), true);
        $this->clientId = $fr['clientId'];
        $this->clientToken = $fr['accessToken'];
        return $this->clientToken;
    }
    public function getAccessToken(string $_id = 'NaN'): string
    {
        if ($_id == 'NaN') {
            if ($this->clientId == null) $this->createClient();
            $_id = $this->clientId;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://clienttoken.spotify.com/v1/clienttoken');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'authority: clienttoken.spotify.com',
            'accept: application/json',
            'content-type: application/json',
            'origin: https://open.spotify.com',
            'referer: https://open.spotify.com/',
            'user-agent: ' . $this->userAgent,
            'accept-encoding: gzip',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"client_data":{"client_version":"1.2.23.56.gc151b964","client_id":"' . $_id . '","js_sdk_data":{"device_brand":"unknown","device_model":"unknown","os":"windows","os_version":"NT 10.0","device_id":"2614934cd7e34dcf996c5738de4a9257","device_type":"computer"}}}');
        $response = json_decode(gzdecode(curl_exec($ch)), true);
        $this->accessToken = $response['granted_token']['token'];
        return $response['granted_token']['token'];
    }
    public function track($id): array
    {
        return $this->api('getTrack', ['uri' => "spotify:track:{$id}"], $this->trachSh256Hash);
    }
    public function album(string $id, int $offset = 0, int $limit = 50): array
    {
        return $this->api('getAlbum', ['uri' => "spotify:album:{$id}", 'locale' => '', "offset" => $offset, 'limit' => $limit], $this->albumSh256Hash);
    }
    public function artist(string $id): array
    {
        return $this->api('queryArtistOverview', ['uri' => "spotify:artist:{$id}", 'locale' => '', 'includePrerelease' => true], $this->artistSh256Hash);
    }
    public function artistَAlbums(string $id, int $offset = 0, int $limit = 5): array
    {
        return $this->api('queryArtistDiscographyAlbums', ['uri' => "spotify:artist:{$id}", "offset" => $offset, 'limit' => $limit], $this->artistAlbumsSh256Hash);
    }
    public function search(string $v, int $offset = 0, int $limit = 10): array
    {
        return $this->api('searchDesktop', ['searchTerm' => $v, "offset" => $offset, 'limit' => $limit, 'numberOfTopResults' => 5, 'includeAudiobooks' => false], $this->searchSh256Hash);
    }
}
