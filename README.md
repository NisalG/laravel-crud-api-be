# Laravel 11 Adavanced API BE CRUD

# Laravel 11 Changes (More info in Laravel 11 Release Notes: https://laravel.com/docs/11.x/releases)
- Bootstrap file `bootstrap/app.php` as central configuration point	
- Reduced default service providers from five to one AppServiceProvider (`app\Providers\AppServiceProvider.php` file)	
- Event discovery and route model bindings enabled by default: 
    - Laravel automatically finds and registers events and their listeners.
    - Manual registration is still possible in the AppServiceProvider.
    - Route model bindings and authorization gates can also be registered in the AppServiceProvider.
- Optional API and broadcast routing :
    - The api.php and channels.php route files are no longer present by default, as many applications do not require these files.
    - Can be created with Artisan commands
- Middleware integrated into the framework, customizable in bootstrap file `bootstrap/app.php`	
- Removal of HTTP kernel class (`src\app\Http\Kernel.php` file)	
- Scheduled tasks defined directly in console routes file `routes/console.php` (`src\app\Console\Kernel.php` file removed)	
- Exception handling configured in bootstrap file `bootstrap/app.php`	
- Simplified base controller class (`app\Http\Controllers\Controller.php` file)
- Default database storage: SQLite	
- Database drivers for session, cache, and queue by default

---

# Advance Features Included (Please check below for detailed guide)
- **Swagger**

- **Docker**

- **Unit & Feature Testing (TDD)**
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
    - Rate Limiting & Throttling
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
        - Rate Limiting & Throttling
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

- **AWS services**
    - AWS SDK for PHP
    - File Storage - AWS S3
    - Parameter Store
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3 will be used) - see *CI/CD Implementation* section.
    - Elasticbeanstalk Extensions (.ebexensions) - see *CI/CD Implementation* section.
    - SES (To do)
    - SQS (To do)

- **CI/CD Implementation**
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3 will be used)
    - Elasticbeanstalk Extensions (.ebexensions)

- **Scheduling**
    - Scheduling Queued Jobs
    - Scheduling Artisan Commands

- **Mails** - ToDo
    - Uses Mailable, Queueable, SerializesModels, Envelope, Content etc.
    - AWS SES: See AWS SES section

- **Caching with Redis**

- **Notifications** - ToDo
    - Events
    - Listeners
    - Queueable
    - ServiceProvider
    - DB tables

- **Event Broadcasting (WebSockets)** - ToDo

---

