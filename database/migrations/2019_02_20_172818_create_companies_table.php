<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations. 
     * Create a database table called 'companies' 
     * with 'id', 'company_name' 'company_type', 'industry_type', 'website', 'tagline', 'logo', 'email', 'password', 'telephone_number', 'token' and 'timestamps' attributes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('company_type');
            $table->string('industry_type');
            
            $table->string('website')->nullable();
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable(); 

            $table->string('email')->unique();
            $table->string('password');
            $table->integer('telephone_number');
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. Remove the database table called 'companies'
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
