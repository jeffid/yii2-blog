<?php
use frontend\widgets\banner\BannerWidget;
use frontend\widgets\feed\FeedWidget;
use yii\base\Widget;


$this->title='博客首页';
?>


<div class="row">
    <div class="col-lg-9">
        <!--轮播-->
        <?= BannerWidget::widget()?>
        <!--文章列表-->
        <?= frontend\widgets\post\PostWidget::widget()?>
        
    </div>
    
    <div class="col-lg-3">
        <!--反馈-->
        <?= FeedWidget::widget()?>
        <!--热门文章-->
        <?= \frontend\widgets\hot\HotWidget::widget()?>
        <!--标签-->
        <?= \frontend\widgets\tag\TagWidget::widget()?>
    </div>


</div>