# Advance Features Explained: Guides, Steps, Artisan Commands and Source Files for above Advance Features
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
        - Modify the exception handling in the TestCase class to throw exceptions instead of rendering them. See `tests\TestCase.php`
    - Post Controller testing:
        - `php artisan make:factory PostFactory --model=Post`
        - Change `database\factories\PostFactory.php`
        - Make sure the Post model uses the HasFactory trait.
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
    - Service Class: `app/Services/AWSService.php`
    - Service Provider: 
        - Artisan command: `php artisan make:provider AWSServiceProvider`
        - File: `app/Providers/AWSServiceProvider.php`
    - Register the Service Provider in `config/app.php`
    - Use in a Controller: `app/Http/Controllers/AWSController.php`
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
    - Rate Limiting & Throttling
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
    - Previous Method with `Laravel 10`
        - `php artisan make:exception CustomExceptionHandler`
            - File will be created: `app\Exceptions\CustomExceptionHandler.php`
        - `report()` method: This method allows to handle logging or reporting of the exceptions. This method is called automatically before the render() method and is useful for logging errors or sending notifications to external services (like Sentry, Bugsnag, etc.).
        - `render()` method: This method is called automatically when the exception is thrown, and it customizes the error JSON response that the user receives.
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

    - New Method with `Laravel 11`
        - Create 4 Custom Exceptions:
            `php artisan make:exception EntityNotFoundException` - file get created: `app\Exceptions\EntityNotFoundException.php`
            `php artisan make:exception ValidationException` - file get created: `app\Exceptions\ValidationException.php`
            `php artisan make:exception AuthorizationException` - file get created: `app\Exceptions\AuthorizationException.php`
            `php artisan make:exception DatabaseException` - file get created: `app\Exceptions\DatabaseException.php`
        - Add relevant codes into above files
        - `report()` method: This method allows to handle logging or reporting of the exceptions. This method is called automatically before the render() method and is useful for logging errors or sending notifications to external services (like Sentry, Bugsnag, etc.).
        - `render()` method: This method is called automatically when the exception is thrown, and it customizes the error JSON response that the user receives.
        - Register & Configure Custom Exception Handling in `bootstrap/app.php` >> `Application::configure` >> `withExceptions`
        - Use above 4 custom exceptions in `app\Http\Controllers\Api\V2\CategoryController.php`
        - Add relevant routes in `routes\api_v2.php`
        - Testing on Postman:
            - `http://127.0.0.1:8000/api/v2/categories`
            - `http://127.0.0.1:8000/api/v2/categories/1`
            - `http://127.0.0.1:8000/api/v2/categories/222`
            - `render()` method will give the proper customized exception message as response
            - `report()` method will log the proper customized exception message to laravel.log
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
        - Force HTTPS
            - Web Server Configuration: Make sure the web server (e.g., Apache, Nginx) is configured to use HTTPS and redirect HTTP requests to HTTPS.
            - Ensure the correct environment set in .env: `APP_ENV=production`
            - Set forceScheme in the `app/Providers/AppServiceProvider.php` >> `boot()`
        - Environment Configuration
            Secure .env file by not exposed to the public and sensitive information like database credentials and API keys are kept secure. See *AWS Parameter Store* implementation section.
        - Encrypt Sensitive Data: Use Laravel’s encryption to store sensitive data securely.
            - What should be encrypted:
                - Personal Identifiable Information (PII): Social Security Numbers (SSN), Driver's License Numbers, Passport Numbers, National Identification Numbers

                - Financial Information: Credit Card Numbers, Bank Account Numbers, Credit Reports, Tax Information

                - Authentication Credentials: Passwords (typically hashed rather than encrypted, but still considered sensitive), Security Questions and Answers, Authentication Tokens

                - Health Information: Medical Records, Health Insurance Information, Prescription Information, Patient History

                - Contact Information: Email Addresses, Phone Numbers, Home Addresses

                - Business Information: Trade Secrets, Proprietary Formulas, Strategic Plans, Internal Communications

                - Communication Data: Chat Logs, Emails, Text Messages

                - Biometric Data: Fingerprints, Facial Recognition Data, Iris Scans, Voice Recognition Data

                - Legal Information: Legal Contracts, Court Orders, Legal Agreements

                - Intellectual Property: Source Code, Design Documents, Patents

                - Sensitive Configuration Data: API Keys, Access Tokens, Encryption Keys, Database Credentials
            - Example 1: Using encrypted keys using AWS ParameterStore >> `SecureString` type parameters. But this doesn't use Laravel `Illuminate\Support\Facades\Crypt`
            - Example 2: Encrypting & Decrypting Sensitive Data (e.g.: user SSN)
                `app\Http\Controllers\Api\V2\UserController.php` >> `store()` & `show()`
        - Content Security Policy (CSP): Implement CSP: Use a content security policy to mitigate XSS attacks by defining which sources are allowed to load content on the webapplication.

