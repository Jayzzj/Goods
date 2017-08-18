<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170816_032006_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->comment('用户名')->notNull()->unique(),
            'auth_key' => $this->string(32)->comment('认证')->notNull(),
            'password_hash' => $this->string()->comment('用户密码')->notNull(),
            'password_reset_token' => $this->string()->comment('重置密码验证')->unique(),
            'email' => $this->string()->comment('邮箱')->notNull()->unique(),

            'status' => $this->smallInteger()->comment('状态值')->notNull()->defaultValue(10),
            'created_at' => $this->integer()->comment('创建时间')->notNull(),
            'updated_at' => $this->integer()->comment('修改时间')->notNull(),
            'last_login_time'=>$this->date()->comment('最后登录时间')->notNull(),
            'last_login_ip'=>$this->integer()->comment('最后登录ip')->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
