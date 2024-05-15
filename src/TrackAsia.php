<?php

namespace FGCTApi\TrackAsia\Api;

use Exception;
use GuzzleHttp\Client;

class TrackAsia
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Method: GET
     * Hàm mã hóa địa lý bởi TrackAsia
     * 
     * @param string $lat Vĩ độ
     * @param string $lng Kinh độ
     * @param float $size Số lượng kết quả trả về
     * @param float $radius Bán kính tìm kiếm
     * @param string $lang Ngôn ngữ trả về
     * 
     * @return object
     * @throws Exception
     */
    public function geocoding(string $lat, string $lng, float $size = 10, float $radius = null, string $lang = "vi")
    {
        $uri = "https://maps.track-asia.com/api/v1/reverse?point.lat=$lat&point.lon=$lng&size=$size&lang=$lang";

        $uri .= $radius ? "&boundary.circle.radius=$radius" : null;

        try {
            $res = $this->client->get($uri);
            return json_decode($res->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method: GET
     * Hàm tìm kiếm địa lý bởi TrackAsia
     * 
     * @param string $text Từ khóa tìm kiếm
     * @param float $size Số lượng kết quả trả về
     * @param string $lat Vĩ độ
     * @param string $lng Kinh độ
     * @param string $lang Ngôn ngữ trả về
     * 
     * @return object
     * @throws Exception
     */
    public function autocomplete(string $text, int $size = 10, string $lat = "", string $lng = "", string $lang = "vi")
    {
        // $uri = "https://maps.track-asia.com/api/v1/autocomplete?lang=$lang&text=$text&size=$size";
        $uri = "https://maps.track-asia.com/api/v1/search?lang=$lang&text=$text&size=$size";

        $uri .= strlen($lat) > 0 ? "&focus.point.lat=$lat" : null;
        $uri .= strlen($lng) > 0 ? "&focus.point.lon=$lng" : null;

        try {
            $res = $this->client->get($uri);
            return json_decode($res->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method: GET
     * Hàm lập kế hoạch tuyến đường bởi TrackAsia
     * 
     * @param string $origin Điểm xuất phát
     * @param string $destination Điểm đến
     * @param string $geometries Loại hình học trả về
     * @param bool $steps Trả về các bước đi
     * @param string $overview Loại hình học trả về
     * 
     * @return object
     * @throws Exception
     */
    public function directions(string $origin, string $destination, string $geometries = 'polyline', bool $steps = false, string $overview = "simplified")
    {
        $uri = "https://maps.track-asia.com/route/v1/car/$origin;$destination.json?geometries=$geometries&steps=$steps&overview=$overview";

        try {
            $res = $this->client->get($uri);
            return json_decode($res->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