- **Middlewares**
    - Previously, new Laravel applications included nine middleware for tasks like authenticating requests, trimming input strings, and validating CSRF tokens. In Laravel 11, these middleware are now part of the framework itself, reducing the application's bulk. Customization of these middleware can be done through new methods in the framework, which can be invoked from your application's `bootstrap/app.php` file. There's no registering in `kernel.php` in Laravel 11 since that file is not available.
    - Deafult (already available and non-custom middlewares). Can be invoked and configured in `bootstrap/app.php` >> `withMiddleware()` 
        - Sanctum Auth Middleware:
            - `routes\api_v2.php` >> `middleware('auth:sanctum')` && `'middleware' => 'auth:sanctum'`
            - `\config\sanctum.php`
        - Route Middleware for `api` & `web`: 
            - `app\Providers\RouteServiceProvider.php` >> `Route::middleware('api')` & `Route::middleware('web')`
        - Throttle Middleware with Custom Limits (See API Throttling section):
            - `routes\api_v1.php` >> `Route::middleware('throttle:api')`
        - CORS Middleware
        - Verify CSRF Token Middleware
            - Invoked and configured in `bootstrap/app.php` >> `withMiddleware()`  >> `$middleware->validateCsrfTokens()`
    - Custom middlewares (Created using Artisan commands and will be created inside `app/Http/Middleware` folder)
        - Steps:
            - `php artisan make:middleware MyMiddleware`
            - File will be created: `app/Http/Middleware/MyMiddleware.php`
            - Register the middleware (no Kernel.php in Laravel 11) in: `bootstrap\app.php` >> `Application::configure()` >> `withMiddleware()`
            - Use middlware in route files: `routes\api_v2.php` >> `middleware()`
            - Use routes without a certain middlware in a route group: `withoutMiddleware()`
        - Role-Based Access Control Middleware:
            - `php artisan make:middleware RoleManagement` creates `app/Http/Middleware/RoleManagement.php`
            - Register the middleware (no Kernel.php in Laravel 11) in: `bootstrap\app.php` >> `Application::configure()` >> `withMiddleware()` >> `prepend()`
            - Using/Testing route in: `routes\api_v2.php`
            - Example Postman Request: [GET] http://127.0.0.1:8000/api/v2/admin
                - Headers: Key: Authorization | Value: Bearer <Auth Token>
                - Response:
                    - If the user is an admin: "Admin Area" [200]
                    - If the user is not an admin: [403] "message": "Unauthorized"
        - Logging User Activity Middleware:
            - `php artisan make:middleware LogUserActivity` creates `app\Http\Middleware\LogUserActivity.php`
            - Register the middleware (no Kernel.php in Laravel 11) in: `bootstrap\app.php` >> `Application::configure()` >> `withMiddleware()` >> `prepend()`
            - Using/Testing route in: `routes\api_v2.php`
            - Example Postman Request: [GET] http://127.0.0.1:8000/api/v2/profile
                - Headers: Key: Authorization | Value: Bearer <Auth Token>
                - Response: "User Profile" [200]
                - Check Laravel log files for the log entry
        - Localization Middleware - with usage a middleware in route groups using `group()`:
            - `php artisan make:middleware Localization` creates `app\Http\Middleware\Localization.php`
            - Using/Testing route in: `routes\api_v2.php`
            - Example Postman Request: 
                - Setting:
                    - [POST] http://127.0.0.1:8000/api/v2/set-locale?locale=es
                    - Response: "message" : "Set Locale" [200]
                    - Set application locale to `es`
                - Getting:
                    - [GET] http://127.0.0.1:8000/api/v2/current-locale
                    - Response: "locale": "es" [200]
                    - Ensure the application locale is set to `es`

- **AWS services**
    - Install the AWS SDK for PHP: `composer require aws/aws-sdk-php`
    - File Storage - AWS S3
        - Servic class: `app\Services\AWSService.php`
        - Service Provider: `app\Providers\AWSServiceProvider.php`
            - Register in register()
            - Ensure AWSService is available from the start of your application and environment variables are set up immediately by resloving in boot()
        - Register the Service Provider in: `config\app.php`
        - Usage in: `app\Http\Controllers\Api\V2\AWSController.php`
    - Parameter Store: 
        - AWS Parameter Store Encryption
        AWS Parameter Store supports two types of parameters: Standard and SecureString. SecureString parameters are encrypted at rest using a customer-managed AWS Key Management Service (KMS) key. When you store a parameter as a SecureString, AWS handles the encryption and decryption processes for you. AWS enforces strict IAM policies, and auditing access.
        - Retrieve the Parameter:
            - Single: `app\Services\AWSService.php` >> `getSSMParameter()`
            - Get all and assign to environment variables: `app\Services\AWSService.php` >> `setEnvironmentVariables()`
            - Service Provider: `app\Providers\AWSServiceProvider.php`
                - Register in register()
                - Ensure AWSService is available from the start of your application and environment variables are set up immediately by resloving in boot()
            - Access the anv vars: `env('THE_ENV_VAR');`
        - Set the Environment Variable during Deployment in YML files:
        ``` option_settings:
                aws:elasticbeanstalk:application:
                    environment:
                        THE_ENV_VAR: "the-env-var"
        ```
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3, RDS, EC2, CloudFront, ECS, Route53 will be used) - see *CI/CD Implementation* section.
    - Elasticbeanstalk Extensions (.ebexensions) - see *CI/CD Implementation* section.
    - SES (Simple Email Service)
        - Send mail method in service class: `app\Services\AWSService.php` >> `sendEmail()`
        - Make sure the email address used as the Source in sendEmail is verified in your SES account. You can set this email in your .env file: `SES_SOURCE_EMAIL=your-ses-verified-email@example.com`
        - Using the AWSService Class to Send Emails in controller action: `app\Http\Controllers\Api\V2\AWSController.php` >> `sendSESTestEmail()`
        - Define Route: `routes\api_v2.php`
        - Configure the mail driver in: ` config/mail.php` >> `'default' => env('MAIL_MAILER', 'ses'),`
        - Configure AWS SES configuration: `config/services.php` (to get from `.env` already available)
        - Update `.env` File
    - SQS (Simple Queue Service)
        - See *Scheduling* section as well. 
        - Send and Recive messages methods in service class: `app\Services\AWSService.php` >> `sendMessageToSQSQueue()` & `receiveMessagesFromSQSQueue()`
        - Define Routes: `routes\api_v2.php`
        - Configure AWS SQS as a queue driver in `config/queue.php` >> `connections` array
        - Update `.env` File
        - If using the AWSService Class to Send and Recive messages in controller actions: `app\Http\Controllers\Api\V2\AWSController.php` >> `sendSQSMessage()` & `receiveSQSMessages()`
        - If queuing a Laravel job:-------to do
            - Using SQS in a Job - Create a job that will be pushed to the SQS queue: `php artisan make:job SendSQSEmailJob`
            - 
            - Run/Start the queue worker to process jobs from the SQS queue: `php artisan queue:work`
    - Redis Caching setting up on EC2 instance with Amazon Linux OS - See *Caching with Redis* section 
