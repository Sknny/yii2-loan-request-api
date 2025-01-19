<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "loan_requests".
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $term
 * @property string $status
 * @property string $created_at
 */
class LoanRequest extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%loan_requests}}';
    }

    public function rules(): array
    {
        return [
            [['user_id', 'amount', 'term'], 'required'],
            [['user_id', 'amount', 'term'], 'integer'],
            ['amount', 'compare', 'compareValue' => 0, 'operator' => '>'],
            ['term', 'compare', 'compareValue' => 0, 'operator' => '>'],
            ['status', 'in', 'range' => ['approved', 'declined', 'pending']],
        ];
    }
}