<?php

use yii\db\Migration;

/**
 * Class m250117_231924_loan_requests
 */
class m250117_231924_loan_requests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        // Создание типа ENUM для статуса
        $this->execute("CREATE TYPE loan_request_status AS ENUM ('pending', 'approved', 'declined');");

        // Создание таблицы заявок
        $this->createTable('{{%loan_requests}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'term' => $this->integer()->notNull(),
            'status' => "loan_request_status DEFAULT 'pending'",
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->execute("DROP TYPE loan_request_status");
        $this->dropTable('{{%loan_requests}}');
    }
}
