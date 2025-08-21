<?php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('photo')->nullable()->change();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('photo')->nullable(false)->change();
    });
}