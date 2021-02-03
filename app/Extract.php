<?php

namespace Addrparser;

class Extract
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
    public  function parseProvince()
    {
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
        $this->resultProvinces = $this->removeDuplicate($this->resultProvinces);
        return $this;
    }

    /**
     * 获取城市
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    public  function parseCity()
    {

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
        $this->resultCities = $this->removeDuplicate($this->resultCities);
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
    public  function parseCounty()
    {

        foreach ($this->counties as $county){
            $keywords = explode('/',$county->keywords);
            $kwLength = count($keywords);
            for($i=0;$i<$kwLength;$i++){
                if(preg_match('/'.$keywords[$i].'/',$this->address)){
                    //如果匹配到高新两个字
                    $county->score = $i;
                    $this->resultCounties[] = $county;
                    break;
                }
            }
        }

        $this->resultCounties = $this->removeDuplicate($this->resultCounties);

        //


        foreach ($this->resultCounties as $county){

            if(preg_match('/高新/',$county->name)){
                $tmpName = str_replace('高新技术产业开发区','',$county->name);
                //获取该城市名字
                $relatedCities = Finder::searchCity($tmpName);

                foreach ($relatedCities as $relatedCity){

                }

                //如果等于1，则获取到该城市
                $tmpCityName = str_replace('市','',$relatedCities[0]->name);
                $countyFullName = $tmpCityName."高新技术产业开发区";
                $theCounty = Finder::getCountyByName($countyFullName);
                $tmpCounties[] = $theCounty;
            }
        }

        if(isset($tmpCounties)){
            $this->resultCounties = $tmpCounties;
        }


        //获取到区县，然后获得市，如果省份为空则获得省份。
        /**
         * 4个直辖市
         */
        foreach ($this->resultCounties as $county){
            if($county->cityCode === '3101' || $county->cityCode === '1101'
                || $county->cityCode === '5001'
                || $county->cityCode === '1201'
            ){
                $this->resultCities[] = Finder::getProvinceByCode($county->provinceCode);
            }else{
                $this->resultCities[] = Finder::getCityByCode($county->cityCode);
            }
        }

        foreach ($this->resultCities as $city){
            $this->resultProvinces[] = Finder::getProvinceByCode($city->cityCode);
        }

        return $this;
    }

    public function parseAll(){
        //解析县区
        $this->parseCounty();
        $this->parseCity();
        $this->parseProvince();
        return $this;
    }

    /**
     * 处理
     * @author www.iplayio.cn
     * @since 2021/2/2 12:54
     */
    public function getAll(){

        return [
            'provinces' => $this->getProvince(),
            'cities' => $this->getCity(),
            'counties' => $this->getCounty()
        ];
    }

    public function getCity(){

        $cities = $this->removeDuplicate($this->resultCities);
        $this->resultCities = [];

        return $cities;
    }

    public function getCounty(){
        $counties = $this->resultCounties;
        $this->resultCounties = [];
        return $counties;
    }

    public function getProvince(){
         $provinces = $this->resultProvinces;
        $this->resultProvinces = [];
         return $provinces;
    }

    protected function removeDuplicate(array $array = []){
        if(!$array){
            return [];
        }

        if(count($array) === 1){
            return $array;
        }

        $cityCodes = array_unique(array_column($array,'code'));
        $exitsCode = [];
        $resultArray = [];

        //先获取score为0的，然后去除其他的，如果没有为0的，返回其他匹配到数字
        $filterArray = array_filter($array,function($item){
            return $item->score === 0;
        });

        $rCount = count($filterArray);
        if($rCount === 1){
            return $filterArray;
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