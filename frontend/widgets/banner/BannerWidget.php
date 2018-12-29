<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/24
 * Time: 22:37
 */

namespace frontend\widgets\banner;

use frontend\models\PostForm;
//use http\Url;
use yii\helpers\Url;
use yii\base\Widget;
use Yii;
use common\models\Posts;
use yii\data\Pagination;

class BannerWidget extends Widget
{
    public $item = [];

    
    
    public function init()
    {
        $this->item=[
            [
                'label'=>'demo',
                'image_url'=>'/static/images/banner/b_0.jpg', //相对路径以用以 /
                // 开头
                'url'=>['site/index'],
                'html'=>'',
                'active'=>'active',
            ],
            [
                'label'=>'demo',
                'image_url'=>'/static/images/banner/b_1.jpg',
                'url'=>['site/index'],
                'html'=>'',
//                'active'=>'active',
            ],
            [
                'label'=>'demo',
                'image_url'=>'/static/images/banner/b_2.jpg',
                'url'=>['site/index'],
                'html'=>'',
//                'active'=>'active',
            ],

        ];
        
    }
    
    public function run()
    {
        $data['items']=$this->item;
        return $this->render('index', compact('data'));
    }
}