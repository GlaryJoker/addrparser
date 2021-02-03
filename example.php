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


use Addrparser\Extract;
$parser = new Extract();
$a = $parser->setAddress('河南省洛阳市洛龙区洛阳师范学院伊滨校区')->parseAll()->getAll();

//var_dump($parser->parseCounty()->getCounty());
//var_dump($parser->parseCity()->getCity());
//var_dump($parser->parseCounty()->getProvince());
var_dump($a);
var_dump($parser->getAddress());

$c = \Addrparser\Dict::getCounies();

foreach ($c as $d){
    if(preg_match('/高新技术产业开发区/',$d->name)){
        $d->keywords = '高新/'.$d->keywords;
    }
}

file_put_contents(__DIR__.'/dict/county.json',json_encode($c,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS|JSON_UNESCAPED_UNICODE));