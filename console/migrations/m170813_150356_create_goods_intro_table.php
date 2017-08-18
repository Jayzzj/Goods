<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170813_150356_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
//            'id' => $this->primaryKey(),
            //goods_id	int	商品id
            'goods_id'=>$this->integer(),
            //content	text	商品描述
            'coontent'=>$this->text()->comment('商品描述')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
