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

        $uri .= array_filter([
            isset($paramOptions['tag']) ? '&tag=' . $paramOptions['tag'] : null,
            isset($paramOptions['city_limit']) ? '&city_limit=' . $paramOptions['city_limit'] : null,
            isset($paramOptions['extensions_adcode']) ? '&extensions_adcode=' . $paramOptions['extensions_adcode'] : null,
            isset($paramOptions['scope']) ? '&scope=' . $paramOptions['scope'] : null,
            isset($paramOptions['center']) ? '&center=' . $paramOptions['center'] : null,
            isset($paramOptions['filter']) ? '&filter=' . $paramOptions['filter'] : null,
            isset($paramOptions['coord_type']) ? '&coord_type=' . (int)$paramOptions['coord_type'] : null,
            isset($paramOptions['ret_coordtype']) ? '&ret_coordtype=' . $paramOptions['ret_coordtype'] : null,
            isset($paramOptions['page_size']) ? '&page_size=' . (int)$paramOptions['page_size'] : null,
            isset($paramOptions['page_num']) ? '&page_num=' . (int)$paramOptions['page_num'] : null,
            isset($paramOptions['sn']) ? '&sn=' . $paramOptions['sn'] : null,
            isset($paramOptions['timestamp']) ? '&timestamp=' . $paramOptions['timestamp'] : null,
            isset($paramOptions['photo_show']) ? '&photo_show=' . (bool)$paramOptions['photo_show'] : null,
            isset($paramOptions['address_result']) ? '&address_result=' . $paramOptions['address_result'] : null,
        ]);

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

        $uri .= array_filter([
            isset($paramOptions['city']) ? '&city=' . $paramOptions['city'] : null,
            isset($paramOptions['ret_coordtype']) ? '&ret_coordtype=' . $paramOptions['ret_coordtype'] : null,
            isset($paramOptions['sn']) ? '&sn=' . $paramOptions['sn'] : null,
            isset($paramOptions['callback']) ? '&callback=' . $paramOptions['callback'] : null,
            isset($paramOptions['extension_analys_level']) ? '&extension_analys_level=' . $paramOptions['extension_analys_level'] : null
        ]);

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

        $uri .= array_filter([
            isset($paramOptions['origin_uid']) ? '&origin_uid=' . $paramOptions['origin_uid'] : null,
            isset($paramOptions['destination_uid']) ? '&destination_uid=' . $paramOptions['destination_uid'] : null,
            isset($paramOptions['waypoints']) ? '&waypoints=' . $paramOptions['waypoints'] : null,
            isset($paramOptions['coord_type']) ? '&coord_type=' . $paramOptions['coord_type'] : null,
            isset($paramOptions['ret_coordtype']) ? '&ret_coordtype=' . $paramOptions['ret_coordtype'] : null,
            isset($paramOptions['tactics']) ? '&tactics=' . (int)$paramOptions['tactics'] : null,
            isset($paramOptions['alternatives']) ? '&alternatives=' . (int)$paramOptions['alternatives'] : null,
            isset($paramOptions['cartype']) ? '&cartype=' . (int)$paramOptions['cartype'] : null,
            isset($paramOptions['plate_number']) ? '&plate_number=' . $paramOptions['plate_number'] : null,
            isset($paramOptions['departure_time']) ? '&departure_time=' . $paramOptions['departure_time'] : null,
            isset($paramOptions['ext_departure_time']) ? '&ext_departure_time=' . $paramOptions['ext_departure_time'] : null,
            isset($paramOptions['expect_arrival_time']) ? '&expect_arrival_time=' . $paramOptions['expect_arrival_time'] : null,
            isset($paramOptions['gps_direction']) ? '&gps_direction=' . (int)$paramOptions['gps_direction'] : null,
            isset($paramOptions['radius']) ? '&radius=' . (float)$paramOptions['radius'] : null,
            isset($paramOptions['speed']) ? '&speed=' . (float)$paramOptions['speed'] : null,
            isset($paramOptions['output']) ? '&output=' . $paramOptions['output'] : null,
            isset($paramOptions['sn']) ? '&sn=' . $paramOptions['sn'] : null,
            isset($paramOptions['timestamp']) ? '&timestamp=' . (int)$paramOptions['timestamp'] : null,
            isset($paramOptions['callback']) ? '&callback=' . $paramOptions['callback'] : null,
            isset($paramOptions['intelligent_plan']) ? '&intelligent_plan=' . (int)$paramOptions['intelligent_plan'] : null,
            isset($paramOptions['walkinfo']) ? '&walkinfo=' . (int)$paramOptions['walkinfo'] : null,
            isset($paramOptions['steps_info']) ? '&steps_info=' . (int)$paramOptions['steps_info'] : null,
            isset($paramOptions['origin_bind_stategy']) ? '&origin_bind_stategy=' . (int)$paramOptions['origin_bind_stategy'] : null,
            isset($paramOptions['dest_bind_stategy']) ? '&dest_bind_stategy=' . (int)$paramOptions['dest_bind_stategy'] : null,
        ]);

        try {
            $client = new Client();
            $res = $client->get($uri);
            return json_decode($res->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
