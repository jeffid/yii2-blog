<?php

namespace common\models;

use common\models\base\Base;
use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id 自增ID
 * @property string $title 标题
 * @property string $summary 摘要
 * @property string $content 内容
 * @property string $label_img 标签图
 * @property int $cat_id 分类id
 * @property int $user_id 用户id
 * @property string $user_name 用户名
 * @property int $is_valid 是否有效：0-未发布 1-已发布
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Posts extends Base
{
    const VALID = 1;
    const INVALID = 0;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['cat_id', 'user_id', 'created_at', 'updated_at','is_valid'], 'integer'],
            [['title', 'summary', 'label_img', 'user_name'], 'string', 'max' => 255],
//            [['is_valid'], 'string', 'max' => 1],
        ];
    }
    
    /**
     * 相应表单字段的名称
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '文章ID',
            'title' => '标题',
            'summary' => '摘要',
            'content' => '内容',
            'label_img' => '首图',
            'cat_id' => '分类ID',
            'user_id' => '用户ID',
            'user_name' => '作者名',
            'is_valid' => '是否可用',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            
            'cat.cat_name'=>'所属分类', //关联关系
        ];
    }
    
    public function getRelate()
    {
        return $this->hasMany(RelationPostTags::className(),['post_id'=>'id']);
    }
    
    /**
     * 一对一关系
     * 'post_id'是参数一模型的字段,id 是本模型(posts)的字段
     * @return \yii\db\ActiveQuery
     */
    public function getExtend()
    {
        return $this->hasOne(PostExtends::className(),['post_id'=>'id']);
    }
    
    public function getCat()
    {
        return $this->hasOne(Cats::className(),['id'=>'cat_id']);
    }
}
