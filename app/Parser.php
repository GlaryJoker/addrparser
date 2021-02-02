<?php

namespace Addrparser;

class Parser
{
    protected  $address = '';

    private $provinces;
    private $cities;
    private $counties;

    protected $resultProvinces = [];

    protected $resultCities = [];

    protected $resultCounties = [];

    public function __construct()
    {
        $this->provinces = Dict::getProvinces();
        $this->cities = Dict::getCities();
        $this->counties = Dict::getCounies();
    }

    public function setAddress($address){
        $this->address = $address;
        return $this;
    }

    /**
     * 获取省份
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected  function parseProvince()
    {
        $this->resultProvinces = [];

        foreach ($this->provinces as $province) {
            $names = explode('/', $province->keywords);
            for ($i = 0; $i < count($names); $i++) {
                if (preg_match('/' . $names[$i] . '/', $this->address)) {
                    $result[] = $province;
                    $province->score = $i;
                    $this->resultProvinces[] = $province;
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * 获取城市
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected  function parseCity()
    {
        $this->resultCities = [];
        foreach ($this->cities as $city){
            $keywords = explode('/',$city->keywords);
            $kwLength = count($keywords);
            for($i=0;$i<$kwLength;$i++){
                if(preg_match('/'.$keywords[$i].'/',$this->address)){
                    $city->score = $i;
                    $this->resultCities[] = $city;
                    break;
                }
            }
        }

        foreach ($this->resultCities as $city){
            $this->resultProvinces[] = Finder::getProvinceByCode($city->provinceCode);
        }

        return $this;
    }

    /**
     * 获取区或者县
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected  function parseCounty()
    {
        $this->resultCounties = [];
        foreach ($this->counties as $county){
            $keywords = explode('/',$county->keywords);
            $kwLength = count($keywords);
            for($i=0;$i<$kwLength;$i++){
                if(preg_match('/'.$keywords[$i].'/',$this->address)){
                    $county->score = $i;
                    $this->resultCounties[] = $county;
                    break;
                }
            }
        }
        //获取到区县，然后获得市，如果省份为空则获得省份。

        foreach ($this->resultCounties as $county){
            $this->resultCities[] = Finder::getCityByCode($county->cityCode);
        }

        foreach ($this->resultCities as $city){
            $this->resultProvinces[] = Finder::getProvinceByCode($city->cityCode);
        }

        return $this;
    }

    protected function parseAll(){

    }

    /**
     * 处理
     * @author www.iplayio.cn
     * @since 2021/2/2 12:54
     */
    public function getAll(){
        //解析县区

        $this->parseCounty();
        $this->parseCity();
        $this->parseProvince();
        return [
            'provinces' => $this->getProvince(),
            'cities' => $this->getCity(),
            'counties' => $this->getCounty()
        ];
    }

    public function getCity(){

        return $this->removeDuplicate($this->resultCities);
    }

    public function getCounty(){

        return self::removeDuplicate($this->resultCounties);
    }

    public function getProvince(){
         return $this->resultProvinces;
    }

    protected function removeDuplicate(array $array = []){
        $cityCodes = array_unique(array_column($array,'code'));
        $exitsCode = [];
        $resultArray = [];

        //先获取score为0的，然后去除其他的，如果没有为0的，返回其他匹配到数字
        $array = array_filter($array,function($item){
            return $item->score === 0;
        });

        if(count($array) === 1){
            return $array;
        }

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
        return $this->address;
    }

}