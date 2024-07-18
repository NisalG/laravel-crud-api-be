# Laravel 11 Adavanced API BE CRUD

# Advanced Features Included
- Swagger
- Docker
- Testing
    - TDD Unit & Feature
    - Mocking External (Third Party) APIs and Services (CoinGecko Cryptocurrency Data API)
- Service Container and Service Providers
- Eloquent Techniques
    - One-to-One Relationship
    - Many-to-Many Relationship
    - Polymorphic Relationship
    - Eager Loading
    - Lazy Loading
    - Advanced Querying Techniques
        - Subqueries
        - Custom Scopes
    - Advanced Eloquent Techniques
        - Events and Observers
        - Mutators and Accessors
-"Below section - Coming in Next Week.................."
- API 
    - Versioning
    - Rate Limiting & Throtteling
    - API Request validation by extending Illuminate\Foundation\Http\FormRequest
    - API Resource for transforming Responses extending Illuminate\Http\Resources\Json\JsonResource 
    - Error Handling and Validation
- Security Best Practices used
    - Protect Against Common Vulnerabilities
        - Cross-Site Scripting (XSS)
        - Cross-Site Request Forgery (CSRF)
        - SQL Injection
    - Use of Laravel's Built-in Security Features
        - Authentication with Sanctum
        - Password Hashing
        - Rate Limiting & Throtteling
    - Additional Security Measures
        - HTTPS
        - Environment Configuration
        - Data Encryption
- Middlewares
    - Role-Based Access Control Middleware
    - Logging User Activity Middleware
    - Throttle Middleware with Custom Limits
    - Localization Middleware
    - CORS Middleware
- Scheduling
    - Scheduling Queued Jobs
    - Scheduling Artisan Commands
- Caching with Redis
- Mails 
    - Uses Mailable, Queueable, SerializesModels, Envelope, Content etc.
- Notifications
    - Events
    - Listeners
    - Queueable
    - ServiceProvider
    - DB tables
- Event Broadcasting (WebSockets)
- AWS services
    - AWS SDK for PHP
    - File Storage - AWS S3
    - Parameter Store
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3 will be used)
    - Elasticbeanstalk Extensions (.ebexensions)
    - SES (To do)
    - SQS (To do)
- Customized Exceptions Handling with Json Responses, Logging and Sentry integration
- Centralized Application Constants
- Migrations and Seeding

# Relevant Artisan commands and Source files for above Advance Features
- Swagger
    - Swagger UI library to generate interactive documentation - commands:
        - `npm install swagger-ui-dist`
        - `composer require darkaonline/l5-swagger tymon/jwt-auth`
        - `php artisan vendor:publish --provider=”Tymon\JWTAuth\Providers\LaravelServiceProvider”`
    - Generate the Swagger documentation: `php artisan l5-swagger:generate`
    - Access Swagger API Documentation URL: `http://your-app-url/api/documentation`
- Docker
    - Files:
        - Dockerfile
        - docker-compose.yml
        - docker-compose-sample.yml (another sample file with set of different commands)
    - Dockerfile commands:
        - `docker build`
        - `docker run`
    - docker-compose.yml commands:
        - `docker-compose up`
        - `docker-compose down`
        - `docker-compose ps`
        - `docker-compose build`
        - `docker ps` (all containers)
        - `docker ps -a` (all containers, including stopped)
        - `docker stop <container_id>`
        - `docker rm <container_id>`
        - `docker images`
        - `docker pull <image_name>`
        - `docker exec -it <container_id> bash` (enter running container)
        - `docker commit <container_id> <new_image_name>` (create new image from container)
- Unit & Feature Testing (TDD)
    - Common commands:
        - Make: `php artisan make:test FaqsTableSeederTest`
        - Run all: `php artisan test`
        - To run a specific test class, provide the path to the test file: `php artisan test --filter ExampleTest`
        - Run a Specific Test Method: `php artisan test --filter 'ExampleTest::testBasicExample'`
    - Enable Detailed Error Messages in Tests:
        - Check Log files
        - Update phpunit.xml: `<env name="APP_DEBUG" value="true"/>`
        - Create File: `tests/CreatesApplication.php`
        - Modify the exception handling in your TestCase class to throw exceptions instead of rendering them. See tests\TestCase.php
    - Post Controller testing:
        - `php artisan make:factory PostFactory --model=Post`
        - Change `database\factories\PostFactory.php`
        - Make sure your Post model uses the HasFactory trait.
        - Test: `php artisan test --filter 'PostControllerTest::it_can_list_all_posts'`
    - Mocking External (Third Party) APIs and Services (CoinGecko Cryptocurrency Data API)
        - Create a Service for CoinGecko API: app/Services/CoinGeckoService.php
        - Create a Controller: app/Http/Controllers/CoinGeckoController.php
        - Define Routes: routes/api.php
        - Get Cryptocurrency Data by URL: http://127.0.0.1:8000/api/cryptocurrency/bitcoin
        - Get Market Data by URL: http://127.0.0.1:8000/api/market-data
        - Create Unit Test Cases for the Service: tests/Unit/CoinGeckoServiceTest.php
        - Create Feature Test Cases for the Controller: tests/Feature/CoinGeckoControllerTest.php
        - Run the Tests: `php artisan test` 
- Service Container and Service Providers
    - Service Class: app/Services/S3Service.php
    - Service Provider: 
        - Artisan command: `php artisan make:provider S3ServiceProvider`
        - File: app/Providers/S3ServiceProvider.php
    - Register the Service Provider in config/app.php
    - Use in a Controller: app/Http/Controllers/S3Controller.php
- 

# How to run the application 
- Install required Composer packages using: `composer i`
- Set proper database configuration in `.env`
- Run migration: `php artisan migrate`
- Run seeder: `php artisan db:seed`
- Run application locally: `php artisan serve`
- Postman collection useful for testing the API end points is available in `postman-collection` directory
- Access Swagger API Documentation URL: `http://your-app-url/api/documentation`


##########################################################################################################
#
#
# Larevl Default
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
