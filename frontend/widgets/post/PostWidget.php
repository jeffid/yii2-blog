<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/24
 * Time: 22:37
 */

namespace frontend\widgets\post;

use frontend\models\PostForm;
//use http\Url;
use yii\helpers\Url;
use yii\base\Widget;
use Yii;
use common\models\Posts;
use yii\data\Pagination;

class PostWidget extends Widget
{
    public $title = '';
    public $limit = 6;
    public $more = true; //是否显示更多
    public $page = true; //是否显示分页
    
    
    public function run()
    {
        $curPage = Yii::$app->request->get('page', 1);
        $cond = ['=', 'is_valid', Posts::VALID];
        $list = PostForm::getList($cond, $curPage, $this->limit); //拿到对应范围内的文章数据
        $ret = [
            'title' => $this->title ?: '最新文章',
            'more' => Url::to(['post/index']),
            'body' => $list['data'] ?: [],
        ];
        
        if ($this->page)
            //创建一个分页对象,第3个参数是当前页码,默认采用参数page值
            //https://www.yiichina.com/doc/guide/2.0/output-pagination?language=zh-cn
            $ret['page'] = new Pagination([
                'totalCount' => $list['count'],
                'pageSize' => $list['pageSize']
            ]);
        
//        var_dump($ret);die;
        return $this->render('index', ['data' => $ret]);
    }
}