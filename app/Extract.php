<?php

namespace Addrparser;

class Extract
{
    protected $address = '';

    private $provinces;
    private $cities;
    private $counties;

    protected $resultProvinces = [];

    protected $resultCities = [];

    protected $resultCounties = [];

    protected $countyConfirmed = false;

    protected $cityConfirmed = false;

    protected $provinceConfirmed = false;

    public function __construct()
    {
        $this->provinces = Dict::getProvinces();
        $this->cities = Dict::getCities();
        $this->counties = Dict::getCounies();
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }


    /**
     * 获取区或者县
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected function parseCounty()
    {

        foreach ($this->counties as $county) {
            $keywords = explode('/', $county->keywords);
            $kwLength = count($keywords);
            for ($i = 0; $i < $kwLength; $i++) {
                if (preg_match('/' . $keywords[$i] . '/', $this->address)) {
                    //如果匹配到高新两个字
                    $county->score = $i;
                    $this->resultCounties[] = $county;
                    break;
                }
            }
        }

        $this->resultCounties = Duplicate::removeDuplicate($this->resultCounties);


        if(count($this->resultCounties) === 1){
            $this->countyConfirmed = true;
            //如果只有一个区县，则只获取城市、province就就行了

            $this->resultCities = [Finder::getCityByCode($this->resultCounties[0]->cityCode)];
            $this->resultProvinces = [Finder::getProvinceByCode($this->resultCities[0]->provinceCode)];

            $this->cityConfirmed = true;
            $this->provinceConfirmed = true;
            return $this;
        }

        //如果区或者县名字重复了，则抓城市
        $this->parseCity();
        $dupCities = $this->resultCities;
        $dupCities = Duplicate::removeDuplicate($dupCities);

        foreach ($this->resultCounties as $county1){

            if($county1->cityCode === $dupCities[0]->code){
                $this->resultCounties = [$county1];
                $this->resultCities = [$dupCities];
                $this->countyConfirmed = true;
                $this->cityConfirmed = true;
                $theCity = $dupCities[0];
                break;
            }
        }


        foreach ($this->resultCounties as $county) {

            if (preg_match('/高新/', $county->name)) {
                $tmpName = str_replace('高新技术产业开发区', '', $county->name);
                //获取该城市名字
                $relatedCities = Finder::searchCity($tmpName);
                foreach ($relatedCities as $cityIndex => $relatedCity) {
                    $keywords = explode('/', $relatedCity->keywords);
                    foreach ($keywords as $keyword) {
                        if (preg_match('/' . $keyword . '/', $this->address)) {
                            $theCity = $relatedCity;
                            $this->resultCounties = [$county];
                            $this->resultCities = [$theCity];
                            $this->countyConfirmed = true;
                            $this->cityConfirmed = true;
                            break;
                        }
                    }
                    if (isset($theCity)) {
                        break;
                    }
                }
            }
        }

        if($this->countyConfirmed && $this->cityConfirmed){

            $this->provinceConfirmed = true;
            $theProvince = Finder::getProvinceByCode($theCity->provinceCode);
            $this->resultProvinces = [$theProvince];
            return $this;
        }

        //获取到区县，然后获得市，如果省份为空则获得省份。
        /**
         * 4个直辖市
         */
        foreach ($this->resultCounties as $county2) {
            if ($county2->cityCode === '3101' || $county2->cityCode === '1101'
                || $county2->cityCode === '5001'
                || $county2->cityCode === '1201'
            ) {
                $this->resultCities[] = Finder::getProvinceByCode($county2->provinceCode);
            } else {
                $this->resultCities[] = Finder::getCityByCode($county2->cityCode);
            }
        }


        foreach ($this->resultCities as $city) {
            $this->resultProvinces[] = Finder::getProvinceByCode($city->cityCode);
        }

        return $this;
    }

    /**
     * 获取城市
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected function parseCity()
    {
        if($this->cityConfirmed){
            return $this;
        }

        if(preg_match('/上海/',$this->address)){
            $this->resultCities = Finder::searchCity('上海');
            $this->cityConfirmed = true;
        }
        if(preg_match('/北京/',$this->address)){
            $this->resultCities = Finder::searchCity('北京');
            $this->cityConfirmed = true;
        }
        if(preg_match('/重庆/',$this->address)){
            $this->resultCities = Finder::searchCity('重庆');
            $this->cityConfirmed = true;
        }
        if(preg_match('/天津/',$this->address)){
            $this->resultCities = Finder::searchCity('天津');
            $this->cityConfirmed = true;
        }

        if($this->cityConfirmed){
            return $this;
        }

        foreach ($this->cities as $city) {
            $keywords = explode('/', $city->keywords);
            $kwLength = count($keywords);
            for ($i = 0; $i < $kwLength; $i++) {
                if (preg_match('/' . $keywords[$i] . '/', $this->address)) {
                    $city->score = $i;
                    $this->resultCities[] = $city;
                    break;
                }
            }
        }
        $this->resultCities = Duplicate::removeDuplicate($this->resultCities);

        if(count($this->resultCities) === 1){
            $this->cityConfirmed = true;
            $this->provinceConfirmed = true;
            $this->resultProvinces = [Finder::getProvinceByCode($this->resultCities[0]->provinceCode)];
            return $this;
        }

        foreach ($this->resultCities as $city) {
            $this->resultProvinces[] = Finder::getProvinceByCode($city->provinceCode);
        }

        return $this;
    }


    /**
     * 获取省份
     * @author www.iplayio.cn
     * @since 2021/1/25 10:02
     */
    protected function parseProvince()
    {

        if($this->provinceConfirmed || $this->cityConfirmed || $this->countyConfirmed){
            return $this;
        }

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
        $this->resultProvinces = Duplicate::removeDuplicate($this->resultProvinces);
        if(count($this->resultProvinces) === 1){
            $this->provinceConfirmed = true;
        }
        return $this;
    }


    public function getAll()
    {
        $this->parseCounty();
        $this->parseCity();
        $this->parseProvince();

        $result = [
            'provinces' => $this->resultProvinces,
            'cities' => $this->resultCities,
            'counties' => $this->resultCounties
        ];

        $countyConfirmed = $this->countyConfirmed;
        $cityConfirmed = $this->cityConfirmed;
        $provinceConfirmed = $this->provinceConfirmed;

        $this->countyConfirmed = false;
        $this->cityConfirmed = false;
        $this->provinceConfirmed = false;

        $this->resultCities = [];
        $this->resultProvinces = [];
        $this->resultCounties = [];
        return $result;
    }



    public function getAddress()
    {
        return $this->address;
    }

}