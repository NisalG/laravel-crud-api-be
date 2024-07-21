# Laravel 11 Adavanced API BE CRUD

# Advance Features Included (Please check below for detailed guide)
- **Swagger**
- **Docker**
- **Testing**
    - TDD Unit & Feature
    - Mocking External (Third Party) APIs and Services (CoinGecko Cryptocurrency Data API)
- **Service Container and Service Providers**
- **Eloquent Techniques**
    - One-to-One Relationship: 
    - Many-to-Many Relationship
    - Polymorphic Relationship
    - Eager Loading
    - Lazy Loading
    - Advance Querying Techniques
        - Subqueries
        - Custom Scopes
    - Advance Eloquent Techniques
        - Mutators
        - Accessors
        - Events and Observers
- **API**
    - Authentication with Sanctum
    - Versioning
    - Rate Limiting & Throtteling
    - API Request validation
    - API Resource for transforming Responses
- **Advance Migrations**
- **Customized Exceptions Handling with Json Responses, Logging and Sentry integration**
- **Centralized Application Constants**  
- **Security Best Practices used**
    - Protect Against Common Vulnerabilities
        - Cross-Site Scripting (XSS)
        - Cross-Site Request Forgery (CSRF)
        - SQL Injection
    - Use of Laravel's Built-in Security Features
        - Authentication with Sanctum
            - Sanctum Token Security
        - Password Hashing
        - Rate Limiting & Throtteling
    - Additional Security Measures
        - HTTPS
        - Environment Configuration
        - Data Encryption
- **Middlewares**
    - Role-Based Access Control Middleware
    - Logging User Activity Middleware
    - Throttle Middleware with Custom Limits
    - Localization Middleware
    - CORS Middleware
- "Below sections - Coming in Next Week.................."  
- **Scheduling**
    - Scheduling Queued Jobs
    - Scheduling Artisan Commands
- **Caching with Redis**
- **Mails** 
    - Uses Mailable, Queueable, SerializesModels, Envelope, Content etc.
- **Notifications**
    - Events
    - Listeners
    - Queueable
    - ServiceProvider
    - DB tables
- **Event Broadcasting (WebSockets)**
- **AWS services**
    - AWS SDK for PHP
    - File Storage - AWS S3
    - Parameter Store
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3 will be used)
    - Elasticbeanstalk Extensions (.ebexensions)
    - SES (To do)
    - SQS (To do)

# Guide, Steps, Artisan commands and Source files for above Advance Features
- **Swagger**
    - Swagger UI library to generate interactive documentation - commands:
        - `npm install swagger-ui-dist`
        - `composer require darkaonline/l5-swagger tymon/jwt-auth`
        - `php artisan vendor:publish --provider=”Tymon\JWTAuth\Providers\LaravelServiceProvider”`
    - Generate the Swagger documentation: `php artisan l5-swagger:generate`
    - Access Swagger API Documentation URL: `http://your-app-url/api/documentation`
- **Docker**
    - Files:
        - `Dockerfile`
        - `docker-compose.yml`
        - `docker-compose-sample.yml` (another sample file with set of different commands)
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
- **Unit & Feature Testing (TDD)**
    - Common commands:
        - Make: `php artisan make:test FaqsTableSeederTest`
        - Run all: `php artisan test`
        - To run a specific test class, provide the path to the test file: `php artisan test --filter ExampleTest`
        - Run a Specific Test Method: `php artisan test --filter 'ExampleTest::testBasicExample'`
    - Enable Detailed Error Messages in Tests:
        - Check Log files
        - Update phpunit.xml: `<env name="APP_DEBUG" value="true"/>`
        - Create File: `tests/CreatesApplication.php`
        - Modify the exception handling in your TestCase class to throw exceptions instead of rendering them. See `tests\TestCase.php`
    - Post Controller testing:
        - `php artisan make:factory PostFactory --model=Post`
        - Change `database\factories\PostFactory.php`
        - Make sure your Post model uses the HasFactory trait.
        - Test: `php artisan test --filter 'PostControllerTest::it_can_list_all_posts'`
    - Mocking External (Third Party) APIs and Services (CoinGecko Cryptocurrency Data API)
        - Create a Service for CoinGecko API: `app/Services/CoinGeckoService.php`
        - Create a Controller: `app/Http/Controllers/CoinGeckoController.php`
        - Define Routes: `routes/api.php`
        - Get Cryptocurrency Data by URL: http://127.0.0.1:8000/api/cryptocurrency/bitcoin
        - Get Market Data by URL: http://127.0.0.1:8000/api/market-data
        - Create Unit Test Cases for the Service: `tests/Unit/CoinGeckoServiceTest.php`
        - Create Feature Test Cases for the Controller: `tests/Feature/CoinGeckoControllerTest.php`
        - Run the Tests: `php artisan test` 
