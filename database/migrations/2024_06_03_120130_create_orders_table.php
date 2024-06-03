<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            if (!Schema::hasColumn('orders', 'waiter_id')) {
                $table->unsignedBigInteger('waiter_id');
                $table->foreign('waiter_id')->references('id')->on('users');
            }
            $table->decimal('total', 8, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->timestamp('delivered_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
