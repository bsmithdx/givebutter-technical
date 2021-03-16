<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

##GiveButter Contacts Assignment

A few notes on my implementation and some things I would improve given more time:
1. I decided to use json columns to store the emails and phone numbers. Each email and phone number is represented 
   as a single json object with a property for either the email or phone number and a "primary" property that can be 
   true or false to 
   denote if that email/phone number is the primary one or not. This approach avoided having to save a "primary" 
   email or phone number in its own database field, but does end up requiring additional validation in order to 
   maintain a single "primary" attribute of each type at a time (most of which I decided was beyond the scope of 
   this assignment). Given more time that would be the first thing I would improve.
1. I implemented some basic duplication logic only checking against the first and last names already being in the 
   system already. Given more time I would probably add additional logic to test for either all emails and phone 
   numbers in json being the same as well or perhaps just checking to make sure that "primary" emails and phone 
   numbers were not already in the system.
1. I only implemented the API actions for the contacts, emails, and phone-numbers resources that were required in 
   the prompt. Obviously for a real-world system you'd most likely want additional http actions implemented in order to 
   GET the saved resources.
1. I included instructions below on how to run the Sail docker local environment for testing 
   the application if you'd like.
1. I took a TDD approach and implemented some basic PHPUnit unit tests for each endpoints and HTTP action as I 
   implemented them. Instructions on how to run them in the local environment are also included below. Additional 
   unit tests covering additional possible validation and other errors for each endpoint/action would also be an 
   easy way to improve the code.
1. I loosely modeled the api endpoints for modifying emails and phone numbers on nested resources in 
   the [json api spec](https://jsonapi.org/). You should be able to use POST, PATCH, and DELETE to set the resource 
   list as a whole, add a 
   single resource, or delete a single resource respectively for a contact. You can look in the unit tests for 
   examples of how 
   these are used.
   
Let me know if you have any additional questions or comments on the assignment.
   
Cheers,
[Brendan Smith]('mailto:brendan.smith0325@gmail.com')
   

## Local Environment

We are using the Laravel Sail Docker environment for local development. This is a series of Docker containers
orchestrated with Docker Compose (docker-compose.yml) with the added benefit of a universal `sail` command provided by a
custom bash script. This command makes calling services across the dev environment easier by abstracting away a lot of
the more verbose Docker Compose commands. The `sail` script in the project root is a modified version of the `sail` bash 
script located at 
`/vendor/bin/sail`, which falls back to executing that original script. Any further customizations to Sail commands 
should be made in this file. More information about Laravel Sail and available commands is available [here](https://laravel.com/docs/8.x/sail).

### Setup:
1. First install dependencies using Composer:
    1. If you have Composer installed globally you can run `composer install --ignore-platform-reqs` from the project root
    2. OR you can run the following command to use a Composer docker container for a one-time installation:
   ```shell
   docker run --rm \
   -v $(pwd):/opt \
   -w /opt \
   laravelsail/php80-composer:latest \
   composer install --ignore-platform-reqs
   ```
   Note: If you cannot successfully copy and past the code above then you can copy it from the Sail [docs](https://laravel.com/docs/8.x/sail#installing-composer-dependencies-for-existing-projects).
1. Run `./sail composer run local` to copy .env file, generate a Laravel app key, and generate some IDE helper files.
1. Run `./sail up -d` to bring up the docker environment in the background
1. Run `./sail artisan migrate` 
1. Access the local environment at `http://localhost:80`
1. Additional docker commands via Sail:
    - `./sail stop` Stop the docker containers in the local environment.
    - `./sail down` Stop and destroy the containers in the local environment.
    - `./sail down -v` Adding the `-v` flag will remove all volumes as well (permanently remove local storage for 
      containers like MySQL, MongoDB, and DynamoDB).

### Sail Commands:
You can run many CLI services on the docker environment by simply prefixing with `./sail`
For example to run `composer install` in the docker environment simply run `./sail composer install`. The following 
is a list of CLI commands that can be appended to `./sail`:
- `mongo`
- `docker-compose`
- `php`
- `composer`
- `artisan`
- `test`
- `tinker`
- `npm`
- `mysql`
- `shell`
- `root-shell`
- `share`

More information and additional commands are available in the [Laravel Sail documentaion](https://laravel.com/docs/8.x/sail)

### Debugging:

####Xdebug
Xdebug is enabled by default. To turn off Xdebug simply set `XDEBUG=false` in your .env file and re-build the docker 
container: `./sail up --build -d`.
