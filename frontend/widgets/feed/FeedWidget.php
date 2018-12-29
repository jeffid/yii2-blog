<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/24
 * Time: 22:37
 */

namespace frontend\widgets\feed;

use frontend\models\FeedsForm;
use yii\base\Widget;
use Yii;


class FeedWidget extends Widget
{
    
    public function run()
    {
        $ff=new FeedsForm();
        $data['feed']=$ff->getList();
        return $this->render('index', compact('data'));
    }
}