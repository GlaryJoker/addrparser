<?php

namespace app;

class Parser
{

    protected $provinceDictPath = '';

    protected $cityDictPath = '';

    protected $countyDictPath = '';

    protected $address = '';


    public function __construct(string $address)
    {
        $this->address = $address;
    }

    /**
     * 设置省份字典地址
     * @param string $path
     * @author GlaryJoker
     * @since 2021/1/25 10:07
     */
    public function setProvinceDict(string $path)
    {
        if (!file_exists($path)) {
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
    public function setCityDict(string $path)
    {
        if (!file_exists($path)) {
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
    public function setCountyDict(string $path)
    {
        if (!file_exists($path)) {
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
    public function getProvince()
    {
        $provinces = json_decode(file_get_contents($this->provinceDictPath));
        $result = false;
        foreach ($provinces as $province) {
            $names = explode('/', $province->name);

            for($i=0;$i<count($names);$i++){

            }
        }
        return $result;
    }

    /**
     * 获取城市
     * @author GlaryJoker
     * @since 2021/1/25 10:02
     */
    public function getCity()
    {

    }

    /**
     * 获取区或者县
     * @author GlaryJoker
     * @since 2021/1/25 10:02
     */
    public function getCounty()
    {

    }

}

$path = __DIR__ . '/../dict/provinces.json';
$provinces = file_get_contents($path);

$provinces = json_decode($provinces);

foreach ($provinces as $province){
    $province->name = explode('/',$province->name)[0];
}

file_put_contents($path,json_encode($provinces,JSON_UNESCAPED_LINE_TERMINATORS|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));