<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/24
 * Time: 22:37
 */

namespace frontend\widgets\tag;

use common\models\PostExtends;
use common\models\Posts;
use common\models\Tags;
use yii\base\Widget;
use Yii;
use yii\db\Query;
use yii\helpers\Url;


class TagWidget extends Widget
{
    
    public $title = '';
    
    public $limit = 20;
    
    public function run()
    {
        $res = Tags::find()
            ->orderBy(['post_num' => SORT_DESC])
            ->limit($this->limit)
            ->all();
        
        $data = [
            'title' => $this->title ?: '标签云',
            'body' => $res ?: [],
        ];
        
        return $this->render('index', compact('data'));
    }
}