- **Service Container and Service Providers**
    - Service Class: `app/Services/S3Service.php`
    - Service Provider: 
        - Artisan command: `php artisan make:provider S3ServiceProvider`
        - File: `app/Providers/S3ServiceProvider.php`
    - Register the Service Provider in `config/app.php`
    - Use in a Controller: `app/Http/Controllers/S3Controller.php`
- **Eloquent Techniques**
    - One-to-One Relationship:
        - `app\Models\CategoryDetail.php` >> `category()`
        - `app\Models\Category.php` >> `detail()`
        - Migration (not required but best practice to maintain relationships also in DB):
            - `database\migrations\2024_07_20_131424_create_category_details_table.php` >> `$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');`
    - One-to-Many Relationship:
        - `app\Models\Category.php` >> `posts()`
        - `app\Models\Post.php` >> `category()`
        - Migration (not required but best practice to maintain relationships also in DB):
            - `$table->unsignedBigInteger('category_id')->default(1); // 1 = uncategorized - if CSV doesn't have a category, default to uncategorized`
            `$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');`
    - Many-to-Many Relationship - uses a Pivot table
        - `app\Models\Tag.php` >> `posts()` >> `belongsToMany()`
        - `app\Models\Post.php` >> `tags()` >> `belongsToMany()`
        - Migration (required):
            - Create Pivot table - `post_tag`: `php artisan make:migration create_post_tag_table`
            - `database\migrations\2024_07_21_050652_create_post_tag_table.php` >> `$table->foreignId('post_id')->constrained()->onDelete('cascade');` 
            & `$table->foreignId('tag_id')->constrained()->onDelete('cascade');`
    - Has-Many-Through Relationship: A has-many-through relationship provides a convenient shortcut for accessing distant relations via an intermediate relation. E.g.: a Country has many Posts through a User.
        - `app\Models\Country.php` >> `posts()` >> `hasManyThrough()`
        - `app\Models\Post.php` >> `user()` >> `belongsTo()`
        - `app\Models\User.php`:
            - `country()` >> `belongsTo()` 
            - `posts()` >> `hasMany()`
        - Migration (not required but best practice to maintain relationships also in DB):
            - Nothing to add for relationship in: `database\migrations\2024_07_21_051334_create_countries_table.php`
            - `php artisan make:migration add_country_id_to_users_table`
                - `database\migrations\2024_07_21_052413_add_country_id_to_users_table.php`
            - `php artisan make:migration add_user_id_to_posts_table`
                - `database\migrations\2024_07_21_052857_add_user_id_to_posts_table.php`
    - Polymorphic Relationship: A polymorphic relationship allows a model to belong to more than one other model on a single association. E.g: Suppose both Posts and Categories can have Comments.
        - `app\Models\Comment.php` >> `commentable()`
        - `app\Models\Post.php` >> `comments()`
        - `app\Models\Category.php` >> `comments()`
        - Migration (not required but best practice to maintain relationships also in DB):
            - `database\migrations\2024_07_21_033147_create_comments_table.php` >> `$table->morphs('commentable');`. The `morphs()` method creates both `commentable_id` and `commentable_type` columns together.
        - Usage:
            - `app\Http\Controllers\Api\V2\CategoryController.php` >> `addCategoryComment()`
            - `app\Http\Controllers\Api\V2\PostController.php` >> `addCategoryComment()`
        - Also a sample available in Laravel generated `database\migrations\2024_06_01_083834_create_personal_access_tokens_table.php`
    - Eager Loading: `app\Http\Controllers\Api\V2\CategoryController.php`
    - Lazy Loading: `app\Http\Controllers\Api\V2\CategoryController.php`
    - Advance Querying Techniques
        - Subqueries: `app\Http\Controllers\Api\V2\CategoryController.php`  >> `getCategoriesWithPostsCount()`
        - Custom Scopes:
            - Create: `app\Models\Post.php` >> `scopePublished($query)`
            - Usage: `app\Http\Controllers\Api\V2\PostController.php` >> `getPublishedPosts(Request $request)`
    - Advance Eloquent Techniques
        - Mutators: `app\Models\Post.php` >> `setTitleAttribute($value)`
        - Accessors: `app\Models\Post.php` >> `getTitleAttribute($value)`
        - Events and Observers: Eloquent lifecycle events and observers in Laravel allow you to handle model lifecycle events, keeping your code clean and organized by separating concerns.
            - Eloquent Events: retrieved, creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored
            - Usage of Eloquent Events: Automatically setting attributes, sending notifications, logging activities, validating data, enforcing business rules
            - Benefits of Using Observers: Separation of concerns, reusability, organization, improved code readability, maintainability, centralized event handling logic
            - Create an Observer: `php artisan make:observer PostObserver --model=Post`
            - Created file: `App\Observers\PostObserver.php`
            - Register the Observer: `App\Providers\AppServiceProvider.php`
