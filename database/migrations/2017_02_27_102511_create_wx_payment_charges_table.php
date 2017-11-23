<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWxPaymentChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wx_payment_charges', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('pid')->unsigned()->default(0)->comment('第三方ID');
			$table->string('out_trade_no', 32)->comment('商户订单号');
			$table->string('transaction_id', 32)->default('')->comment('微信支付订单号');
			$table->boolean('pay_status')->default(1)->comment('支付状态1:待支付2:支付成功3:支付失败');
			$table->boolean('refund_status')->default(1)->comment('退款状态1:未退款2:退款成功');
			$table->integer('total_fee')->comment('支付总金额，单位为分');
			$table->integer('refund_fee')->default(0)->comment('退款总金额，单位为分');
			$table->string('device_info', 32)->default('')->comment('设备号');
			$table->string('body', 128)->comment('商品描述');
			$table->string('detail', 6000)->default('')->comment('商品详情');
			$table->string('attach', 127)->default('')->comment('附加数据');
			$table->string('fee_type', 16)->default('CNY')->comment('货币类型');
			$table->string('spbill_create_ip', 16)->comment('终端IP');
			$table->string('time_start', 14)->default('')->comment('交易起始时间，格式为:yyyyMMddHHmmss');
			$table->string('time_expire', 14)->default('')->comment('订单失效时间，格式为:yyyyMMddHHmmss');
			$table->string('time_finish', 14)->nullable()->default('');
			$table->string('notify_url', 256)->default('')->comment('通知地址');
			$table->string('trade_type', 16)->comment('交易类型');
			$table->string('product_id', 32)->default('')->comment('商品ID');
			$table->string('limit_pay', 32)->default('')->comment('指定支付方式');
			$table->string('openid', 128)->default('')->comment('用户标识');
			$table->char('is_subscribe', 1)->nullable()->default('');
			$table->string('prepay_id', 64)->default('')->comment('预支付交易会话标识');
			$table->string('code_url', 64)->default('')->comment('二维码链接');
			$table->string('return_code', 16)->default('')->comment('返回状态码');
			$table->string('return_msg', 128)->default('')->comment('返回信息');
			$table->string('result_code', 16)->default('')->comment('业务结果');
			$table->string('err_code', 32)->default('')->comment('错误代码');
			$table->string('err_code_des', 128)->default('')->comment('错误代码描述');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('wx_payment_charges');
	}

}
