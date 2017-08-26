<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170821_095310_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('收货人'),
            'cmbProvince'=>$this->string(50)->comment('省份'),
            'cmbCity'=>$this->string('50')->comment('区县'),
            'cmbArea'=>$this->string('50')->comment('区县名称'),
            'address'=>$this->string(255)->comment('地址'),
            'tel'=>$this->integer(11)->comment('联系电话'),
            'status'=>$this->integer(1)->comment('状态'),
            'member_id'=>$this->integer()->comment('用户id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
