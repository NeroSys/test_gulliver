<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $codePostal
 * @property string $country
 * @property string $city
 * @property string $street
 * @property int $houseNumber
 * @property int $flatNumber
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'codePostal', 'houseNumber', 'flatNumber'], 'integer'],
            [['country', 'city', 'street'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['codePostal', 'trim'],
            ['codePostal', 'required'],
            ['country', 'trim'],
            ['country', 'required'],
            ['city', 'trim'],
            ['city', 'required'],
            ['street', 'trim'],
            ['street', 'required'],
            ['houseNumber', 'trim'],
            ['houseNumber', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'codePostal' => Yii::t('app', 'Индекс'),
            'country' => Yii::t('app', 'Страна'),
            'city' => Yii::t('app', 'Город'),
            'street' => Yii::t('app', 'Улица'),
            'houseNumber' => Yii::t('app', '№ дома'),
            'flatNumber' => Yii::t('app', '№ квартиры'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