- **API**
    - Authentication with Sanctum
        - Commands:
            - `composer require laravel/sanctum`
            - `php artisan cache:clear`
            - `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
            - `php artisan migrate`
        - Model `app/Models/User.php` update to include the `HasApiTokens` trait
        - Route change: `routes/api.php`
        - AuthController:
            - `app\Http\Controllers\Api\V2\AuthController.php` >>  `register()` >> `$user()->createToken()`
            - `app\Http\Controllers\Api\V2\AuthController.php` >>  `login()` >> `auth()->user()->createToken()`
        - Client-Side React:
            - Make API request by passing User email, password to received token
            - Store the received token securely (e.g., in local storage) and include it in the Authorization header for subsequent authenticated requests
            - Token Security: Store tokens in cookies with the `HttpOnly` flag and `SameSite` attribute for better security.
    - Versioning
        - `app\Providers\RouteServiceProvider.php` >> `boot()`
        - `routes\api_v1.php`
        - `routes\api_v2.php`
        - `app\Http\Controllers\Api\V1\UserController.php`
        - `app\Http\Controllers\Api\V2\UserController.php`
        - Add `v1` or `v2` to Postman requests
    - Rate Limiting & Throtteling
        - `app\Providers\RouteServiceProvider.php`
        - `routes\api_v1.php`
        - Test it by setting ‘1’ to `app\Providers\RouteServiceProvider.php` >> `configureRateLimiting()` >> `Limit::perMinute(1)`
        - When accessed on Postman it will get `“429: Too Many Attempts.”`
    - API Request validation
        - By using Request validation, you don't need to use Validator class to validate in Controller Actions
        - `php artisan make:request CreateUserRequest`
            - `app\Http\Requests\CreateUserRequest.php` will be created extending `Illuminate\Foundation\Http\FormRequest`
        - Add validation rules to: `app\Http\Requests\CreateUserRequest.php` >> `rules()`
        - Use the CreateUserRequest in `app\Http\Controllers\Api\V2\UserController.php`
            - E.g.: `public function store(CreateUserRequest $request)`
    - API Resource for transforming Models into JSON responses.
        - `php artisan make:resource PostResource`
            - `app\Http\Resources\PostResource.php` will be created extending  `Illuminate\Http\Resources\Json\JsonResource`
        - Add fields to transform in `app\Http\Resources\PostResource.php` >> `toArray()`
        - Use the PostResource in `app\Http\Controllers\Api\V2\PostController.php`
            - E.g.1: `PostResource::collection($posts)`
            - E.g.2: `new PostResource($post)`
- **Advance Migrations**
    - Defining Relationships in Migrations 
        - It is not compulsory, but a good practice and try to do always. See above "Eloquent Techniques" relationships. 
            - Benifits (When to Use): Defining relationships in migrations ensures data integrity, enables automatic cascading of related records, provides clear schema documentation, and allows for complete and clean rollbacks of the database schema, making it easier for new developers to understand the database structure.
            - When NOT to Use Database Relationships in Migrations: Avoid defining database relationships in migrations for legacy databases (to prevent issues with existing data), high-throughput applications (to reduce overhead),  and specialized use cases (where constraints might be unnecessary or counterproductive).
    - Files related:
        - `database\migrations\2024_07_20_131414_create_categories_table.php`
        - `database\migrations\2024_07_20_131424_create_category_details_table.php`
    - Foreign Key Constrains: (See these got created in `MySQL WorkBench` > `Alter Table` > `Foreign Keys`)
        - Short method to define Constrains: Using `foreignId()`, `constrained()`, `cascadeOnDelete()` etc.
        - Long method to define Constrains: Using `foreign()`, `references()`, `on()` etc.
        - Droping FK with `down()` >> `dropForeign()` - Use it carefully to avoid compromising data integrity and handle potential orphaned data.
            - `database\migrations\2024_07_21_052413_add_country_id_to_users_table.php` >> `down()`
    - Delete Cascade with `onDelete('cascade')` & `cascadeOnDelete()`
    - Update Cascade with `onUpdate('cascade')`
    - Indexing (See these got created in `MySQL WorkBench` > `Alter Table` > `Indexes`)
        - Benefits of Indexing: 
            - Improved performance for queries that involve filtering or sorting on indexed columns.
            - Faster data retrieval by allowing the database to efficiently locate relevant records.
        - `index()` 
            - `index([])` - Creates indexes for given column names
            - `index(,)` - Creates index for the given colmn with given custom index name
        - `unique()` - Enforces unique values across indexed column
        - `fullTextIndex()` & `fullText()` - For full-text search capabilities on text columns
            - `fullText()` - to mark a column as searchable within Laravel's Eloquent queries.
            - `fullTextIndex()` - to create a dedicated full-text index for optimal search performance on large datasets.
    - Soft Deletes with `deleted_at` in ``
        - `up()` >> `softDeletes()`
        - `down()` >> `dropSoftDeletes()`
    - `id()`, `increment()` & `bigIncrements()`
        - `id()` - Laravel 8+: creates an auto-incrementing primary key column named `id`. It chooses the right data type (often `BIGINT`) based on your database.
        - `increment()` - Old method before Laravel 8 to do above with `INT`
        - `bigIncrements()` - Old method before Laravel 8 to do above with `BIGINT`
    - `unsignedBigInteger()`: Foreign Keys: When creating a column to reference another table's primary key (often `id`), you'll typically use `unsignedBigInteger()`. This ensures sufficient storage and avoids negative values for foreign key references. E.g.: `database\migrations\2024_05_30_102005_create_posts_table.php` >> `$table->unsignedBigInteger('category_id')->nullable();`
    - morphs() - Used in Polypolymorphic relationship. See above "Eloquent Techniques" relationships.

- **Customized Exceptions Handling with Json Responses, Logging and Sentry integration**
    - `php artisan make:exception CustomExceptionHandler`
        - File will be created: `app\Exceptions\CustomExceptionHandler.php`
    - Binding the Custom Exception Handler to the service container in `bootstrap\app.php`
    - Testing the Custom Exception Handler: 
        - Make some change like a change in class name in `app\Http\Controllers\Api\V2\AuthController.php` to make the process go into `catch` and throw an exception
        - Access the Login route on Postman: http://127.0.0.1:8000/api/v2/login 
        - Response will be: `"message": "A custom exception occurred:....`
        - If you remove the service container registration of `CustomExceptionHandler` from `bootstrap\app.php` >> `singleton()` the response will be the Laravel default exception `"message": "Class \"App\\Http\\Controllers\\Api\\V2\\DDDValidator\" not found.....",` etc.
    - Laravel logs will be updated with Logs added in `CustomExceptionHandler` >> `report()` & `render()` methods
    - Sentry integration:
        - Install Sentry Laravel SDK: `composer require sentry/sentry-laravel`
        - Publish the Sentry Configuration File: `php artisan vendor:publish --provider="Sentry\Laravel\ServiceProvider"`
        - Configure Sentry in `config/sentry.php`
        - Add Sentry DSN to Environment File: `.env` >> `SENTRY_LARAVEL_DSN=https://your-dsn@sentry.io/project-id`
        - Add Sentry logging to `app\Exceptions\CustomExceptionHandler.php` >> `report()` 
