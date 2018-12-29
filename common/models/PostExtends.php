<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post_extends".
 *
 * @property int $id 自增ID
 * @property int $post_id 文章id
 * @property int $browser 浏览量
 * @property int $collect 收藏量
 * @property int $praise 点赞
 * @property int $comment 评论
 */
class PostExtends extends base\Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_extends';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'browser', 'collect', 'praise', 'comment'], 'integer'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'browser' => 'Browser',
            'collect' => 'Collect',
            'praise' => 'Praise',
            'comment' => 'Comment',
        ];
    }
    
    /**
     * 统计文章访问次数
     * @param $cond
     * @param $attribute
     * @param $num
     */
    public function upCounter($cond, $attribute, $num)
    {
        if ($counter = $this->findOne($cond)) {
            $countData[$attribute] = $num;
            $counter->updateCounters($countData); //更新相关字段,将browser字段自增$num
            
        } else {
            $this->setAttributes($cond);
            $this->browser = 1;
            $this->save();
        }
    }
}
