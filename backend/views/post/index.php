<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">
    
    <!--    <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <p>
        <?= Html::a('Create Posts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'id',
            'title' => [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<a href="' . Url::to(['post/view', 'id' => $model->id]) . '">' . $model->title . '</a>';
                }
            ],
            'summary',
//            'content:ntext',
            'label_img',
            'cat.cat_name' => [ //关联关系
                'label' => '分类',
            ],
            //'user_id',
            'user_name',
            'is_valid' => [
                'label' => '状态',
                'attribute' => 'is_valid',
                'filter' => ['0' => '无效', '1' => '有效'], //下拉过滤选项
                'value' => function ($model) {
                    return ($model->is_valid == 1) ? '有效' : '无效';
                },
            ],
            'created_at',
            //'updated_at',
            
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
