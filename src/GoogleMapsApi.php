<?php

namespace GoogleMaps\Api;

use Error;
use GuzzleHttp\Client;

class GoogleMapsApi
{
    private $apiKey = "";
    protected $client;
    public function __construct(string $key)
    {
        $this->apiKey = $key;
        $this->client = new Client();
    }
    public function autocomplete(string $input, array $optionParams = [], $output = "json")
    {
        $uri = "https://maps.googleapis.com/maps/api/place/autocomplete/$output?input=$input&key=$this->apiKey";

        $uri .= isset($optionParams['components']) ? "&components=" . $optionParams['components'] : null;
        $uri .= isset($optionParams['language']) ? "&language=" . $optionParams['language'] : null;
        $uri .= isset($optionParams['location']) ? "&location=" . $optionParams['location'] : null;
        $uri .= isset($optionParams['locationbias']) ? "&locationbias=" . $optionParams['locationbias'] : null;
        $uri .= isset($optionParams['locationrestriction']) ? "&locationrestriction=" . $optionParams['locationrestriction'] : null;
        $uri .= isset($optionParams['offset']) ? "&offset=" . $optionParams['offset'] : null;
        $uri .= isset($optionParams['origin']) ? "&origin=" . $optionParams['origin'] : null;
        $uri .= isset($optionParams['radius']) ? "&radius=" . $optionParams['radius'] : null;
        $uri .= isset($optionParams['region']) ? "&region=" . $optionParams['region'] : null;
        $uri .= isset($optionParams['sessiontoken']) ? "&sessiontoken=" . $optionParams['sessiontoken'] : null;
        $uri .= isset($optionParams['strictbounds']) ? "&strictbounds=" . $optionParams['strictbounds'] : null;
        $uri .= isset($optionParams['types']) ? "&types=" . $optionParams['types'] : null;

        $res = $this->client->get($uri);
        return $res->getBody();
    }

    public function computeRoutes(array $body, string $fielMask)
    {
        $url = "https://routes.googleapis.com/directions/v2:computeRoutes";

        $response = $this->client->post($url, [
            'json' => $body,
            'headers' => [
                'X-Goog-Api-Key' => $this->apiKey,
                'X-Goog-FieldMask' => $fielMask,
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function geocoding(array $param, $output = "json")
    {
        $client = new Client();
        $uri = "https://maps.googleapis.com/maps/api/geocode/$output?key=$this->apiKey";

        $isActive = 0;

        $isActive = isset($param['address']) ? 1 : $isActive;
        $isActive = isset($param['components']) ? 2 : $isActive;

        if ($isActive > 0) {
            $uri .= isset($param['bounds']) !== 0 ? "bounds=" . $param['bounds'] : null;
            $uri .= isset($param['language']) !== 0 ? "language=" . $param['language'] : null;
            $uri .= isset($param['region']) !== 0 ? "region=" . $param['region'] : null;
            $res = $client->get($uri);

            return json_decode($res->getBody());
        }

        $missing = $isActive == 1 ? "address" : "components";

        return new Error("Missing parameter $missing");
    }
}
