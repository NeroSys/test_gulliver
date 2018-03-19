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
            'codePostal' => Yii::t('app', 'Code Postal'),
            'country' => Yii::t('app', 'Country'),
            'city' => Yii::t('app', 'City'),
            'street' => Yii::t('app', 'Street'),
            'houseNumber' => Yii::t('app', 'House Number'),
            'flatNumber' => Yii::t('app', 'Flat Number'),
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