- **CI/CD Implementation**
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3, RDS, EC2, CloudFront, ECS, Route53 will be used)
        - See `buildspec.yml`
    - Elasticbeanstalk Extensions (.ebexensions)
        - .ebextensions/enable-laravel-log-file-writable.config
            - Why: This extension ensures that the Laravel application can write to its log files within the storage/logs directory.
            - What it does:
                container_commands: Defines commands to run within the Elastic Beanstalk container.
                    01_run_bootstrap_command:
                        command: Sets ownership of the storage/logs directory and its contents to the webapp user and group. This allows the Laravel application running under the webapp user to write to the logs.
                        cwd: Specifies the working directory where the command is executed. Here, it's /var/app/staging, which is the default deployment directory for Elastic Beanstalk staging environments. This path doesn't need to be changed for production environments.
                    02_run_bootstrap_command:
                        command: Sets permissions on the storage directory to 775. This grants read, write, and execute access for the owner (webapp), the group (webapp), and others (for execution only).

        - .ebextensions/env-variables.config
            - Why: This extension retrieves environment variables from Elastic Beanstalk and sets them as shell environment variables for your application.
            - What it does:
                container_commands: Defines commands to run within the container.
                    setvars:
                        command: Retrieves environment variables using Elastic Beanstalk's get-config command and pipes the output through jq to format it as shell environment variable assignments. The formatted output is then written to /etc/profile.d/local.sh. This script gets sourced when the shell starts, making the environment variables available to your application.
                packages: Defines software packages to install on the Elastic Beanstalk instance.
                    yum: Specifies packages to install using the Yum package manager.
                        jq: Installs the jq command-line JSON processor, which is required for parsing the environment variable output from get-config.

        - .ebextensions/install-composer.config
            - Why: This extension installs Composer, a dependency management tool for PHP projects, if it's not already installed.
            - What it does:
                container_commands: Defines commands to run within the container.
                    01installcomposer:
                        command: Downloads the Composer installer script, runs it with sudo to install Composer globally, and specifies the installation directory (/usr/local/bin) and filename (composer).
                        ignoreErrors: Set to false (default), meaning the deployment will fail if this command fails.

        - .ebextensions/install-mysql-client.config
            - Why: This extension installs the MySQL client library if you need to interact with a MySQL database from your Laravel application.
            - What it does:
                container_commands: Defines commands to run within the container.
                    01installmysql80:
                        command: Adds the MySQL Yum repository for version 8.0 using a provided URL.
                    02installmysql80:
                        command: Installs the MySQL community server using Yum.
                    03startmysqld:
                        command: Starts the MySQL daemon using systemctl.
                    04checkstatus:
                        command: Checks the status of the MySQL daemon using systemctl.

        - .ebextensions/laravel-schedule-cron.config
            - Why: This extension sets up a cron job to run the Laravel scheduler periodically.
            - What it does:
                files: Defines file configurations to manage on the Elastic Beanstalk instance.
                    /etc/cron.d/mycron:
                        mode: Sets the file permissions to 000644 (read and write for owner, read for group and others).
                        owner: Sets the file owner to root.
                        group: Sets the file group to root.
                        content: Defines the cron job schedule and command to execute. The cron expression * * * * * runs the command every minute. The command webapp php /var/www/html/artisan schedule:run >> /var/log/schedule_debug.log 2>&1 executes the Laravel scheduler (artisan schedule:run) using the webapp user, redirects standard output and error to the /var/log/schedule_debug.log file for debugging

