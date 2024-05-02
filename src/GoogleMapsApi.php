<?php

namespace GoogleMaps\Api;

use Error;
use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;

class GoogleMapsApi
{
    private $apiKey = "";
    protected $client;
    public function __construct(string $key)
    {
        $this->apiKey = $key;
        $this->client = new Client();
    }

    /**
     * Hàm này dùng để tự động hoàn thành địa điểm
     * 
     * @param string $input Chuỗi văn bản để tìm kiếm (Bắt buộc)
     * @param array $optionParams Mảng chứa các option parameters (Tùy chọn), bao gồm: 
     *  - 'components' (string) Một nhóm các địa điểm mà bạn muốn hạn chế kết quả của mình
     *  - 'language' (string) Ngôn ngữ sử dụng để trả về kết quả.
     *  - 'location' (string) Điểm xung quanh để lấy thông tin địa điểm. Cần được chỉ định dưới dạng vĩ độ, kinh độ.
     *  - 'locationbias' (string) Ưu tiên các kết quả dựa trên vị trí được chỉ định. Có thể là một điểm cụ thể hoặc một vùng.
     *  - 'locationrestriction' (string) Hạn chế kết quả dựa trên vị trí được chỉ định. Có thể là một điểm cụ thể hoặc một vùng.
     *  - 'offset' (string) Số ký tự trong chuỗi đầu vào mà kết quả phải bắt đầu từ đó.
     *  - 'origin' (string) Vị trí của người dùng, được sử dụng để ưu tiên các kết quả dựa trên vị trí của người dùng.
     *  - 'radius' (string) Xác định khoảng cách (tính bằng mét) mà trong đó sẽ trả về kết quả địa điểm.
     *  - 'region' (string) Mã quốc gia, được sử dụng để ưu tiên các kết quả trong một quốc gia cụ thể.
     *  - 'sessionToken' (string) Một chuỗi duy nhất cho mỗi phiên người dùng, giúp giảm chi phí khi sử dụng API.
     *  - 'strictbounds' (string) Chỉ trả về những địa điểm nằm chặt chẽ trong khu vực được xác định bởi location và radius.
     *  - 'types' (string) Hạn chế kết quả từ yêu cầu Place Autocomplete để chỉ thuộc một loại nhất định.
     * @param string $output Chuỗi kết quả trả về (Mặc định là json)
     * @throws Exception Nếu call api gặp lỗi
     * @return json Kết quả hoàn thành địa điểm
     * 
     */
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

        try {
            $res = $this->client->get($uri);
            return $res->getBody();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Hàm này dùng để xem tuyến đường cho một hoặc nhiều điểm đến
     * 
     * @param array $body Mảng chứa các tham số ( origin: Bắt buộc, destination: Bắt buộc), nội dung của body bạn có thể đọc tại đây: https://developers.google.com/maps/documentation/routes/reference/rest/v2/TopLevel/computeRoutes
     * @param string $fielMask Chuỗi các giá trị muốn trả về trong kết quả trả về, bạn có thể đọc thêm tại đây: https://developers.google.com/maps/documentation/routes/choose_fields-rm
     * @throws Exception Nếu gọi api bị lỗi.
     * @return json Kết quả tuyến đường.
     */
    public function computeRoutes(array $body, string $fielMask)
    {
        $url = "https://routes.googleapis.com/directions/v2:computeRoutes";

        try {
            $response = $this->client->post($url, [
                'json' => $body,
                'headers' => [
                    'X-Goog-Api-Key' => $this->apiKey,
                    'X-Goog-FieldMask' => $fielMask,
                ],
            ]);

            return json_decode($response->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Hàm này dùng để mã hóa địa chỉ.
     *
     * @param array $param Mảng chứa các tham số, bao gồm:
     *  - 'address' (string) Địa chỉ cần mã hóa (Bắt buộc nếu không sử dụng components)           
     *  - 'bounds' (string) Hộp giới hạn của khung nhìn. (Tùy chọn)
     *  - 'language ' (string) Ngôn ngữ sử dụng để trả về kết quả. (Tùy chọn)
     *  - 'region ' (string) Mã vùng. (Tùy chọn)
     *  - 'components ' (string) Một bộ lọc thành phần. (Tùy chọn nếu không sử dụng address)
     * @param string $output Định dạng đầu ra, mặc định là 'json'.
     * @throws InvalidArgumentException Nếu tham số 'address' hoặc 'components' không có trong mảng $param.
     * @return json Kết quả mã hóa địa chỉ.
     */
    public function geocoding(array $param, $output = "json")
    {
        $client = new Client();
        $uri = "https://maps.googleapis.com/maps/api/geocode/$output?key=$this->apiKey";

        $isActive = false;

        $isActive = isset($param['address']) || isset($param['components']) ? true : false;

        if ($isActive) {
            $uri .= isset($param['bounds']) ? "bounds=" . $param['bounds'] : null;
            $uri .= isset($param['language']) ? "language=" . $param['language'] : null;
            $uri .= isset($param['region']) ? "region=" . $param['region'] : null;

            $res = $client->get($uri);

            return json_decode($res->getBody());
        }

        throw new InvalidArgumentException("Missing parameter address or components");
    }
}
