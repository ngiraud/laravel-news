<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('comments', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('news_id')->unsigned()->index();
			$table->text('author_name');
			$table->string('author_email')->index();
			$table->string('author_url')->nullable();
			$table->integer('user_id')->unsigned()->index()->default(0);
			$table->tinyInteger('approved_status')->index()->default(1);
			$table->longText('content');
			$table->integer('parent_id')->unsigned()->index()->default(0);
			$table->timestamps();
			$table->softDeletes();
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('comments');
	}
}
