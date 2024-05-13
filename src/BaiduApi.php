<?php

namespace FGCTApi\Baidu\Api;

use Exception;
use GuzzleHttp\Client;



class BaiduApi
{
    private $ak = "";
    protected $client;
    public function __construct(string $ak)
    {
        $this->ak = $ak;
        $this->client = new Client();
    }

    function setUri(string &$uri, array $paramOptions)
    {
        foreach ($paramOptions as $key => $value) {
            if (isset($value)) {
                $uri .= "&$key=$value";
            }
        }
        return $uri;
    }

    /**
     * Hàm hoàn thành địa chỉ (dành cho Trung Quốc)
     * 
     * @param string $query Từ khóa tìm kiếm (Bắt buộc)
     * @param string $origin Tên khu vực có thể là quốc gia hoặc tên tỉnh, thành phố (Mặc định là: Trung Quốc - 中国)
     * @param array $paramOptions Mảng chứa các option parameters (Tùy chọn), bao gồm: 
     * - output string Định dạng đầu ra là Json hoặc Xml (Mặc định là json)
     * - tag string Từ khóa tìm kiếm
     * - city_limit string Giới hạn kết quả tìm kiếm trong một thành phố cụ thể
     * - extensions_adcode string Mở rộng mã vùng
     * - scope string Phạm vi tìm kiếm
     * - center string Tọa độ trung tâm
     * - filter string Lọc kết quả
     * - coord_type int Loại tọa độ
     * - ret_coordtype string Tùy chọn tham số để trả về tọa độ kinh độ và vĩ độ quốc gia trong kết quả POI
     * - page_size int Số lượng kết quả trên mỗi trang
     * - page_num int Số trang
     * - sn string SN Check trong Baidu Maps API
     * - timestamp int Thời gian
     * - photo_show bool Hiển thị ảnh
     * - address_result string Kết quả địa chỉ
     * @return json Kết quả gọi api
     * @throws Exception Lỗi khi gọi api
     */

    public function autocomplete(string $query, string $origin = "中国", array $paramOptions = [])
    {
        $uri = "https://api.map.baidu.com/place/v2/search?query=$query&region=$origin&ak=$this->ak";


        $uri .= '&output=' . isset($paramOptions['output']) ? $paramOptions['output'] : 'json';

        $this->setUri($uri, $paramOptions);

        try {
            $client = new Client();
            $res = $client->get($uri);

            return json_decode($res->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Hàm mã hóa địa chỉ (dành cho Trung Quốc)
     * 
     * @param string $addresss Địa chỉ cần mã hóa (Bắt buộc)
     * @param array $paramOptions Một mảng chứa các option parameters (Tùy chọn), bao gồm: 
     * - output string Định dạng đầu ra là Json hoặc Xml (Mặc định là json)
     * - city string Tên thành phố
     * - ret_coordtype string Tùy chọn tham số để trả về tọa độ kinh độ và vĩ độ quốc gia trong kết quả POI
     * - sn string SN Check trong Baidu Maps API
     * - callback string Trả về giá trị trả về ở định dạng json thông qua hàm gọi lại để triển khai hàm jsonp
     * - extension_analys_level int Mức độ phân tích mở rộng
     * @return json Kết quả gọi api
     * @throws Exception Lỗi khi gọi api
     */
    public function geocoding(string $address, array $paramOptions = [])
    {
        $uri = "https://api.map.baidu.com/geocoding/v3/?address=$address&ak=$this->ak";

        $uri .= '&output=' . isset($paramOptions['output']) ? $paramOptions['output'] : 'json';

        $this->setUri($uri, $paramOptions);

        try {
            $client = new Client();
            $res = $client->get($uri);
            return json_decode($res->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Hàm thiết lập đường đi (dành cho Trung Quốc)
     * 
     * @param string $origin Điểm xuất phát (Bắt buộc)
     * @param string $destination Điểm đến (Bắt buộc)
     * @param array $paramOptions Mảng chứa các option parameters (Tùy chọn), bao gồm:
     * - origin_uid string Định danh của điểm xuất phát
     * - destination_uid string Định danh của điểm đến
     * - waypoints string Điểm dừng chân
     * - coord_type int Loại tọa độ (Mặc định là bd09II)
     * - ret_coordtype string Tùy chọn tham số để trả về tọa độ kinh độ và vĩ độ quốc gia trong kết quả POI (Mặc định là bd09II)
     * - tactics int Chiến lược định tuyến (Mặc định là 0)
     * - alternatives int Có nên trả về tuyến đường thay thế không (Mặc định là 0)
     * - cartype int Loại xe (Mặc định là 0)
     * - plate_number string Biển số xe
     * - departure_time string Thời gian xuất phát
     * - ext_departure_time string Thời gian xuất phát mở rộng
     * - expect_arrival_time string Thời gian dự kiến đến
     * - gps_direction int Hướng GPS
     * - radius float Bán kính
     * - speed float Tốc độ
     * - output string Định dạng đầu ra (Mặc định là json)
     * - sn string SN Check trong Baidu Maps API
     * - timestamp int Thời gian
     * - callback string Trả về giá trị trả về ở định dạng json thông qua hàm gọi lại để triển khai hàm jsonp
     * - intelligent_plan int Kế hoạch thông minh
     * - walkinfo int Thông tin đi bộ
     * - steps_info int Thông tin bước
     * - origin_bind_stategy int Chiến lược ràng buộc nguồn gốc (Mặc định là 0)
     * - dest_bind_stategy int Chiến lược ràng buộc đích (Mặc định là 0)
     * @return json Kết quả gọi api
     * @throws Exception Lỗi khi gọi api
     */
    public function driving_route(string $origin, string $destination, array $paramOptions = [])
    {
        $uri = "https://api.map.baidu.com/direction/v2/driving?origin=$origin&destination=$destination&ak=$this->ak";

        $this->setUri($uri, $paramOptions);

        try {
            $client = new Client();
            $res = $client->get($uri);
            return json_decode($res->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
