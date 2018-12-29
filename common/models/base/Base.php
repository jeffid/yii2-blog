<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/19
 * Time: 16:43
 */

namespace common\models\base;


use yii\db\ActiveRecord;

class Base extends ActiveRecord
{
    public function getPages($query, $curPage = 1, $pageSize = 10, $search = null)
    {
        if ($search) {
            $query = $query->andFilerWhere($search);
        }
        $data['count'] = $query->count();
        
        if (($data['count'])) {
            // 若超过实际页数，不取curPage为当前页
            $curPage = (($max = ceil($data['count'] / $pageSize)) < $curPage)
                ? $max : $curPage;
            // 当前页
            $data['curPage'] = $curPage;
            // 每页显示条数
            $data['pageSize'] = $pageSize;
            // 当前页第一条
            $data['start'] = ($curPage - 1) * $pageSize + 1;
            // 当前页最后一条
            $data['end'] = ($max == $curPage)
                ? $data['count']
                : ($curPage - 1) * $pageSize + $pageSize;
            // 数据
            $data['data'] = $query
                ->offset(($curPage - 1) * $pageSize)
                ->limit($pageSize)
                ->asArray()
                ->all();
            return $data;
            
        } else {
            return [
                'count' => 0,
                'curPage' => $curPage,
                'pageSize' => $pageSize,
                'start' => 0,
                'end' => 0,
                'data' => [],
            ];
            
        }
        
        
    }
}