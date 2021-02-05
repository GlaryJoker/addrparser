<?php
namespace addrparser;

class Duplicate{


    public static function removeDuplicate(array $array = [])
    {
        if (!$array) {
            return [];
        }

        if (count($array) === 1) {
            return $array;
        }

        $cityCodes = array_unique(array_column($array, 'code'));
        $exitsCode = [];
        $resultArray = [];

        //先获取score为0的，然后去除其他的，如果没有为0的，返回其他匹配到数字
        $filterArray = array_filter($array, function ($item) {
            return $item->score === 0;
        });

        $filterArray = self::compareWords($filterArray);

        $rCount = count($filterArray);
        if ($rCount === 1) {
            return $filterArray;
        }


        foreach ($array as $city) {
            if (in_array($city->code, $cityCodes) && !in_array($city->code, $exitsCode)) {
                array_push($exitsCode, $city->code);
                $resultArray[] = $city;
                continue;
            }
        }


        return $resultArray;
    }


    /**
     * 比较单词之间相似
     * @author www.iplayio.cn
     * @since 2021/2/3 18:25
     */
    public static function compareWords(array $words){
        foreach ($words as $index => $word){
            $word->str_length = mb_strlen($word->name);
        }
        $last_names = array_column($words,'str_length');
        array_multisort($last_names,SORT_DESC,$words);
        $newWords = [];
        foreach ($words as $word){
            if($word->str_length === $words[0]->str_length){
                $newWords[] = $word;
            }
        }
        return $newWords;
    }

}