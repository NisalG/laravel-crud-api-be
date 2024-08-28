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
- Removal of HTTP Kernel class (`src\app\Http\Kernel.php` file)	and Console Kernel class (`src\app\Console\Kernel.php` file)
    - Middleware integrated into the framework, customizable in bootstrap file `bootstrap/app.php` (since `src\app\Http\Kernel.php` file removed)
    - Scheduled tasks defined directly in console routes file `routes/console.php` (since `src\app\Console\Kernel.php` file removed)	
- Exception handling configured in bootstrap file `bootstrap/app.php`	
- Simplified base controller class (`app\Http\Controllers\Controller.php` file)
- Default database storage: SQLite	
- Database drivers for session, cache, and queue by default

---

# Advance Features Included (Please check below for detailed guide)
- [**Swagger**](#swagger)

- [**Docker**](#docker)

- [**Unit & Feature Testing (TDD)**](#unit-feature-testing-tdd)
    - TDD Unit & Feature
    - Mocking External (Third Party) APIs and Services (CoinGecko Cryptocurrency Data API)

- [**Service Container and Service Providers**](#service-container-and-service-providers)

- [**Eloquent Techniques**](#eloquent-techniques)
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

- [**APIs**](#apis)
    - Authentication with Sanctum
    - Versioning
    - Rate Limiting & Throttling
    - API Request validation
    - API Resource for transforming Responses

- [**Advance Migrations**](#advance-migrations)

- [**Customized Exceptions Handling with Json Responses, Logging and Sentry integration**](#customized-exceptions-handling)

- [**Centralized Application Constants**](#centralized-application-constants)

- [**Security Best Practices used**](#security-best-practices-used)
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

- [**Middlewares**](#middlewares)
    - Role-Based Access Control Middleware
    - Logging User Activity Middleware
    - Throttle Middleware with Custom Limits
    - Localization Middleware
    - CORS Middleware

- [**AWS services**](#aws-services)
    - AWS SDK for PHP
    - File Storage - AWS S3
    - Parameter Store
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3 will be used) - see *CI/CD Implementation* section.
    - Elasticbeanstalk Extensions (.ebexensions) - see *CI/CD Implementation* section.
    - SES
    - SQS

- [**Schedules vs Queues**](#schedules-vs-queues)

- [**Schedules**](#schedules)
    - Scheduling Artisan Commands
    - Scheduling Queued Jobs

- [**Queues**](#queues)

- [**Mails**](#mails)
    - Uses Mailable, Queueable, SerializesModels, Envelope, Content etc.
    - Send emails with Sendgrid (with an attachment)
    - Send Scheduled Emails: see *AWS > SQS* & *Scheduling* sections
    - Also see these sections: `AWS SES`, `AWS SQS`, `Scheduling` 

- [**Caching with Redis**](#caching-with-redis)

- [**CI/CD Implementation**](#ci-cd)
    - CI/CD with buildspec.yml (AWS CodeBuild, Pipeline, Elasticbeanstalk, IAM, S3 will be used)
    - Elasticbeanstalk Extensions (.ebexensions)

- [**Event Driven Architecture(EDA), Events & Listeners:**](#eda)


- [**Event Broadcasting (WebSockets)**](#webSockets) - ToDo


- [**Notifications**](#notifications) - ToDo


- [**Clean Code Architecture**](#clean-code-architecture)


- [**Domain-Driven Design (DDD)**](#ddd)


- [**SOLID Principles**](#solid-principles)


- [**Design Patterns**](#design-patterns)


---


# Advance Features Explained: Guides, Steps, Artisan Commands and Source Files for above Advance Features

## Swagger
- Swagger UI library to generate interactive documentation - commands:
	- `npm install swagger-ui-dist`
	- `composer require darkaonline/l5-swagger tymon/jwt-auth`
	- `php artisan vendor:publish --provider=”Tymon\JWTAuth\Providers\LaravelServiceProvider”`
- Generate the Swagger documentation: `php artisan l5-swagger:generate`
- Access Swagger API Documentation URL: `http://your-app-url/api/documentation`

---


## Docker
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

---


<h2 id="unit-feature-testing-tdd">Unit & Feature Testing (TDD)</h2>

- Mockery to create mock objects for testing dependencies etc.
- Faker to fake data like text, datetime, country etc.
- Common commands:
	- Make: `php artisan make:test FaqsTableSeederTest`
	- Run all: `php artisan test`
	- To run a specific test class, provide the path to the test file: `php artisan test --filter ExampleTest`
	- Run a Specific Test Method: `php artisan test --filter 'ExampleTest::testBasicExample'`
- Testing database settings should be given in phpunit.xml:
	Default is `sqlite`. If you want to configure a different MySQL DB config it as:
	`<env name="DB_CONNECTION" value="mysql"/>`
	`<env name="DB_DATABASE" value="test_database"/>`
- Enable Detailed Error Messages in Tests:
	- Check Log files
	- Update phpunit.xml: `<env name="APP_DEBUG" value="true"/>`
	- Create File: `tests/CreatesApplication.php`
	- Modify the exception handling in the TestCase class to throw exceptions instead of rendering them. See `tests\TestCase.php`
- Post Controller testing:
	- `php artisan make:factory PostFactory --model=Post`
	- Category entity changes:
		- Also need to create CategoryFactory since a category_id is required to create a Post in PostFactory: `php artisan make:factory CategoryFactory --model=Category`
		- Change Category creation migration file's name by changing the date manually to a date before Post creation migration file's name
		- Run `php artisan migrate:fresh`
	- Country entity changes: 
		- Also need to create CountryFactory since a country_id is required to create a User in UserFactory: `php artisan make:factory CountryFactory --model=Country`
	- Change `database\factories\PostFactory.php`
	- Make sure the Post model uses the HasFactory trait.
	- Test: `php artisan test --filter 'PostControllerTest::it_can_list_all_posts'`
	- Testing `it_can_search_answers_by_faq()`: 
		- Since default is `sqlite`, FullText index will not work and `app\Http\Controllers\Api\V2\PostController.php` > `getAnswers()` query testing will not work as expected
		- Check if fultext is anabled in `content` column. If empty that means not enabled:
		`info('index: ' , DB::select('SHOW INDEX FROM posts WHERE Key_name = "content"'));`
		- If you do a basic query like this, it will work.
			```$results = DB::table('posts')
					->select('content')
					->where('content', 'LIKE', '%' . $faq . '%')
					->get();
			```
		- Therefore this test will not pass with our test DB settings right now. 
- Mocking External (Third Party) APIs and Services (CoinGecko Cryptocurrency Data API)
	- Create a Service for CoinGecko API: `app/Services/CoinGeckoService.php`
	- Create a Controller: `app/Http/Controllers/CoinGeckoController.php`
	- Define Routes: `routes/api.php`
	- Get Cryptocurrency Data by URL: http://127.0.0.1:8000/api/cryptocurrency/bitcoin
	- Get Market Data by URL: http://127.0.0.1:8000/api/market-data
	- Create Unit Test Cases for the Service: `tests/Unit/CoinGeckoServiceTest.php`
	- Create Feature Test Cases for the Controller: `tests/Feature/CoinGeckoControllerTest.php`
	- Run the Tests: `php artisan test` 
- Mockery to create mock objects for testing dependencies etc. in `tests\Unit\PostsTableSeederTest.php`
	- `php artisan test --filter 'PostsTableSeederTest::testMissingCsvFile'`

---


## Service Container and Service Providers
- Service Class: `app/Services/AWSService.php`
- Service Provider: 
	- Artisan command: `php artisan make:provider AWSServiceProvider`
	- File: `app/Providers/AWSServiceProvider.php`
- Register the Service Provider in `config/app.php`
- Use in a Controller: `app/Http/Controllers/AWSController.php`
	
---


## Eloquent Techniques
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
			- Add code for model event functions `creating()`, `updating()` etc.
		- Register the Observer: `App\Providers\AppServiceProvider.php`

---


## APIs
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


---


## Advance Migrations
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
- Soft Deletes with `deleted_at` in `\database\migrations\2024_07_21_000815_enable_softdelete_in_category_table.php`
	- `up()` >> `softDeletes()`
	- `down()` >> `dropSoftDeletes()`
- `id()`, `increment()` & `bigIncrements()`
	- `id()` - Laravel 8+: creates an auto-incrementing primary key column named `id`. It chooses the right data type (often `BIGINT`) based on your database.
	- `increment()` - Old method before Laravel 8 to do above with `INT`
	- `bigIncrements()` - Old method before Laravel 8 to do above with `BIGINT`
- `unsignedBigInteger()`: Foreign Keys: When creating a column to reference another table's primary key (often `id`), you'll typically use `unsignedBigInteger()`. This ensures sufficient storage and avoids negative values for foreign key references. E.g.: `database\migrations\2024_05_30_102005_create_posts_table.php` >> `$table->unsignedBigInteger('category_id')->nullable();`
- morphs() - Used in Polypolymorphic relationship. See above "Eloquent Techniques" relationships.

---


<h2 id="customized-exceptions-handling">Customized Exceptions Handling with Json Responses, Logging and Sentry integration</h2>

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
		
---


## Centralized Application Constants
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
	
---


## Security Best Practices used
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
		using Laravel Sanctum (Passport before L10) stateless authentication tokens for secure token-based authentication and authorization. But still in L 11  `bootstrap\app.php` >> `withMiddleware()` >> `validateCsrfTokens()` is available by default.
		- Laravel Web projects:
			- CSRF Tokens: Laravel automatically generates a CSRF token for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.`<form method="POST" action="/example">@csrf<!-- Other inputs --></form>`

			- CSRF Middleware: 
				- L 11: Ensure that the enabled: `bootstrap\app.php` >> `withMiddleware()` >> `validateCsrfTokens()`
				- Before L 11: Ensure that the `VerifyCsrfToken` middleware is enabled in  `src\app\Http\Kernel.php` file:
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


---


## Middlewares
- Previously, new Laravel applications included nine middleware for tasks like authenticating requests, trimming input strings, and validating CSRF tokens. In Laravel 11, these middleware are now part of the framework itself, reducing the application's bulk. Customization of these middleware can be done through new methods in the framework, which can be invoked from your application's `bootstrap/app.php` >> `withMiddleware()` >> `validateCsrfTokens()`. There's no registering in `src\app\Http\Kernel.php` in Laravel 11 since that file is not available.
- Deafult (already available and non-custom middlewares). Can be invoked and configured in `bootstrap/app.php` >> `withMiddleware()` >> `validateCsrfTokens()`
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
		- Register the middleware (no `src\app\Http\Kernel.php` in Laravel 11) in: `bootstrap\app.php` >> `Application::configure()` >> `withMiddleware()`
		- Use middlware in route files: `routes\api_v2.php` >> `middleware()`
		- Use routes without a certain middlware in a route group: `withoutMiddleware()`
	- Role-Based Access Control Middleware:
		- `php artisan make:middleware RoleManagement` creates `app/Http/Middleware/RoleManagement.php`
		- Register the middleware (no `src\app\Http\Kernel.php` in Laravel 11) in: `bootstrap\app.php` >> `Application::configure()` >> `withMiddleware()` >> `prepend()`
		- Using/Testing route in: `routes\api_v2.php`
		- Example Postman Request: [GET] http://127.0.0.1:8000/api/v2/admin
			- Headers: Key: Authorization | Value: Bearer <Auth Token>
			- Response:
				- If the user is an admin: "Admin Area" [200]
				- If the user is not an admin: [403] "message": "Unauthorized"
	- Logging User Activity Middleware:
		- `php artisan make:middleware LogUserActivity` creates `app\Http\Middleware\LogUserActivity.php`
		- Register the middleware (no `src\app\Http\Kernel.php` in Laravel 11) in: `bootstrap\app.php` >> `Application::configure()` >> `withMiddleware()` >> `prepend()`
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


---


## AWS services
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
	```option_settings:
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
	- If queuing a Laravel job:
		- Using SQS in a Job - Create a job that will be pushed to the SQS queue: `php artisan make:job SendSQSEmailJob`
		- Locally: Test locally with `config/queue.php` >> `database` section.
			- Create queue table:
				- `php artisan make:queue-table`
				- `php artisan migrate`
			- Run/Start the queue worker to process jobs from the SQS queue: `php artisan queue:work`
		- STG/PRD: 
			- Add to AWS Beanstalk extenstions(`.ebextensions`)
- Redis Caching setting up on EC2 instance with Amazon Linux OS - See *Caching with Redis* section 


---


## Schedules vs Queues
    
In Laravel, both schedules and queues are used for managing tasks, but they serve different purposes and are best suited for different types of operations. Here's a comparison and guidance on when to use each:

- Laravel Schedules

	**Purpose:**
	Laravel's scheduling system allows you to define scheduled tasks that are executed at specified intervals. This is particularly useful for tasks that need to run periodically, like daily, weekly, or monthly.

	**How It Works:**
		You define scheduled tasks in the App\Console\Kernel class within the schedule method.
		You then set up a single cron entry on your server that calls Laravel's scheduler every minute. Laravel will check your schedule and run the appropriate tasks.

	**Use Cases:**
		Routine Maintenance: Cleaning up old logs or temporary files.
		Regular Data Processing: Aggregating data, generating reports, or synchronizing with external APIs on a regular basis.
		Periodic Notifications: Sending scheduled emails or reminders.
		Database Cleanup: Regularly purging old records or running database optimizations.

	**Advantages:**
		Simplifies the management of periodic tasks.
		No need to handle job queuing or worker processes.

	**Disadvantages:**
		The frequency is limited to the cron schedule's precision (typically every minute).
		Not ideal for tasks that need immediate execution or are highly variable in timing.

- Laravel Queues

	**Purpose:**
	Laravel's queue system is designed to handle background processing of jobs. It's ideal for tasks that are time-consuming or need to be processed asynchronously.

	**How It Works:**
		Jobs are pushed onto a queue and processed by workers.
		You define jobs that handle specific tasks and then dispatch these jobs to the queue.
		Workers pick up these jobs and execute them. You can configure different queues and workers based on your needs.

	**Use Cases:**
		Background Processing: Sending emails, processing file uploads, or generating reports that should not block the main application flow.
		Delayed Tasks: Performing actions with a delay (e.g., sending reminders or notifications at a later time).
		High-Volume Tasks: Handling tasks that involve a lot of computation or external API calls.

	**Advantages:**
		Allows for more complex job handling and retry mechanisms.
		Can scale easily by adding more workers or changing the queue driver.
		Supports delayed execution and priority levels.

	**Disadvantages:**
		Requires configuration of queue drivers (e.g., Redis, database) and workers.
		Adds some complexity in managing and monitoring job execution.

---


## Schedules

Executes scheduled tasks defined at specified intervals. Runs pending tasks and then exits. Ideal for periodic tasks like backups or cleanups. Usually triggered by a cron job.
	
- Task schedule is defined in `routes/console.php`. Schedule a closure sample(`command`, `job` below ) to be called every day at midnight: 
	```Schedule::call(function () {
		DB::table('recent_users')->delete();
	})->daily();
	```

- If you prefer to reserve `routes/console.php` for `command` definitions only, you may use the `withSchedule` method in `bootstrap/app.php` to define your scheduled tasks. Scheduling a closure (`command`, `job` below ) sample:
	```use Illuminate\Console\Scheduling\Schedule;
	
	->withSchedule(function (Schedule $schedule) {
		DB::table('recent_users')->delete();
	});
	```

- Overview of scheduled tasks and the next time they are scheduled to run: `php artisan schedule:list`

- Scheduling Artisan Commands using the `command` method:
		`Schedule::command('emails:send Taylor --force')->daily();`
		`Schedule::command(SendEmailsCommand::class, ['Taylor', '--force'])->daily();`

- Scheduling Queued Jobs using `job` method:
	``` use App\Jobs\Heartbeat;
		use Illuminate\Support\Facades\Schedule;
		Schedule::job(new Heartbeat)->everyFiveMinutes();
	```
- Preventing Task Overlaps
	By default, scheduled tasks will be run even if the previous instance of the task is still running. To prevent this, you may use the `withoutOverlapping` method:
		`use Illuminate\Support\Facades\Schedule;`
		`Schedule::command('emails:send')->withoutOverlapping();`

- Background Tasks
	By default, multiple tasks scheduled at the same time will execute sequentially based on the order they are defined in `schedule` method. If you have long-running tasks, this may cause subsequent tasks to start much later than anticipated. If you would like to run tasks in the background so that they may all run simultaneously, you may use the runInBackground method: 
	
	```
	use Illuminate\Support\Facades\Schedule;
	
	Schedule::command('analytics:report')
			->daily()
			->runInBackground();
	```

- Running the Scheduler Locally: `php artisan schedule:work`

- Running the Scheduler on Servers (STG/PRD)
	Use schedule:run Artisan command on AWS EC2 instance CRONTab (single entry is enough), AWS ElasticBeanstalk .ebextensions, Laravel Forge, etc.

- Scheduling Artisan Commands:
	- Create mailable: `php artisan make:mail DailyPostCountEmail`
		- `app\Mail\DailyPostCountEmail.php` will be created
	
	- Create mail layout in: `resources\views\emails\dailyPostCountEmail.blade.php`

	- Create command: `php artisan make:command DailyPostCountEmailCommand`
		- `app\Console\Commands\DailyPostCountEmailCommand.php` file will be created.
			- Set `$signature`, `$description`, `handle()`

	- Scheduling: `routes/console.php` >> 
	```
		Schedule::command('app:send-daily-post-count-email example@example.com John')
			// ->dailyAt('10:00')
			->everyMinute();
	```
	- No `src\app\Console\Kernel.php` in L11 (If L10 should add in there)

	- Running:
		- Locally: `php artisan schedule:work`
			- Email will be sent (Or laravel.log will be updated)
				- Test mail locally:
				- `.env` > `MAIL_MAILER=log`
				- The emails will be logged to your `storage/logs/laravel.log` file
			- Get an overview of scheduled tasks: `php artisan schedule:list`
		- STG/PRD: 
			- Cron Job Setup on Linux Server using CRON tab
				- `crontab -e`
				- `* * * * * cd /path-to-your-project & php artisan schedule:run >> /dev/null 2>&1`
			- Cron Job Setup on Linux Server using AWS Elastic Beanstalk extensions: `.ebextensions\laravel-schedule-cron.config`

- Scheduling Queued Jobs (Queue this jobs as in the Queue section to add into `jobs` table, otherwise below doesn't work): 
	- Creating a Job: `php artisan make:job DeleteOldPostsJob`

	- Define the job: Add logic to define job in `app\Jobs\DeleteOldPostsJob.php` >> `handle()`

	- Queue this jobs as in the Queue section, otherwise below doesn't work

	- Job Scheduling with Laravel Scheduler to run every minute
		- Method 1 (not good for clarity): in `routes\console.php` >> `Schedule::job(new DeleteOldPostsJob($data))->everyMinute();`
		- Method 2 (good for clarity, but couldn't find a working sample): in `bootstrap\app.php` >> `withSchedule` >> `Schedule::job(new DeleteOldPostsJob($data))->everyMinute();`
		```use Illuminate\Console\Scheduling\Schedule;
		
		->withSchedule(function (Schedule $schedule) {
			Schedule::job(new DeleteOldPostsJob($emailData))->everyMinute();
		});
		```

	- Running: Same as above Console section


---


## Queues

Jobs and Queues (Jobs are Queued): Queues defer time-consuming tasks to run in background, improving performance and responsiveness. Laravel 11 supports various queue backends: `Redis`, `Amazon SQS`, `Beanstalkd`, and `database`. Jobs represent a unit of work that can be dispatched onto the queue. Queue workers process jobs pushed onto the queue.


Laravel Queue Worker
- Starts processing queued jobs asynchronously, useful for tasks like sending emails or file processing. 
- Runs continuously, listening for new jobs to handle.

- In L11 `jobs` table is already available. If not create the jobs table:
	- `php artisan queue:table`
	- `php artisan migrate`

- Creating a Job: `php artisan make:job SendPostUpdatedEmailJob`

- Define the job: Add logic to define job in `app\Jobs\SendPostUpdatedEmailJob.php` >> `handle()`
	
- Dispatching (pushing the job to queue - this will add to `jobs` table) a Job in a Controller Action: 
`app\Http\Controllers\Api\V2\PostController.php` >> `update()` >> `SendPostUpdatedEmailJob::dispatch($email)`
These does not populate `jobs` table: Scheduled tasks (unless they dispatch jobs), Artisan commands, event listeners.

- Running Queue Worker:
	- Run/test Locally: 
		- .env update:
			- `.env` >> `QUEUE_CONNECTION=database`
			- `.env` > `MAIL_MAILER=log`
			- Stop and run: `php artisan serve`
		- `php artisan queue:work` -- this will execute the jobs and if fails will add to `failed_jobs` table with the error
		- In another Terminal tab: `php artisan serve`
		- Test mail locally by sending Postman request: [PUT] `http://127.0.0.1:8000/api/v2/posts/8`
			- `app\Http\Controllers\Api\V2\PostController.php` >> `update()` >> `SendPostUpdatedEmailJob::dispatch($email)` will add to `jobs` table
			- Running Queue Worker (started by `php artisan queue:work`) will execute the job at once (if no delay is added)
			- Email will be sent (Or `laravel.log` will be updated)
			- The emails will be logged to your `storage/logs/laravel.log` file (since `.env` > `MAIL_MAILER=log` is set to test mail locally) 
			- If succeeded, the command out puts `Done`, it deletes the job from `jobs` table
			- If failed, the command out puts `Failed`, add to `failed_jobs` table
			- If you change anything in `app\Jobs\SendPostUpdatedEmailJob.php` you should restart: `php artisan queue:work`
		- Running a Queue Worker specify Connection and Queue: `php artisan queue:work redis --queue=default`
	- Run on STG/PRD: 
		- Cron Job Setup on Linux Server using CRON tab
			- `crontab -e`
		- Cron Job Setup on Linux Server using AWS Elastic Beanstalk extensions: `.ebextensions\laravel-queue-worker.config`
			- `leader_only`: true ensures that the command is only run on the leader instance in an autoscaling group.
			- The `--daemon` flag makes the worker run continuously, processing jobs as they come in.

- Handling Failed Jobs in a `failed_jobs` database table
	- A migration to create the `failed_jobs` table is already in Laravel 11 applications.
	- If not available:
		- Generate and run migrations:
			- `php artisan queue:failed-table`
			- `php artisan migrate`

		- Configure failed job services in `config\queue.php`:
		```'failed' => [
				'database' => env('DB_CONNECTION', 'mysql'),
				'table' => 'failed_jobs',
			],
		```
	- Retrying Failed Jobs:
		`php artisan queue:retry all`
		`php artisan queue:retry {job_id}`
	- Cleaning Up Failed Jobs: `php artisan queue:flush` 
		
- Also see *AWS* > *SQS* section


---


## Mails
- Uses Mailable, Queueable, SerializesModels, Envelope, Content etc.
- Send mail with attachments suing basic `log` driver to log the mail in `storage/logs/laravel.log`
- Basic email with `log` driver:
	- `.env` > `MAIL_MAILER=log`
	- Stop and run: `php artisan serve`
	- `app\Http\Controllers\Api\V2\CategoryController.php` >> `destroy()` >> `Mail::to()`
	- `php artisan make:mail CategoryDeleteEmail`
	- Change `app\Mail\CategoryDeleteEmail.php` accordingly
	- Test mail locally by sending Postman request: [DELETE] `http://127.0.0.1:8000/api/v2/categories/2`:
		- The emails will be logged to your `storage/logs/laravel.log` file
- Send emails with *Sendgrid* (with an attachment):
	- Install SendGrid: `composer require sendgrid/sendgrid`
	- Setup Mail Configuration in `.env` > Sendgrid
	- Create Mail Class: `php artisan make:mail CategoryDeleteEmail`
	- Update `app/Mail/CategoryDeleteEmail.php`
	- Create a Blade template at `resources/views/emails/categoryDeleteEmail.blade.php` and update `app/Mail/CategoryDeleteEmail.php` >> `content()` with its name `emails.categoryDeleteEmail`
	- Add mailing to a controler action: `app\Http\Controllers\Api\V2\CategoryController.php` >> `destroy()`
	- Add attachments files:
		- Create an `attachments` folder inside `storage/app` and paste testing files in there
		- Create a Symlink to Public Directory: `php artisan storage:link`
		- Update Controller to Use Storage Path
	- Testing (local/STG/PRD):
		- SendGrid can work in both local and production environments using the above steps. This will trigger the email sending functionality and you should receive an email with the attachments.
- Send Scheduled Emails: see *AWS > SQS*, *AWS SES* & *Scheduling* sections


---


## Caching with Redis
- Redis Cache Usage
	- **Purpose:** Improve application performance by storing frequently accessed data in-memory.
	- **Commonly Cached Data:** Session data, user profiles, product catalogs, dynamic content, API responses, leaderboards(sport score boards), real-time analytics, pub/sub messages.
	- **Use Cases:** Rate limiting, distributed locks, queues.
	- **Key Factors:** Read-heavy workloads, data staleness tolerance, cache size, cache invalidation.
- Installing Redis
	- Local setup: Redis on Windows and Setting Up with Laravel
		- Download Redis for Windows from Microsoft archive.
		- Extract the ZIP file and run redis-server.exe.
		- CMD as Admin: `redis-server --service-install redis.windows-service.conf --loglevel verbose redis-server --service-start`
		- To see what values are cached at the moment install `RedisInsight`
	- Deployed(PRD) setup: Installing Redis on AWS Linux EC2 for Laravel
		- Elastic Beanstalk 
			- Environment >> Configuration:
			```
				CACHE_STORE=redis
				REDIS_HOST=your-redis-endpoint
				REDIS_PASSWORD=null
				REDIS_PORT=6379
			```
			- Ensure the security group for your Elastic Beanstalk instances allows outbound traffic to the Redis server's security group on port `6379`.
		- EC2 instance:
			- Conect to the EC2 instance
			- Update package lists: `sudo yum check-update`
			- Update all packages: `sudo yum update -y`
			- Install Redis: `sudo amazon-linux-extras install redis6 -y`
			- Start the Redis service: `sudo systemctl start redis`
			- Enable Redis to start on boot: `sudo systemctl enable redis`
- Setting up Redis in Laravel project:
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
						```
						Redis::subscribe(['channel'], function ($message) {
							echo $message;
						});
						```
					- Publish to a channel: `Redis::publish('channel', 'Hello, World!');`
				- Working with Redis Hashes:
					- Set a hash value: `Redis::hset('hash_key', 'field', 'value');`
					- Get a hash value: `$value = Redis::hget('hash_key', 'field');`
- Disable Redis in Laravel project:
	- `config/cache.php` >> `'default' => env('CACHE_DRIVER', 'file'),` 
	- `config/session.php` >> `'driver' => env('SESSION_DRIVER', 'file'),`
	- `.env` >>  `CACHE_DRIVER=file` & `SESSION_DRIVER=file`


---


<h2 id="ci-cd">CI/CD Implementation</h2>

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


---


## Events vs Notifications:
- In Laravel, Events and Notifications are both powerful tools, but they serve different purposes and are used in different contexts.
- **Key Differences:**
	- **Purpose:**
		- Events: Used for handling actions that need to be processed in different parts of the system, often asynchronously.
		- Notifications: Specifically designed for sending notifications to users across multiple channels.

	- **Flexibility:**
		- Events: More flexible in terms of usage, can be used for anything from logging to triggering complex workflows.
		- Notifications: Tailored for user notifications with built-in support for different channels.

	- **Channels:**
		- Events: No built-in channels; you define how the event is handled.
		- Notifications: Comes with built-in channels like mail, SMS, Slack, etc.

	- **Complexity:**
		- Events: Requires setting up both events and listeners, which can be more complex.
		- Notifications: Easier to implement for user notifications, with less boilerplate code.
- **Summary:**
	Use Events when you need to decouple parts of your application or when you need to handle actions across multiple areas of your system. Use Notifications when you need to inform users about specific events that concern them, leveraging Laravel's built-in channels for delivery.


---


<h2 id="eda">Event-Driven Architecture (EDA), Events & Listeners:</h2>

- **EDA Benefits:**

	- Improved scalability: Can handle high loads by distributing event processing.
    - Flexibility: Easily add or remove event listeners without affecting core logic.
    - Reusability: Event listeners can be reused in different contexts.
    - Asynchronous processing: Can offload time-consuming tasks to background jobs, improving performance.
	- Events and listners are not only to send emails etc. but also for updating DBs, running other processes etc.

- **Laravel Events:** 
	- Events in Laravel allow you to implement the `Observer Pattern`, where events are dispatched, and multiple listeners can respond to them, promoting loose coupling in your application. In other words, decouples components of an application by using events as a communication mechanism.
	- Use `php artisan event:list` to list registered listeners.
	- Consider using event sourcing for storing a complete history of events, enabling easier auditing and replay. Use `Spatie Laravel Event Sourcing` packag: https://spatie.be/index.php/docs/laravel-event-sourcing/v7/introduction

- **Generating Events & Listeners:**
    Use Artisan commands to create events and listeners:
    - `php artisan make:event PostCreated`
    - `php artisan make:listener SendPostCreatedEmail --event=PostCreated`

- **Registering Events & Listeners:**
    - **Automatic Registration:**
            Laravel automatically registers listeners by scanning the `app/Listeners` directory. Methods named `handle` or` __invoke` will be registered.
			Events are discovered/registered by Laravel from the event that is type-hinted in the listners' `handle()` method's signature.
			Event Discovery in Production: Add `buildspec.yml` >> `php artisan event:cache` OR `php artisan optimize`
    - **Manual Registration:**
            Use the `Event` facade in `AppServiceProvider` >> `boot()` method. Event listeners can be registered globally or within specific routes or controllers.
        ```
        Event::listen(
            PostCreated::class,
            SendPostCreatedEmail::class,
        );
        ```
- **Other Event and Listner types**		
	- **Closure & Queueable Listeners:**

		- **Closure Listeners:**
			Can be defined/register directly in the `AppServiceProvider` >> `boot()` method using the `Event::listen()` method.
		
		- **Queueable Listeners:**
			Wrap closures with `queueable` for executing via queue.
			```
			Event::listen(queueable(function (PostCreated $event) {
				// Logic here...
			})->onConnection('redis')->onQueue('queue_name'));
			```

	- **Wildcard Event Listeners:**

		Allows catching multiple events with a single listener using `*` as a wildcard.

		```
		Event::listen('event.*', function (string $eventName, array $data) {
			// Handle wildcard event...
		});
		```

- **Defining Events & Listeners:**

    - **Event Class:**
        A simple data container that holds event-related information. For example, an `PostCreated` event might contain an `Post` instance.
        `App\Events\PostCreated.php`:
        ```
		namespace App\Events;
		
		use App\Models\Post;
		
        class PostCreated extends Event
        {
			use Dispatchable, InteractsWithSockets, SerializesModels;
			   
            public function __construct(public Post $post) 
			{
			    info("PostCreated event's >> __construct() method called");
				info("PostCreated event's >> post: " . json_encode($post));
				$this->post = $post;
			}
        }
        ```

    - **Listener Class:**

        - Listeners handle events. They receive event instances in their `handle` method. You can perform actions based on the event data.
			- **Queued Event Listeners:**
				- Events can be queued for asynchronous processing using `ShouldQueue` interface. Implement ShouldQueue interface to queue listeners for slow tasks like sending emails, calling third party APIs etc. 
				- Before using queued listeners, make sure to configure your queue and start a queue worker on your server or local development environment.
				- Customize queue behavior using `$connection`, `$queue`, and `$delay` properties or methods like `viaConnection` and `viaQueue`.
				- Handling Failed Jobs: Use the `failed` method to handle listener failures. Define `$tries` or `retryUntil` to limit retries.
	
		`App\Listeners\SendPostCreatedEmail.php`:
        ```
		namespace App\Listeners;
		use App\Events\PostCreated;
		use Illuminate\Contracts\Queue\ShouldQueue;
		
        class SendPostCreatedEmail implements ShouldQueue
        {
		    /**
			 * The name of the connection the job should be sent to.
			 *
			 * @var string|null
			 */
			public $connection = 'sqs';
		 
			/**
			 * The name of the queue the job should be sent to.
			 *
			 * @var string|null
			 */
			public $queue = 'listeners';
		 
			/**
			 * The time (seconds) before the job should be processed.
			 *
			 * @var int
			 */
			public $delay = 60;
	
            public function handle(PostCreated $event): void
            {
				info("PostCreated event's SendPostCreatedEmail listner's >> handle() method triggered");
                // Send Post Created email to the user - see "Create PostCreatedEmail Mailable" section below
				// Mail::to($event->post->user->email)->send(new PostCreatedEmail($event->post));
            }
        }
		
		/**
		* Handle a job failure.
		*/
		public function failed(PostCreated $event, Throwable $exception): void
		{
			// Handle the failure (e.g., log the error, notify admin, etc.)
			info('Failed to send post created email', [
				'post_id' => $event->post->id,
				'exception' => $exception->getMessage(),
			]);
		}
        ```
		
		- Create PostCreatedEmail Mailable for above listener: `php artisan make:mail PostCreatedEmail --markdown=emails.posts.created`
			- `App\Mail\PostCreatedEmail.php` and `resources/views/emails/posts/created.blade.php` will be created 
	
- **Dispatching Events:**

	- Use `Event::dispatch()` to dispatch/trigger an event. 
		- In your controller action etc.:
		```
		// When an Post is created - app\Http\Controllers\Api\V2\PostController.php >> store()
		PostCreated::dispatch($post);
		```
	- You can conditionally dispatch using `dispatchIf` or `dispatchUnless`.
	- Dispatch events after database transactions using `ShouldDispatchAfterCommit`.

- **Testing/Running Events & Listeners:**
	- Run/test Locally: 
		- .env update:
			- `.env` >> `QUEUE_CONNECTION=database`
			- `.env` > `MAIL_MAILER=log`
			- Stop and run: `php artisan serve`
		- Send a request to `PostController` >> `store()`
			- Event info in `PostCreated` >> `__construct()` will be logged
			- Job will be added to `jobs` table
		- `php artisan queue:work` - this will execute the jobs and:
			- if succeed,
				- Listener info in `SendPostCreatedEmail` >> `handle()` will be logged
				- Data will be removed from `jobs` table
			- if faild, will add to `failed_jobs` table with the error
			
	- Run on STG/PRD:
		- Event Discovery in Production & Cache event listeners for performance: Add `buildspec.yml` >> `php artisan event:cache` OR `php artisan optimize`
		- Cron Job Setup on Linux Server using CRON tab
			- `crontab -e`
		- Cron Job Setup on Linux Server using AWS Elastic Beanstalk extensions: `.ebextensions\laravel-queue-worker.config`
			- `leader_only`: true ensures that the command is only run on the leader instance in an autoscaling group.
			- The `--daemon` flag makes the worker run continuously, processing jobs as they come in.
			
	
- **Event Subscribers:**

    - Subscribers can listen to multiple events within a single class by defining event-handler methods. 
	- Define a `app\Listeners\UserEventSubscriber` >> `subscribe()` method to register listeners.
    ```
	<?php
	 
	namespace App\Listeners;
	 
	use Illuminate\Auth\Events\Login;
	use Illuminate\Auth\Events\Logout;
	use Illuminate\Events\Dispatcher;
	 
	class UserEventSubscriber
	{
		/**
		 * Handle user login events.
		 */
		public function handleUserLogin(Login $event): void {}
	 
		/**
		 * Handle user logout events.
		 */
		public function handleUserLogout(Logout $event): void {}
	 
		/**
		 * Method 1 - Register the listeners for the subscriber:
		 * 
		 */
		public function subscribe(Dispatcher $events): void
		{
			
			$events->listen(
				Login::class,
				[UserEventSubscriber::class, 'handleUserLogin']
			);
	 
			$events->listen(
				Logout::class,
				[UserEventSubscriber::class, 'handleUserLogout']
			);
		}
		
		/**
		 * Method 2 - Register the listeners for the subscriber: If your event listener methods are within the subscriber itself, you can return an array of events and corresponding method names from the subscriber's subscribe method.
		 * Laravel will then automatically determine the subscriber's class name when registering the event listeners.
		 * 
		 * @return array<string, string>
		 */
		public function subscribe(Dispatcher $events): array
		{
			return [
				Login::class => 'handleUserLogin',
				Logout::class => 'handleUserLogout',
			];
		}
	}
    ```

    - Register the `UserEventSubscriber` in `AppServiceProvider` >> `boot()` using `Event::subscribe()`:
	```
	namespace App\Providers;
 
	use App\Listeners\UserEventSubscriber;

	class AppServiceProvider extends ServiceProvider
	{
		/**
		 * Bootstrap any application services.
		 */
		public function boot(): void
		{
			Event::subscribe(UserEventSubscriber::class);
		}
	}
	```
	- **Testing/Running Events Subscribers:**
		- Run/test Locally: 
			- Send requests to `/login` & `/logout` routes
				- Event info will be logged			
		- Run on STG/PRD:
			- Same as local testing
			- Clear and Cache Configuration (Optional but Recommended):
				- `php artisan config:cache`
				- `php artisan event:cache`


---


<h2 id="webSockets">Event Broadcasting (WebSockets) - ToDo</h2>


---


## Notifications
- ToDo in the project

**Introduction**

- Laravel provides notifications for various channels (`email, SMS, Slack,` etc.)
- Notifications are short messages informing users about application events.
- You can store notifications in the database for display in your app's UI.

**Generating Notifications**
- Each notification is represented by a class in `app/Notifications`.
- Use `php artisan make:notification InvoicePaid` to create a notification class.
- The notification class defines methods for different channels (e.g., `toMail`).

**Sending Notifications**
There are two ways to send notifications:
- Using `notify` method of the `Notifiable` trait on your models (e.g., `$user->notify(new InvoicePaid($invoice))`)
- Using the `Notification` facade to send to multiple recipients (e.g., `Notification::send($users, new InvoicePaid($invoice))`)

**Specifying Delivery Channels**
- The `via` method on the notification class specifies delivery channels (e.g., `mail`, `database`).
- Use `community` channels for `Telegram, Pusher,` etc.
- The `via` method receives the `$notifiable` instance to determine channels.

**Queueing Notifications**
- Sending notifications can take time, especially for external API calls.
- Use the `ShouldQueue` interface and `Queueable` trait to queue notifications.
- Laravel automatically queues notifications with the default queue connection.

**Customizing Notification Queues**
- You can specify a different queue or connection for each notification channel using `viaQueues` and `viaConnections` methods.

**On-Demand Notifications**

- Use the `Notification` facade's `route` method for sending notifications without a stored user.
- Specify channels and recipient information (`email, Slack channel name`, etc.)

**Mail Notifications**

- Define a `toMail` method to format the email message using `MailMessage`.
- `MailMessage` provides methods for lines of text, call to action buttons, etc.
- Customize `sender, recipient, subject, mailer` using methods on `MailMessage`.
- `Markdown` mail notifications allow for more customized message content.
- You can preview mail notifications directly from routes or controllers.

**Database Notifications**
- Create `notifications` table: `php artisan make:notifications-table` & `php artisan migrate`
- Define a `toDatabase` or `toArray` method to return a data array for storage.
- This data is encoded as `JSON` and stored in the database table.

**Accessing Notifications**

- Use `$user->notifications` to retrieve notifications for a user.
- `unreadNotifications` property returns unread notifications.
- Define a notification controller for access from JavaScript.

**Marking Notifications as Read**

- Use `markAsRead` method on a notification or collection.
- Mass update with update query.

**Broadcast Notifications**

- Requires Laravel's event broadcasting services.

**Formatting Broadcast Notifications**

- `toBroadcast` method defines broadcast data.
- `toArray` used as fallback.
- `onConnection` and `onQueue` methods configure queuing.

**Customizing the Notification Type**

- `broadcastType` method defines the type for broadcasts.

**Listening for Notifications**

- Use Laravel `Echo`'s notification method on a channel.
- `receivesBroadcastNotificationsOn` method for custom channel.

**SMS Notifications**

- Requires `laravel/vonage-notification-channel` and `guzzlehttp/guzzle` packages.
- Environment variables for `Vonage` keys and sender number.

**Formatting SMS Notifications**

- `toVonage` method defines the SMS message content.
- `unicode` method for unicode characters.
- `from` method to customize sender number.
- `clientReference` for cost tracking.

**Routing SMS Notifications**

- `routeNotificationForVonage` method defines routing logic.

**Slack Notifications**

- Requires `laravel/slack-notification-channel` package.
- Configure Slack App with necessary scopes and tokens.

**Formatting Slack Notifications**

- `toSlack` method defines the Slack message content using Block Kit API.
- Supports interactive elements with buttons and confirmation modals.
- Use `dd` method to inspect built blocks.

**Routing Slack Notifications**

- `routeNotificationForSlack` method defines routing logic.
- Supports internal channels, external workspaces (Slack Routes).

**Notifying External Slack Workspaces**

- Requires Slack App distribution.
- Use Laravel Socialite's Slack driver to obtain bot tokens.
- `SlackRoute::make` method to route to external workspaces.

**Other Important Points**
- Localization support for notifications.
- Testing notifications using `Notification` facade's `fake` method.
- Notification events for sending and sent notifications.
- Custom notification channels.
		
		
---


## Clean Code Architecture
- **Purpose:**
	- Focuses on maintainable, scalable, and testable software.
	- Emphasizes separation of concerns, modularity, and clear boundaries.
- **Core Principles:**
	- **Separation of Concerns:**
		- Divides responsibilities into distinct layers.
	- **Dependency Inversion:**
		- High-level modules depend on abstractions: Abstractions are generalized interfaces or abstract classes that define behavior without specifying implementation, allowing flexibility, testability, and maintainability by decoupling high-level modules from specific low-level details.
		- High-level modules do not depend on low-level details.
	- **Modularity:**
		- System divided into reusable and maintainable modules.
	- **Testability:**
		- Supports easy testing of individual components.
- **Components:**
	- **Entities:** Core business objects with business rules.
	- **Use Cases:** Define application-specific business logic.
	- **Interface Adapters:** Manage communication between layers.
	- **Frameworks & Drivers:** External components isolated from core logic.
- **Layered Architecture:**
	- Inner layers (Entities, Use Cases) are more stable.
	- Outer layers (Interface Adapters, Frameworks) are easier to change.
- **Boundaries:**
	- Clear boundaries using interfaces or abstractions for flexibility.
- **DRY (Don't Repeat Yourself):**
	- Avoid code duplication. Write reusable functions and modules. This improves code maintainability and reduces errors.
- **KISS (Keep It Simple, Stupid):**
	- Prioritize simplicity in code design. Avoid unnecessary complexity. Write clear and easy-to-understand code. This enhances readability and reduces development time.


---


<h2 id="ddd">Domain-Driven Design (DDD)</h2>
    
DDD structures and designs software systems around the core business domain.
- **Benefits of DDD:**
	- **Alignment with Business Needs:**
		Reflects the real-world domain, enhancing collaboration between developers and domain experts.
	- **Improved Communication:**
		Promotes a shared language (Ubiquitous Language) between stakeholders, reducing misunderstandings.
	- **Focus on Core Domain:**
		Prioritizes critical business aspects, ensuring efficient resource use. Ensures alignment with business goals.
	- **Easier Maintenance:**
		Organizes code around domain concepts, simplifying long-term management.
	- **Enhanced Modularity:**
		Breaks down systems into well-defined components, improving flexibility and modularity. Enhances separation of concerns and modularity.
	- **Scalability:**
		Allows independent scaling of system parts based on business needs.
	- **Framework Independence:**
		Enables easy changes or updates to frameworks (e.g., Laravel, Express) without affecting core business logic.
- **Relation to Clean Code Architecture:**
	It aligns well with the Clean Code Architecture's emphasis on separation of concerns and modularity, but they are not strictly "parts" of it.


---


## SOLID Principles

The SOLID principles are a set of guidelines for designing software components that are easy to maintain, extend, and understand.

- **Principles:**
	- **Single-Responsibility Principle:** Each class should given one responsibility and have only one reason to change

	- **Open-Closed Principle:** A class should be open for extension but closed for modification

	- **Liskov Substitution Principle:** Each base class can be replaced by its subclasses

	- **Interface Segregation Principle:** You should have many small interfaces instead of a few huge ones

	- **Dependency Inversion Principle:** Depend upon abstraction, not on concrete implementation

- **Relation to Clean Code Architecture:**
	SOLID principles are closely related to Clean Code Architecture, but they are not strictly "parts" of it. Instead, they complement and support Clean Code Architecture in achieving its goals of maintainability, scalability, and testability. Examples: 
	- Single Responsibility Principle (SRP) supports modularity and separation of concerns.
	- Dependency Inversion Principle (DIP) is integral to the architecture's emphasis on dependency management and abstraction.

- **How SOLID principles were implemented in the Advance CRUD BE:**

	- **Single Responsibility Principle (SRP) implementation:**
		
		**1. PostResource:** `app\Http\Resources\PostResource.php` to handle response formatting in a seperate class.
		
		**2. CreateUserRequest:** `app\Http\Requests\CreateUserRequest.php` to handle request validation in a seperate class.
		
		**3. PostValidatorService:** `app\Services\PostValidatorService.php` to handle validation in a seperate class.

		**4. Using Repository Design Pattern:**
		- Note: The **Repository Design Pattern** is used to abstract the data access layer of an application. It acts as a mediator between the domain and data mapping layers, such as a database or an API, providing a consistent and organized way to access, manipulate, and retrieve data. It abstracts the data access logic for the `Post` & `Category` entities in here. 
		- **PostRepositoryInterface:** Defines the contract for post-related operations. This interface ensures that each repository has a single responsibility related to handling `Post` entities. It separates concerns by isolating the data access logic from other parts of the application.
		- **PostRepository:** Implements the interface, encapsulating the data access logic. This class implements the `PostRepositoryInterface`, handling all CRUD operations related to the `Post` model. It ensures that each class or module has one, and only one, reason to change.
		- **Service Provider:** Binds the interface to the concrete implementation, enabling dependency injection. So Laravel knows which class (e.g.: PostRepository) to instantiate when a certain interface (e.g.: PostRepositoryInterface) is injected.
		- **Controller & Models:** Uses the repository interface, adhering to the Dependency Inversion Principle.
		- **File changes:**
			- `Post` Entity
				- Create the Interface `app/Contracts/PostRepositoryInterface.php`
				- Create the Repository Implementation `app/Repositories/PostRepository.php`
				- Changes to: `app\Providers\AppServiceProvider.php` >> `register()`
				- Changes to: `app\Models\Post.php` to use PostRepositoryInterface
				- Changes to: `app\Http\Controllers\Api\V2\PostController.php`

	- **Open/Closed Principle (OCP) implementation:**
		
		**1. Using Repository Design Pattern:**
		- **PostRepositoryInterface:** The `PostRepositoryInterface` allows the `PostRepository` class to be open for extension but closed for modification. If additional behavior is required, you can extend or implement the interface in a new class without altering the existing `PostRepository` code.
		
		**2. Using Traits:**
		- **Trait (`Likeable`):** This encapsulates the `like()` and `dislike()` methods, providing a reusable way to extend functionality to any model that needs it.
		Models (`Post` and `Comment`): By using the Likeable trait, both models gain the ability to be "liked" or "disliked" without modifying their core logic, thus adhering to the Open-Closed Principle.
		- Create trait in `app\Traits\Likeable.php`. Traits are reusable code blocks in PHP that can be included in multiple classes without inheritance. They provide a way to share common functionality between classes without creating a complex class hierarchy and without using multiple inheritance which is not supported by PHP.
		- Use the trait in `app\Models\Post.php` and `app\Models\Comment.php`
		- Sample Usage - e.g: add this in a controller action to easily like or dislike posts and comments:
		```
			$post = Post::find(1);
			$post->like();

			$comment = Comment::find(1);
			$comment->dislike();
		```


	- **Liskov Substitution Principle (LSP) implementation:**
	
		**1. Using Repository Design Pattern:**
		By using the `PostRepositoryInterface` in the `PostController`, any implementation of this interface (like `PostRepository`) can be substituted without altering the functionality of the controller. This adheres to the LSP, ensuring that derived classes can replace base classes without affecting the application's correctness.

		**2. Using an Abstract Class:**
		Hypothetical usage: Author can click on two buttons in the React app UI and the buttons will send two Axios requests to below routes in `routes\api_v2.php:`
		
		```
		Route::post('/subscribe/mailchimp', [AuthorController::class, 'subscribeToMailList'])->defaults('emailProvider', new MailChimp);
		```
		```
		Route::post('/subscribe/sendgrid', [AuthorController::class, 'subscribeToMailList'])->defaults('emailProvider', new SendGrid);
		```

		- Abstract Class in `app/Services/EmailProviders/EmailProvider.php`
		- Concrete subclasses for MailChimp and SendGrid in `app\Services\EmailProviders\MailChimp.php` & `app\Services\EmailProviders\SendGrid.php`
		- Method to accept any EmailProvider type, allowing for either MailChimp or SendGrid (or any future providers) to be used interchangeably: `app\Http\Controllers\Api\V2\AuthorController.php` >> `subscribeToMailList()`
		- Model: `app\Models\Author.php`
			
			
	- **Interface Segregation Principle (ISP) implementation:**

		By segregating the interfaces for Post(`app\Repositories\PostRepository.php`) and Category(`app\Repositories\CategoryRepository.php`), you ensure that each controller only relies on the methods it actually needs, adhering to the Interface Segregation Principle. This approach makes your code more modular, easier to test, and more maintainable.
		Each of the interfaces is designed to be focused on specific methods related to the `Post`/`Category` model. This ensures that classes implementing this interface are not forced to implement methods they don't use. Although the interface in this case is quite broad, it can still be considered under ISP as it is not forcing unnecessary methods on unrelated classes.

	- **Dependency Inversion Principle (DIP) implementation:**

		The `PostController` depends on the `PostRepositoryInterface` rather than the concrete `PostRepository` class. This follows the DIP by ensuring that high-level modules (controllers e.g: `PostController`) do not depend on low-level modules (concrete repositories e.g: `PostRepository`), but both depend on abstractions (interfaces e.g: `PostRepositoryInterface`).


---


## Design Patterns
- **Types of Design Patterns**

	Design patterns can be categorized into three main types based on their purpose:
	
	**1. Creational Patterns:** These patterns deal with object creation mechanisms. They help in creating objects in a flexible and controlled way.		
		
	- **Factory Pattern:** Creates objects without specifying their exact class.
	- **Abstract Factory Pattern:** Creates families of related objects without specifying their concrete classes.
	- **Singleton Pattern:** Ensures that a class has only one instance and provides a global point of access to it.   

	**2. Structural Patterns:** These patterns deal with how classes and objects are composed to form larger structures. They focus on relationships between objects.

	- **Adapter Pattern:** Converts the interface of a class into another interface that clients expect.
	- **Decorator Pattern:** Adds additional responsibilities to objects dynamically.
	- **Facade Pattern:** Provides a unified interface to a set of interfaces in a subsystem.

	**3. Behavioral Patterns:**	These patterns deal with how objects interact and communicate with each other. They focus on the algorithms and responsibilities of objects.

	- **Strategy Pattern:** Defines a family of algorithms, encapsulates each one, and makes them interchangeable.
	- **Observer Pattern:** Defines a one-to-many dependency between objects so that when one object changes state, all its dependents are notified and updated automatically.   

- **1. Singleton Pattern**

	**Usage in Laravel:** Ensures a class has only one instance and provides global access. Facades in Laravel use this pattern as proxies to service container bindings.
	
	**Example:** `Cache::get('key')` uses a single instance of the cache manager.

	- **Singleton Pattern with Service Container**

		**Usage in Laravel:** The service container manages dependencies and uses the Singleton pattern to create and use only one instance of a service across the application.
	
		**Example:**

		```

		public function register()
		{
			$this->app->singleton('MyService', function ($app) {
				return new MyService();
			});
		}
		```
		
		`app('MyService')` always returns the same instance.

- **2. Facade Pattern**

	**Usage in Laravel:** Provides a "static" interface to classes in the service container, simplifying dependency injection.
	
	**Example:**

	```

	'aliases' => [
		'Cache' => Illuminate\Support\Facades\Cache::class,
	]
	```
	
	`Cache::get('key')` is a facade accessing the cache service.
	
- **Combining Singleton and Facade Patterns**

	Facades often resolve singletons from the service container, allowing concise, expressive code while maintaining dependency injection benefits.

- **3. Factory Pattern**

	**Usage in Laravel:** The Factory pattern is used to create objects without specifying the exact class of object that will be created. Laravel uses this pattern in model factories for generating fake data during testing.
	
	**Example:** You can create a new instance of a model using a factory like this:

	```
	$user = User::factory()->create();
	```

- **4. Repository Pattern**

	**Usage in Laravel:** This pattern is not built into Laravel but is commonly implemented by developers to separate business logic from data access. The Repository pattern abstracts the data layer, providing a way to access data in the application from multiple sources (like databases, APIs, etc.).
	
	**Example:** You can create a PostRepository to handle all database interactions related to the Post model, allowing the PostController to focus on business logic.

	```
	class PostRepository {
		public function getAllPosts() {
			return Post::all();
		}
	}
	```
	
- **5. Strategy Pattern**

	**Usage in Laravel:** The Strategy pattern allows you to define a family of algorithms, encapsulate each one, and make them interchangeable. In Laravel, you can use this pattern to swap out different implementations of a task, such as sending notifications via different channels (email, SMS, etc.).
	
	**Example:** Laravel’s notification system allows you to send notifications via multiple channels:

	```
	Notification::send($users, new InvoicePaid($invoice));
	```
	
- **6. Observer Pattern**

	**Usage in Laravel:** The Observer pattern is used to notify multiple objects about any changes to the state of another object.
	
	**Example 1:** Laravel’s Eloquent ORM implements this pattern with model observers, allowing you to listen to various changes in the model in events like creating, updating, deleting, etc.
	
	- Benefits of Using Observers: Separation of concerns, reusability, organization, improved code readability, maintainability, centralized event handling logic
	- Create an Observer: `php artisan make:observer PostObserver --model=Post`
	- Created file: `App\Observers\PostObserver.php`
		- Add code for model event functions `creating()`, `updating()` etc.
	- Register the Observer: `App\Providers\AppServiceProvider.php`
	- See "Advance Eloquent Techniques" >> "Events and Observers" for more info
		
	**Example 2:** Events in Laravel allow you to implement the `Observer Pattern`, where events are dispatched, and multiple listeners can respond to them, promoting loose coupling in your application.
	- See "Events and listners" for more info
	
	
- **7. Decorator Pattern**

	**Usage in Laravel:** The Decorator pattern allows you to dynamically add behavior to an object. Laravel uses this pattern in its middleware system, where each middleware adds some behavior to the request before passing it to the next middleware or to the final application logic.
	
	**Example:** Middleware like auth can be used to check if a user is authenticated before allowing them to access certain routes:

	```
	Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
	```

- **8. Adapter Pattern**

	**Usage in Laravel:** The Adapter pattern allows an interface to be used as another interface. In Laravel, this is often seen in the implementation of service providers, where different services are adapted to work with Laravel's service container.
	
	**Example:** An adapter can be used to integrate a third-party payment service into your Laravel application, making it compatible with your existing code.



- **9. Chain of Responsibility Pattern**

	**Usage in Laravel:** The Chain of Responsibility pattern passes a request along a chain of handlers, where each handler decides either to process the request or pass it on. Laravel’s pipeline and middleware systems are examples of this pattern.
	
	**Example:** You can create a pipeline to process a job through a series of tasks:

	```
	Pipeline::send($job)
		->through([Task1::class, Task2::class])
		->thenReturn();	
	```


# How to run the application 
- Install required Composer packages using: `composer i`
- Set proper database configuration in `.env`
- Run migration: `php artisan migrate`
- Run seeder: `php artisan db:seed`
- Run application locally: `php artisan serve`
- Test user: `test@example.com` | `Abc@123`
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
