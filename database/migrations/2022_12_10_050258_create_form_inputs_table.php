<?php

use App\Models\Form;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Form::class)->constrained();
            $table->string('name', 100)->comment('input name attribute');
            $table->string('label', 255)->comment('input label');
            $table->string('input_type', 50)->comment('input type attribute');
            $table->text('rules')->nullable()->comment('laravel rules for input');
            $table->text('choices')->nullable();
            $table->text('attributes')->nullable();
            $table->smallInteger('input_order')->default(0)->index();
            $table->timestamps();
            $table->unique(['form_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_inputs');
    }
};
