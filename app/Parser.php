<?php

namespace Addrparser;

class Parser
{
    protected static $address = '';

    protected static $resultProvince = [];
    protected static $resultCity = [];
    protected static $resultCounty = [];


    public function __construct()
    {

    }

    public static function setAddress($address){
        self::$address = $address;
        return new self();
    }

    /**
     * 获取省份
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected static function parseProvince()
    {
        $provinces = Dict::getProvinces();

        foreach ($provinces as $province) {
            $names = explode('/', $province->keywords);
            for ($i = 0; $i < count($names); $i++) {
                if (preg_match('/' . $names[$i] . '/', self::$address)) {
                    $result[] = $province;
                    self::$address = mb_substr(self::$address,mb_strlen($names[$i]));
                    self::$resultProvince[] = $province;
                    break;
                }
            }
        }
    }

    /**
     * 获取城市
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected static function parseCity()
    {
        $cities = Dict::getCities();

        foreach ($cities as $city){
            $keywords = explode('/',$city->keywords);
            $kwLength = count($keywords);
            for($i=0;$i<$kwLength;$i++){
                if(preg_match('/'.$keywords[$i].'/',self::$address)){
                    self::$resultCity[] = $city;
                    break;
                }
            }
        }

        foreach (self::$resultCity as $city){
            self::$resultProvince[] = Finder::getProvinceByCode($city->provinceCode);
        }
    }

    /**
     * 获取区或者县
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected static function parseCounty()
    {
        $counties =  Dict::getCounies();

        foreach ($counties as $county){
            $keywords = explode('/',$county->keywords);
            $kwLength = count($keywords);
            for($i=0;$i<$kwLength;$i++){
                if(preg_match('/'.$keywords[$i].'/',self::$address)){
                    self::$resultCounty[] = $county;
                    break;
                }
            }
        }
        //获取到区县，然后获得市，如果省份为空则获得省份。

        foreach (self::$resultCounty as $county){
            self::$resultCity[] = Finder::getCityByCode($county->cityCode);
        }
    }

    public function parseAll(){

    }

    /**
     * 处理
     * @author www.iplayio.cn
     * @since 2021/2/2 12:54
     */
    public static function getAll(){
        self::parseProvince();
        self::parseCounty();
        self::parseCity();
        return [
            'provinces' => self::getProvince(),
            'cities' => self::getCity(),
            'counties' => self::getCounty(),
        ];
    }

    public static function getCity(){
        self::parseCity();
        return self::removeDuplicate(self::$resultCity);
    }

    public static function getCounty(){
        self::parseCounty();
        return self::removeDuplicate(self::$resultCounty);
    }

    public static function getProvince(){
        self::parseProvince();
         return self::removeDuplicate(self::$resultProvince);
    }

    protected static function removeDuplicate(array $array = []){
        $cityCodes = array_unique(array_column($array,'code'));
        $exitsCode = [];
        $resultArray = [];

        foreach ($array as $city){
            if(in_array($city->code,$cityCodes) && !in_array($city->code,$exitsCode)){
                array_push($exitsCode,$city->code);
                $resultArray[] = $city;
                continue;
            }
        }

        return $resultArray;
    }

    public function getAddress(){
        return self::$address;
    }


}