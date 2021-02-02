<?php

require __DIR__.'/vendor/autoload.php';

$addrs = '河南省三门峡市渑池县凯祥新材料有限责任公司
信阳市同济装饰工程有限责任公司
河南省濮阳市仁爱眼科配镜有限公司
贵州省仁怀市茅台镇汉王酒业有限公司
上海市我憧教育科技有限公司
诺行（北京）互联网科技有限公司
广西省南宁市语盟文化传播有限公司
浙江省杭州市众邦文化传媒有限公司
河南千田教育科技有限公司
郑州生之涯教育科技有限公司';
$arr = explode("\n",$addrs);


use Addrparser\Parser;
$parser = new Parser();

foreach ($arr as $addr){
    $a = $parser->setAddress($addr)->getAll();
    var_dump($a['provinces'][0]->name);
    var_dump($a['cities'][0]->name);
    var_dump($a['counties'][0]->name);
}