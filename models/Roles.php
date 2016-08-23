<?php

namespace app\models;

/**
 * This is the model class for table "roles".
 *
 * @property string $id
 * @property string $role
 * @property ProjectUser[] $projectUsers
 */
class Roles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role'], 'string', 'max' => 60],
            [['role'], 'required'],
            [['role'], 'unique', 'targetAttribute' => ['role']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Role',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::className(), ['role_id' => 'id']);
    }
}
