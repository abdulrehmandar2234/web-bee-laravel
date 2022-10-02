<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('movies', function($table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->dateTime('duration');
            $table->string('language');
            $table->dateTime('release_date');
            $table->string('country');
            $table->string('genre');
            $table->timestamps();
        });

        Schema::create('cities', function($table) {
            $table->id();
            $table->string('name');
            $table->string('state');
            $table->string('zip_code');
            $table->timestamps();
        });

        Schema::create('cinemas', function($table) {
            $table->id();
            $table->dateTime('name');
            $table->integer('total_cinema_hall');
            $table->foreignId('city_id')->constrained('cities');
            $table->timestamps();
        });

        Schema::create('cinema_halls', function($table) {
            $table->id();
            $table->dateTime('name');
            $table->integer('total_seats');
            $table->foreignId('cinema_id')->constrained('cities');
            $table->timestamps();
        });

        Schema::create('cinema_seats', function($table) {
            $table->id();
            $table->integer('seat_number');
            $table->integer('type');
            $table->integer('percentage');
            $table->foreignId('cinema_hall_id')->constrained('cinema_halls');
            $table->timestamps();
        });

        Schema::create('shows', function($table) {
            $table->id();
            $table->dateTime('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->foreignId('movie_id')->constrained('movies');
            $table->foreignId('cinema_hall_id')->constrained('cinema_halls');
            $table->timestamps();
        });

        Schema::create('bookings', function($table) {
            $table->id();
            $table->integer('no_of_seats');
            $table->integer('status');
            $table->foreignId('show_id')->constrained('shows');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::create('show_seats', function($table) {
            $table->id();
            $table->integer('status');
            $table->float('price');
            $table->foreignId('show_id')->constrained('shows');
            $table->foreignId('cinema_seat_id')->constrained('cinema_seats');
            $table->foreignId('booking_id')->constrained('bookings');
            $table->timestamps();
        });

        Schema::create('payments', function($table) {
            $table->id();
            $table->float('amount');
            $table->integer('payment_method');
            $table->foreignId('booking_id')->constrained('bookings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
