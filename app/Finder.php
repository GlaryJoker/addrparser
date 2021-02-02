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
                $result = $county;
                break;
            }
        }
        return $result;
    }

    /**
     * @param string $countyCode
     * @author www.iplayio.cn
     * @since 2021/2/1 12:14
     */
    public static function getParentsOfCountyCode(string $countyCode)
    {
        Dict::getCounies();
    }

    /**
     * 根据cityCode获取省份
     * @param string $cityCode
     * @author www.iplayio.cn
     * @since 2021/2/1 12:02
     */
    public static function getParentsOfCity(string $cityCode)
    {

    }


}