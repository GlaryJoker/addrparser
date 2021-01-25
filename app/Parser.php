<?php
namespace app;

class Parser{

    protected $provinceDictPath = '';

    protected $cityDictPath = '';

    protected $countyDictPath = '';


    public function __construct()
    {


    }

    /**
     * 设置省份字典地址
     * @param string $path
     * @author GlaryJoker
     * @since 2021/1/25 10:07
     */
    public function setProvinceDict(string $path){
        if(!file_exists($path)){
            throw new \Exception("省份字典`{$path}`不存在");
        }
        $this->provinceDictPath = $path;
        return $this;
    }

    /**
     * 设置省份字典地址
     * @param string $path
     * @author GlaryJoker
     * @since 2021/1/25 10:07
     */
    public function setCityDict(string $path){
        if(!file_exists($path)){
            throw new \Exception("城市字典`{$path}`不存在");
        }
        $this->cityDictPath = $path;
        return $this;
    }

    /**
     * 设置区县字典地址
     * @param string $path
     * @author GlaryJoker
     * @since 2021/1/25 10:07
     */
    public function setCountyDict(string $path){
        if(!file_exists($path)){
            throw new \Exception("区/县字典`{$path}`不存在");
        }
        $this->countyDictPath = $path;
        return $this;
    }

    /**
     * 获取省份
     * @author GlaryJoker
     * @since 2021/1/25 10:02
     */
    public function getProvince(){

    }

    /**
     * 获取城市
     * @author GlaryJoker
     * @since 2021/1/25 10:02
     */
    public function getCity(){

    }

    /**
     * 获取区或者县
     * @author GlaryJoker
     * @since 2021/1/25 10:02
     */
    public function getCounty(){

    }
}

$path = __DIR__.'/../dict/city.json';
$cities = file_get_contents($path);

$cities = json_decode($cities);