<?php
namespace App;

/**
 * Class Dict
 * @package App
 * @author www.iplayio.cn
 * @since 2021/2/1 11:58
 */
class Dict{

    /**
     * @author www.iplayio.cn
     * @since 2021/2/1 11:58
     */
    public static function getCounies(){
        return json_decode(file_get_contents(__DIR__ . '/../dict/county.json'));
    }

    /**
     * @author www.iplayio.cn
     * @since 2021/2/1 11:58
     */
    public static function getCities(){
        return json_decode(file_get_contents(__DIR__ . '/../dict/city.json'));
    }

    /**
     * @author www.iplayio.cn
     * @since 2021/2/1 11:58
     */
    public static function getProvinces(){
        return json_decode(file_get_contents(__DIR__ . '/../dict/provinces.json'));
    }

}