- **Scheduling**
    - Scheduling Queued Jobs:
        - Jobs and Queues (Jobs are Queued)
        - Scheduling Queued Jobs
    - Scheduling Artisan Commands
        - src\app\Console\Commands\SendTenantProposalReminders.php
        - Scheduling in 
            - .ebextensions\laravel-schedule-cron.config
            - src\app\Console\Kernel.php (not in L 11)

- **Mails**
    - Uses Mailable, Queueable, SerializesModels, Envelope, Content etc.
    - Send mails instantly: 
    - Send Scheduled Emails: see *AWS > SQS* & *Scheduling* sections 
    - AWS SES: See AWS SES section

- **Caching with Redis**
    - Redis Cache Usage
        - **Purpose:** Improve application performance by storing frequently accessed data in-memory.
        - **Commonly Cached Data:** Session data, user profiles, product catalogs, dynamic content, API responses, leaderboards(sport score boards), real-time analytics, pub/sub messages.
        - **Use Cases:** Rate limiting, distributed locks, queues.
        - **Key Factors:** Read-heavy workloads, data staleness tolerance, cache size, cache invalidation.
    - Installing 
        - Local setup: Redis on Windows and Setting Up with Laravel
            - Download Redis for Windows from Microsoft archive.
            - Extract the ZIP file and run redis-server.exe.
            - CMD as Admin: `redis-server --service-install redis.windows-service.conf --loglevel verbose redis-server --service-start`
            - To see what values are cached at the moment install `RedisInsight`
        - Deployed(PRD) setup: Installing Redis on AWS Linux EC2 for Laravel
            - Elastic Beanstalk 
                - Environment >> Configuration:
                ```CACHE_STORE=redis
                    REDIS_HOST=your-redis-endpoint
                    REDIS_PASSWORD=null
                    REDIS_PORT=6379```
                - Ensure the security group for your Elastic Beanstalk instances allows outbound traffic to the Redis server's security group on port `6379`.
            - EC2 instance:
                - Conect to the EC2 instance
                - Update package lists: `sudo yum check-update`
                - Update all packages: `sudo yum update -y`
                - Install Redis: `sudo amazon-linux-extras install redis6 -y`
                - Start the Redis service: `sudo systemctl start redis`
                - Enable Redis to start on boot: `sudo systemctl enable redis`
    - Setting up in Laravel project:
        - Install the predis/predis package: `composer require predis/predis`
        - Update `.env`
            - `CACHE_STORE`
            - `REDIS_CLIENT`
            - `REDIS_HOST`
            - `REDIS_PASSWORD`
            - `REDIS_PORT`
        - Check `config/cache.php` >> `'default' => env('CACHE_STORE', 'redis'),`
        - Check if uses for Sessions: `config/session.php` >> `'driver' => env('SESSION_DRIVER', 'redis'),`
        - Check if uses for Queues: `config/queue.php` >> `'default' => env('QUEUE_CONNECTION', 'redis'),`
        - Verify Redis Connection in `routes\api_v2.php` >> `/test-redis` route
        - There are two ways to use Redis in Laravel 
            - With default `use Illuminate\Support\Facades\Cache;` facade, after above configurations.
                - Store a value in the Redis cache for 60 minutes: `Cache::put('key', 'value', 60);`
                - Retrieve a value from the Redis cache: `$value = Cache::get('key');`
                - Checking if a Key Exists: `if (Cache::has('key')) { // Key exists }`
                - Removing Data from Cache: `Cache::forget('key');`
            - With directly using `use Illuminate\Support\Facades\Redis;` facade
                - Set a Value: `app\Http\Controllers\Api\V2\CategoryController.php` >> `index()`
                - Get a Value: `app\Http\Controllers\Api\V2\CategoryController.php` >> `index()`
                - Clear cache for a value: `app\Http\Controllers\Api\V2\CategoryController.php` >> `store()`
                - Advanced Usage:
                    - Using Redis Pub/Sub:
                        - Subscribe to a channel:
                            ```Redis::subscribe(['channel'], function ($message) {
                                echo $message;
                            });```
                        - Publish to a channel: `Redis::publish('channel', 'Hello, World!');`
                    - Working with Redis Hashes:
                        - Set a hash value: `Redis::hset('hash_key', 'field', 'value');`
                        - Get a hash value: `$value = Redis::hget('hash_key', 'field');`
- **Notifications** - ToDo
    - Events
    - Listeners
    - Queueable
    - ServiceProvider
    - DB tables

- **Event Broadcasting (WebSockets)** - ToDo

---

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
