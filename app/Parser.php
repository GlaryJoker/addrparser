<?php

namespace app;

class Parser
{

    protected $defaultProvinces;

    protected $provinceDictPath = '';

    protected $cityDictPath = '';

    protected $countyDictPath = '';

    protected $address = '';


    public function __construct(string $address = null)
    {
        $this->address = $address;
    }

    /**
     * 设置省份字典地址
     * @param string $path
     * @author www.iplayio.cn
     * @since 2021/1/25 10:07
     */
    public function setCustomProvinceDict(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("省份字典`{$path}`不存在");
        }
        $this->provinceDictPath = $path;
        return $this;
    }

    /**
     * 设置自定义省份字典地址
     * @param string $path
     * @author www.iplayio.cn
     * @since 2021/1/25 10:07
     */
    public function setCustomCityDict(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("城市字典`{$path}`不存在");
        }
        $this->cityDictPath = $path;
        return $this;
    }

    /**
     * 设置自定义区县字典地址
     * @param string $path
     * @author www.iplayio.cn
     * @since 2021/1/25 10:07
     */
    public function setCustomCountyDict(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception("区/县字典`{$path}`不存在");
        }
        $this->countyDictPath = $path;
        return $this;
    }


    /**
     * 获取省份
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    public function getProvince()
    {
        $provinces = Dict::getProvinces();

        $result = false;
        foreach ($provinces as $province) {
            $names = explode('/', $province->keywords);
            $preg = false;
            for ($i = 0; $i < count($names); $i++) {
                if (preg_match('/' . $names[$i] . '/', $this->address)) {
                    $preg = true;
                    break;
                }
            }

            if ($preg) {
                $result = $province;
                break;
            }
        }
        return $result;
    }

    /**
     * 获取城市
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    public function getCity()
    {

    }

    /**
     * 获取区或者县
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    public function getCounty()
    {
        $counties =  Dict::getCounies();
        $result = false;

        foreach ($counties as $county){
            $preg = false;
            if(preg_match('/'.$county->name.'/',$this->address)){
                $preg = true;
                break;
            }else{
                $keywords = explode('/',$county->keywords);
                $kwLength = count($keywords);
                for($i=0;$i<$kwLength;$i++){
                    if(preg_match('/'.$keywords[$i].'/',$this->address)){
                        $preg = true;
                        break;
                    }
                }
            }
            if($preg){
                $result = $county;
                break;
            }
        }
        //如果查询到，根据区或者县获取地级市或者省份
        if($result){
            $parents = Finder::getParentsOfCountyCode($result->code);
        }
        
        return $result;
    }


}

$re = new Parser("河南省灵宝");
var_dump($re->getCounty());