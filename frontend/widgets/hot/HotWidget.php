<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/24
 * Time: 22:37
 */

namespace frontend\widgets\hot;

use common\models\PostExtends;
use common\models\Posts;
use yii\base\Widget;
use Yii;
use yii\db\Query;
use yii\helpers\Url;


class HotWidget extends Widget
{
    public $title = '';
    public $limit = 6;
    
    
    public function run()
    {
        //获取浏览数
        $ret = (new Query())
            ->from(['a' => PostExtends::tableName()])
            ->join('LEFT JOIN', ['b' => Posts::tableName()], 'a.post_id = b.id')
            ->where('b.is_valid =' . Posts::VALID)
            ->select('a.browser, b.id, b.title')
            ->orderBy(['browser' => SORT_DESC, 'id' => SORT_DESC])
            ->limit($this->limit)
            ->all();
        
        $data = [
            'title' => $this->title ?: '热门浏览',
            'more' => Url::to(['post/index', 'sort' => 'hot']),
            'body' => $ret ?: [],
        ];
        
        return $this->render('index', compact('data'));
    }
}