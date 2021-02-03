<?php

namespace Addrparser;

/**
 * 获取
 * Class Finder
 * @package App
 * @author www.iplayio.cn
 * @since 2021/2/1 11:55
 */
class Finder
{

    /**
     * @author www.iplayio.cn
     * @since 2021/2/1 12:08
     */
    public static function getProvinceByCode($provinceCode)
    {
        $result = false;
        foreach (Dict::getProvinces() as $province){
            if($province->code === $provinceCode){
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
        foreach (Dict::getCities() as $city){
            if($city->code === $cityCode){
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
        foreach (Dict::getCities() as $county){
            if($county->code === $countyCode){
                $county->score = 0;
                $result = $county;
                break;
            }
        }
        return $result;
    }
}