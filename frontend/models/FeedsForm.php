<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Feeds;

/**
 * This is the model class for table "feeds".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property string $content 内容
 * @property int $created_at 创建时间
 */
class FeedsForm extends Model
{
    public $content;
    
    public $_lastError;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['content', 'required'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'content' => '内容',
        ];
    }
    
    public function create()
    {
        try{
            $model = new Feeds();
            $model->user_id = Yii::$app->user->identity->id;
            $model->content = $this->content;
            $model->created_at = time();
            if(!$model->save())
                throw new \Exception('保存失败::'. json_encode($model->errors, JSON_UNESCAPED_UNICODE));
            
            return true;
        }catch (\Exception $e){
            $this->_lastError = $e->getMessage();
            return false;
        }
    }
    
    public function getList()
    {
        $model = new Feeds();
        $res = $model
            ->find()
            ->limit(10)
            ->with('user')
            ->orderBy(['id'=>SORT_DESC])
            ->asArray()
            ->all();
        
        return $res?:[];
    }
}
