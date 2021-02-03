<?php

require __DIR__.'/vendor/autoload.php';

$addrs = '河南省三门峡市渑池县凯祥新材料有限责任公司
信阳市同济装饰工程有限责任公司
河南省濮阳市仁爱眼科配镜有限公司
贵州省仁怀市茅台镇汉王酒业有限公司
上海市金山区我憧教育科技有限公司
诺行（北京）互联网科技有限公司
广西省南宁市语盟文化传播有限公司
浙江省杭州市众邦文化传媒有限公司
河南千田教育科技有限公司
郑州生之涯教育科技有限公司';
$arr = explode("\n",$addrs);


/*function strSplit($string){
    $strLength = mb_strlen($string);
    $arr = [];
    for ($i=0;$i<$strLength;$i++){
        $arr[] = mb_substr($string,$i,1);
    }
    return $arr;
}
$address = '河南省洛阳市老城区洛阳市老城区九都东路300号';
$str1 = '老城区';
$str2 = '城区';


die;*/


use Addrparser\Extract;
$parser = new Extract();
$a = $parser->setAddress('福建省福州市鼓楼区置地广场18楼')->getAll();


var_dump($a);die;

$cities = explode("\n",file_get_contents(__DIR__.'/test'));

function isTrue(int $count){
    return $count > 1 || $count === 0;
}
$lose = [];
foreach ($cities as $city){
    $addr = $parser->setAddress($city)->getAll();

    $countProvince = count($addr['provinces']);
    $countCity = count($addr['cities']);
    $counties = count($addr['counties']);

    if( isTrue($countProvince) || isTrue($countCity) || isTrue($counties)){
        var_dump($city);
    }

}

print_r($lose);