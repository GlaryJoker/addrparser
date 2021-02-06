<?php

namespace IPlayIO;

/**
 * 获取
 * Class Finder
 * @package App
 * @author www.iplayio.cn
 * @since 2021/2/1 11:55
 */
class AddrFinder
{

    /**
     * @author www.iplayio.cn
     * @since 2021/2/1 12:08
     */
    public static function getProvinceByCode($provinceCode)
    {
        $result = false;
        foreach (AddrDict::getProvinces() as $province) {
            if ($province->code === $provinceCode) {
                $province->score = 0;
                $result = $province;
                break;
            }
        }
        return $result;
    }

    /**
     * @author www.iplayio.cn
     * @since 2021/2/1 12:08
     */
    public static function getCityByCode($cityCode)
    {
        $result = false;
        foreach (AddrDict::getCities() as $city) {
            if ($city->code === $cityCode) {
                $city->score = 0;
                $result = $city;
                break;
            }
        }
        return $result;
    }

    /**
     * @author www.iplayio.cn
     * @since 2021/2/1 12:08
     */
    public static function getCountyByCode($countyCode)
    {
        $result = false;
        foreach (AddrDict::getCities() as $county) {
            if ($county->code === $countyCode) {
                $county->score = 0;
                $result = $county;
                break;
            }
        }
        return $result;
    }

    /**
     * 根据关键字搜索城市
     * @param string $query
     * @author www.iplayio.cn
     * @since 2021/2/3 10:42
     */
    public static function searchCity(string $query)
    {
        $resultCities = [];
        foreach (AddrDict::getCities() as $city) {
            $keywords = explode('/',$city->keywords);
            $kwLength = count($keywords);
            for($i=0;$i<$kwLength;$i++){
                if(preg_match('/'.$keywords[$i].'/',$query)){
                    $city->score = $i;
                    $resultCities[] = $city;
                    break;
                }
            }
        }
        return $resultCities;
    }

    public static function getCountyByName(string $name){
        $result = [];
        foreach (AddrDict::getCounies() as $county) {
            if($county->name === $name){
                $result[] = $county;
                continue;
            }
        }
        return $result;
    }

    public static function searchCounty(string $query){
        $resultCities = [];
        foreach (AddrDict::getCounies() as $couny) {
            $keywords = explode('/',$couny->keywords);
            $kwLength = count($keywords);
            for($i=0;$i<$kwLength;$i++){
                if(preg_match('/'.$keywords[$i].'/',$query)){
                    $couny->score = $i;
                    $resultCities[] = $couny;
                    break;
                }
            }
        }
        return $resultCities;
    }
}