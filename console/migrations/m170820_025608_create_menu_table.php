<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170820_025608_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'label' =>$this->string()->comment('菜单名称'),
            'parent_id'=>$this->integer()->comment('父级id'),
            'url'=>$this->string()->comment('路由'),
            'sort'=>$this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
