<?php

namespace common\models;

use Yii;
use \common\models\base\Base;

/**
 * This is the model class for table "cats".
 *
 * @property int $id 自增ID
 * @property string $cat_name 分类名称
 */
class Cats extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cats';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_name'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_name' => 'Cat Name',
        ];
    }
    
    public static function getAllCats()
    {
        
        if ($res = self::find()->asArray()->all()) {
            foreach ($res as $item) {
                $cat[$item['id']] = $item['cat_name'];
            }
        } else {
            $cat = ['0' => '暂无分类'];
        }
    
        return $cat;
    }
}