- **Centralized Application Constants**
    - Benefits:
        - Centralized Configuration: Centralizes configuration values, making it easier to manage and update them in one place.

        - Readability: Enhances code readability by using meaningful names instead of magic numbers or strings.

        - Maintainability: Simplifies maintenance by allowing updates to values in a single class without changing multiple code occurrences.

        - Avoiding Magic Numbers/Strings: Prevents bugs and confusion by giving meaningful names to values, avoiding direct use of numbers or strings.

        - Consistency: Ensures consistent use of values throughout the application, reducing the risk of typos and inconsistencies.
    - Define constants in `src\app\Constants\AppConstants.php`
    - How to use:
        - Importing the Constants Class: `use App\Constants\AppConstants;` where you want to use it
        - `$status = AppConstants::POST_STATUS[$post->status];`
        - `$categoryType = AppConstants::CATEGORY_TYPE[$post->category_type];`
- **Security Best Practices used** 
    - Protect Against Common Vulnerabilities
        - Cross-Site Scripting (XSS):
            - Sanitize Inputs: 
                - Common usage Laravel’s built-in validations
                - Also can use `filter_var()` method to sanitize user inputs additionaly, but not a common practice: `filter_var($request->input('name'), FILTER_SANITIZE_STRING);`
            - Escape Outputs:
                - For APIs, the escaping of outputs is usually handled by ensuring the data returned is properly encoded as JSON, which Laravel does by default.
                - Also can use `e()` method to escape outputs additionaly, but not a common practice: `$comment->name = e($comment->name);`
                - For Blade templates, outputting variables is done using `{{ }}` which automatically escapes HTML: `{{ $comment->name }}`
        - Cross-Site Request Forgery (CSRF):
            - Laravel API projects: CSRF protection is typically disabled for `api` routes, 
            using Laravel Sanctum stateless authentication tokens for secure token-based authentication and authorization.
            - Laravel Web projects:
                - CSRF Tokens: Laravel automatically generates a CSRF token for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.`<form method="POST" action="/example">@csrf<!-- Other inputs --></form>`

                - CSRF Middleware: Ensure that the `VerifyCsrfToken` middleware is enabled in  `Kernel.php` file:
                `protected $middlewareGroups = ['web' => [ \App\Http\Middleware\VerifyCsrfToken::class,],];`
        - SQL Injection:  Use Laravel’s query builder(Parameterized Queries) or Eloquent ORM to prevent SQL injection.
            - Query Builder: `$users = DB::table('users')->where('email', $email)->get();`
            - Eloquent ORM: `$user = User::where('email', $email)->first();`
    - Use Laravel's Built-in Security Features
        - Authentication: `php artisan make:auth` OR Use Sanctum for API authentication
        - Password Hashing using Laravel’s Hash facade.
            - `app\Http\Controllers\Api\V2\AuthController.php` >> `$user->password = Hash::make($request->password);`
        - Throttle Requests: Use Laravel’s rate limiting to protect application from Brute-Force Attacks.
            - `routes\api_v1.php` >> `Route::middleware('throttle:api').....`
    - Additional Security Measures
        - Force HTTPS by setting forceScheme in the AppServiceProvider:
            `use Illuminate\Support\Facades\URL;`
            `public function boot()`
            `{`
                `if (env('APP_ENV') !== 'local') {`
                    `URL::forceScheme('https');`
                `}`
            `}`
        - Environment Configuration
            Secure .env file by not exposed to the public and sensitive information like database credentials and API keys are kept secure. See AWS Parameter Store implementation below.
        - Encrypt Sensitive Data: Use Laravel’s encryption to store sensitive data securely.
            `use Illuminate\Support\Facades\Crypt;`
            `$encrypted = Crypt::encryptString('sensitive data');`
            `$decrypted = Crypt::decryptString($encrypted);`
        - Content Security Policy (CSP): Implement CSP: Use a content security policy to mitigate XSS attacks by defining which sources are allowed to load content on your site.

- **Middlewares**

- "Other sections - Coming in Next Week.................."

# How to run the application 
- Install required Composer packages using: `composer i`
- Set proper database configuration in `.env`
- Run migration: `php artisan migrate`
- Run seeder: `php artisan db:seed`
- Run application locally: `php artisan serve`
- Postman collection useful for testing the API end points is available in `postman-collection` directory
- Access Swagger API Documentation URL: `http://your-app-url/api/documentation`


######################################################################################
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
