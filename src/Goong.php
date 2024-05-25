<?php

namespace FGCTApi\Goong\Api;

use GuzzleHttp\Client;

class Goong
{
    private string $key;
    public $client;

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->client = new Client();
    }

    /**
     * Hàm này dùng để tự động hoàn thành địa điểm
     * 
     * @param string $input Từ khóa tìm kiếm (Bắt buộc)
     * @param string $location Điểm xung quanh để lấy thông tin địa điểm. Cần được chỉ định dưới dạng vĩ độ, kinh độ.
     * @param string $limit Số lượng kết quả trả về
     * @param float $radius Xác định khoảng cách (tính bằng mét) mà trong đó sẽ trả về kết quả địa điểm.
     * @param string $sessiontoken Một chuỗi duy nhất cho mỗi phiên người dùng, giúp giảm chi phí khi sử dụng API.
     * @param bool $more_compound Trả về kết quả có chứa thông tin chi tiết hơn
     * @throws Exception Nếu call api gặp lỗi
     * @return json Kết quả hoàn thành địa điểm
     */
    public function autocomplete(
        string $input = "abccc",
        string $location = "d",
        string $limit = "",
        float $radius = 0,
        string $sessiontoken = "",
        bool $more_compound = true
    ) {
        $uri = "https://rsapi.goong.io/Place/AutoComplete";
        $query = [
            'api_key' => $this->key,
            'input' => $input
        ];

        $location && $query['location'] = $location;
        $limit && $query['limit'] = $limit;
        $radius && $query['radius'] = $radius;
        $sessiontoken && $query['sessiontoken'] = $sessiontoken;
        $more_compound && $query['more_compound'] = $more_compound;

        try {
            $res = $this->client->get($uri, ['query' => $query]);

            return json_decode($res->getBody());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Hàm này dùng để lấy thông tin chi tiết của một địa điểm
     * 
     * @param string $address Địa chỉ cụ thể của địa điểm
     * @param string $latLng Tọa độ của địa điểm
     * 
     * @throws InvalidArgumentException Nếu không cung cấp address hoặc latLng
     * @throws Exception Nếu call api gặp lỗi
     * @return json Kết quả thông tin chi tiết của địa điểm
     */
    public function geocode($address = "", $latLng = "")
    {
        if (strlen($address) > 0 && strlen($latLng) > 0) {
            throw new \InvalidArgumentException("You must provide either an address or latLng, but not both");
        }
        $uri = "https://rsapi.goong.io/";
        $query = [
            'api_key' => $this->key
        ];

        if (strlen($address) > 0) {
            $uri .= "geocode";
            $query['address'] = $address;
        } else {
            $uri .= "Geocode";
            $query['latlng'] = $latLng;
        }

        try {
            $res = $this->client->get($uri, ['query' => $query]);

            return json_decode($res->getBody());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Hàm này dùng để dẫn hướng từ điểm A đến điểm B
     * 
     * @param string $origin Điểm xuất phát
     * @param string $destination Điểm đến
     * @param string $vehicle Phương tiện di chuyển (Mặc định là ô tô)
     * @param bool $alternatives Trả về tất cả các lựa chọn đường đi
     * 
     * @throws Exception Nếu call api gặp lỗi
     * @return json Kết quả dẫn hướng
     */
    public function direction($origin, $destination, $vehicle = "car", $alternatives = "false")
    {
        $uri = "https://rsapi.goong.io/Direction";

        $query = [
            'origin' => $origin,
            'destination' => $destination,
            'vehicle' => $vehicle,
            'alternatives' => $alternatives,
            'api_key' => $this->key
        ];

        try {
            $res = $this->client->get($uri, ['query' => $query]);

            return json_decode($res->getBody());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
