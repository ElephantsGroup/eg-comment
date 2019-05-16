<?php

namespace elephantsGroup\comment\models;

use Yii;

/**
 * This is the model class for table "{{%eg_comment}}".
 *
 * @property integer $id
 * @property string $ip
 * @property string $name
 * @property integer $item_id
 * @property integer $item_version
 * @property integer $service_id
 * @property integer $comment_id
 * @property integer $user_id
 * @property string $email
 * @property string $subject
 * @property string $description
 * @property integer $status
 * @property string $update_time
 * @property string $creation_time
 */
class Comment extends \yii\db\ActiveRecord
{
    public static $_STATUS_CREATED = 0;
    public static $_STATUS_CONFIRMED = 1;
    public static $_STATUS_DENIED = 2;
    public static $_STATUS_ARCHIVED = 3;

    public static function getStatus()
    {
        return [
            self::$_STATUS_CREATED => Yii::t('app', 'Created'),
            self::$_STATUS_CONFIRMED => Yii::t('app', 'Confirmed'),
            self::$_STATUS_DENIED => Yii::t('app', 'Denied'),
            self::$_STATUS_ARCHIVED => Yii::t('app', 'Archived')
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eg_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip','name', 'email', 'subject', 'description'], 'trim'],
            [['user_id', 'item_id', 'item_version', 'service_id', 'level', 'comment_id', 'status'], 'integer'],
            [['email'], 'required'],
            [['email'], 'email'],
            [['description', 'name'], 'string'],
            [['update_time', 'creation_time'], 'date', 'format'=>'php:Y-m-d H:i:s'],
            [['ip'], 'string', 'max' => 32],
            [['email', 'subject'], 'string', 'max' => 128],
            [['status'], 'default', 'value' => self::$_STATUS_CREATED],
            [['item_version'], 'default', 'value' => 0],
            [['update_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['creation_time'], 'default', 'value' => (new \DateTime)->setTimestamp(time())->setTimezone(new \DateTimeZone('Iran'))->format('Y-m-d H:i:s')],
            [['status'], 'in', 'range' => array_keys(self::getStatus())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $base_module = Yii::$app->getModule('base');
        return [
            'id' => $base_module::t('ID'),
            'name' => $base_module::t('Name'),
            'ip' => $base_module::t('IP'),
            'item_id' => $base_module::t('Item ID'),
            'item_version' => $base_module::t('Item Version'),
            'service_id' => $base_module::t('Service ID'),
            'comment_id' => $base_module::t('Comment ID'),
            'level' => $base_module::t('Level'),
            'user_id' => $base_module::t('User ID'),
            'email' => $base_module::t('Email'),
            'subject' => $base_module::t('Subject'),
            'description' => $base_module::t('Description'),
            'status' => $base_module::t('Status'),
            'update_time' => $base_module::t('Update Time'),
            'creation_time' => $base_module::t('Creation Time'),
        ];
    }

    public function beforeSave($insert)
    {
        $date = new \DateTime();
        $date->setTimestamp(time());
        $date->setTimezone(new \DateTimezone('Iran'));
        $this->update_time = $date->format('Y-m-d H:i:s');
        if($this->isNewRecord)
            $this->creation_time = $date->format('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    public function getCanBeConfirmed()
    {
      return (($this->status == self::$_STATUS_CREATED || $this->status == self::$_STATUS_ARCHIVED || $this->status == self::$_STATUS_DENIED)
        && Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->user_id)
      );
    }

    public function Confirm()
    {
      if($this->getCanBeConfirmed())
      {
        $this->updateAttributes(['status' => self::$_STATUS_CONFIRMED]);
        return true;
      }
      return false;
    }

    public function getCanBeDenied()
    {
      return (($this->status == self::$_STATUS_CREATED || $this->status == self::$_STATUS_CONFIRMED || $this->status == self::$_STATUS_ARCHIVED)
        && Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->user_id)
      );
    }

    public function Deny()
    {
      if($this->getCanBeDenied())
      {
        $this->updateAttributes(['status' => self::$_STATUS_DENIED]);
        return true;
      }
      return false;
    }

    public function getCanBeArchived()
    {
      return (($this->status == self::$_STATUS_CREATED || $this->status == self::$_STATUS_CONFIRMED || $this->status == self::$_STATUS_DENIED)
        && Yii::$app->user &&(Yii::$app->user->identity->isAdmin || Yii::$app->user->id == $this->user_id)
      );
    }

    public function Archive()
    {
      if($this->getCanBeArchived())
      {
        $this->updateAttributes(['status' => self::$_STATUS_ARCHIVED]);
        return true;
      }
      return false;
    }

    /**
     * @inheritdoc
     * @return \common\models\CommentQuery the active query used by this AR class.
     */
    /*public static function find()
    {
        return new \common\models\CommentQuery(get_called_class());
    }*/
}
