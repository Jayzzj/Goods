<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170821_034345_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
//            id	primaryKey
//            username	varchar(50)	用户名
//            auth_key	varchar(32)
//            password_hash	varchar(100)	密码（密文）
//            email	varchar(100)	邮箱
//            tel	char(11)	电话
//            last_login_time	int	最后登录时间
//            last_login_ip	int	最后登录ip
//            status	int(1)	状态（1正常，0删除）
//            created_at	int	添加时间
//            updated_at	int	修改时间
            'username' => $this->string()->comment('用户名')->notNull()->unique(),
            'auth_key' => $this->string(32)->comment('认证')->notNull(),
            'password_hash' => $this->string()->comment('用户密码')->notNull(),
            'email' => $this->string()->comment('邮箱')->notNull()->unique(),
            'tel'=>$this->string(11)->comment('电话'),
            'last_login_time'=>$this->date()->comment('最后登录时间')->notNull(),
            'last_login_ip'=>$this->integer()->comment('最后登录ip')->notNull(),
            'status' => $this->integer(1)->comment('状态')->notNull()->defaultValue(1),
            //'password_reset_token' => $this->string()->comment('重置密码验证')->unique(),
            'created_at' => $this->integer()->comment('创建时间')->notNull(),
            'updated_at' => $this->integer()->comment('修改时间')->notNull(),



        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
