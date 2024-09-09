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


- [**Event Broadcasting (WebSockets)**](#webSockets)


- [**Notifications**](#notifications)


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

<h2 id="docker">Docker - Dockerizing a Laravel Application </h2>

### Benefits of using Docker in Laravel development Locally:

- **Consistent Environment:** Ensures all developers work in the same setup, avoiding "works on my machine" issues.
- **Easy Setup:** Quick environment setup with all dependencies via a single `docker-compose` command.
- **Isolation:** Runs in a sandbox, preventing conflicts with other apps.
- **Collaboration:** Simplifies onboarding and collaboration with easy-to-replicate environments.
- **Environment Parity:** Mirrors production setup for better testing and debugging.
- **Version Control:** Easily revert to previous states of the environment.
- **Automated Testing:** Runs tests in clean, isolated environments for reliable results.

### Benefits of using Docker in Laravel development for Deployments:
- **Portability:** Runs on any system supporting Docker, easing transitions between environments.
- **Scalability:** Scales applications easily by running multiple container instances.
- **Security:** Enhances security through isolated containers.
- **Simplified Deployment:** Streamlines updates and rollbacks, integrates well with CI/CD pipelines.
- **Dependency Management:** Bundles all dependencies, ensuring consistent runtime environments.
- **Immutable Infrastructure:** Uses new containers for updates, reducing configuration drift.
- **Resource Efficiency:** More lightweight than VMs, allowing better resource utilization.

### Docker Glossary:
- **Docker Hub (hub.docker.com):** A platform for hosting Docker images, handling user authentication, automating builds, and integrating with GitHub/Bitbucket for workflows.
- **Registry:** A service hosting Docker image repositories, accessible via Docker Hub or the `docker search` command for managing images. Common registries:
	- **Docker Hub Registry:** Docker Hub (hub.docker.com) is the default Docker registry. It hosts many repositories, such as official images (like Nginx, Redis, MySQL) and user-created repositories.
		- Example command to search for a repository in the Docker Hub registry: `docker search nginx`
	- **AWS ECR (Elastic Container Registry)** is a fully-managed Docker registry by AWS. It securely stores, manages, and deploys Docker images, integrated with AWS services like ECS, EKS, and EC2. You can use AWS ECR to:
		- **Push** Docker images from your local environment or CI/CD pipelines.
			- `docker tag my-app:latest <aws_account_id>.dkr.ecr.<region>.amazonaws.com/my-repo:latest`
			- `docker push <aws_account_id>.dkr.ecr.<region>.amazonaws.com/my-repo:latest`
		- **Pull** images from ECR to deploy them to services like **ECS, EKS,** or **EC2**.
- **Repository:** A collection of Docker images, stored in a registry and labeled with tags. Repositories can be shared by pushing to a server. E.g.: Nginx Docker Repository:
A Docker repository named `nginx` can contain different versions (or tags) of the Nginx image, such as `nginx:1.21, nginx:1.22,` etc. Each tag represents a specific version of the image.
Example command to pull an image from the repository: `docker pull nginx:latest`
- **Image:** A Docker image is a set of filesystem changes and execution instructions used to create containers. Images are unchangeable.
- **Parent Image:** The base image in a Dockerfile (specified by `FROM`) on which all other instructions build. Some do not have a parent (e.g., `FROM scratch`).
- **Container:** A runtime instance of a Docker image, including the image, environment, and instructions. It's like a shipping container for software.
- **Layer:** Each step in a Dockerfile creates a layer in the image. Layers are cached and only update if changed, making images efficient.
- **Filesystem:** A system that organizes and stores files on an operating system (e.g., Linux: ext4, Windows: NTFS, macOS: HFS+).
- **SSH:** A secure protocol for remotely accessing machines, encrypting data, and authenticating logins via public/private key pairs.
- **Service:** Defines how to run containers in a Docker swarm, specifying the image, commands, and desired state, like the number of tasks.
- **Volume:** A directory in a container that stores persistent data. Volumes aren’t deleted automatically and exist beyond the container's lifecycle.
- **Dockerfile:** Specifies the environment for running a Laravel app, including PHP version and necessary extensions for proper functionality.
- **Docker Compose:** A tool for defining and running multi-container apps using a YAML file to manage services and their configurations.
	- **docker-compose.yml:** Configures multi-container apps with Nginx, PHP-FPM, and MySQL, ensuring they communicate over a shared network.
- **Nginx Configuration:** Sets up the web server to manage HTTP requests and send PHP requests to the PHP-FPM container for processing.
- **PHP Configuration:** Configures PHP settings needed to run the Laravel application effectively, ensuring proper functionality.
- **PHP - FPM (FastCGI Process Manager):** Manages PHP processes, isolating each request to ensure that an error in one process doesn’t affect others or overall system stability.
- **WSL:** Windows Subsystem for Linux

### `docker` vs `docker-compose` commands
- Use `docker` commands for:
	- **Managing individual containers:** `docker start <container_id>, docker stop <container_id>, docker rm <container_id>`
	- **Handling images:** `docker pull <image_name>, docker build -t <image_name> , docker rmi <image_name>`
	- **Container details:** `docker inspect <container_id>, docker logs <container_id>`
- Use `docker-compose` commands for:
	- **Multi-container applications:** `docker-compose up -d, docker-compose down`
	- **Service management:** `docker-compose logs, docker-compose build`
	- **Networking and volumes:** Automatically managed by Compose

### Setting Up Docker on Windows (10 Pro)
#### Install Docker Desktop for Windows: ####
- Download Docker Desktop for Windows from Docker's official site.
- Run the installer and follow the instructions.
	- Check these two checkboxes when installing:
	- Use Windows Subsystem for Linux (WSL) 2 instead of Hyper-V (recommended)
	- Add shortcut to desktop
	- Restart
	- Complete the installation of Docker Desktop.: Use recommended settings (requires administrator password)
	- Sign in to docker.com >> Google >> xxxxxx@gmail.com
- Ensure Docker Desktop is using the Windows Subsystem for Linux (WSL) 2 backend. This should be the default for Windows 10 Pro.

#### Configure WSL 2: ####
- Ensure WSL 2 is enabled. You can do this by opening PowerShell as an administrator and running: `wsl --set-default-version 2`
	- OP: 
	```
		For information on key differences with WSL 2 please visit https://aka.ms/wsl2
		The operation completed successfully.
	```
#### Install a Linux distribution from the Microsoft Store (e.g., Ubuntu). ####
- Press `Win + S` and type `Microsoft Store` to open it.
- Search for your preferred Linux distribution: Type `Ubuntu` in the search bar. You can choose from different versions like `Ubuntu, Ubuntu 20.04 LTS, or Ubuntu 18.04 LTS`
- Click on the desired Ubuntu version (Ubuntu 22.04.3 LTS) and then click `Get` to start the installation.
- Once installed, click `Launch` or find it in the Start menu and open it.
- Enter new UNIX (root) user: abc_user | 123456
- Update Ubuntu packages: `sudo apt update`

#### Verify Docker Installation: ####
- Open PowerShell (as Administrator if required) and run: 
	- `docker --version`
	- `wsl --list --verbose`
		- This command lists all installed distributions and their WSL versions. Ensure your Ubuntu distribution is set to version 2

### Docker Configuration/Setting-up Steps for Laravel project
- Relevant Files:
	- `Dockerfile`
	- `docker-compose.yml`
	- `nginx\default.conf`
	- `php\local.ini`
	- `.env`
- Build the images: `docker-compose build` or `docker-compose up --build -d` to build, run/start containers in detached(background) mode
	- Image will be built with the name in `package-lock.json` >> "name" which is the local project folder name. 
	- Image will be listed:
		- List all containers: `docker-compose ps`
			- List all containers, including stopped: `docker-compose ps -a`
		- OR view in Docker Desktop >> Local Images
	- If you want to delete/remove image:
		- This will both bring down, and destroy, the containers and any associated non-volume data that was stored in them.: `docker-compose down`
		- Remove a specific container: `docker-compose rm <container_id_or_name>` e.g: `docker-compose rm mysql`
			- OR Delete from Docker Desktop >> Local Images
			- Force remove a container (if the container is still running or if you encounter an issue): `docker-compose rm -f <container_id_or_name>`
		- Stops a running Docker container: `docker stop <container_id>`
		- If you want to create again: `docker-compose up --build -d`
	- Lists all Docker images on your system: `docker images`
	- Downloads (pulls) a Docker image from a remote registry, like Docker Hub, to your local system - not required right now: `docker pull mysql:5.7`
- Starting up the containers: `docker-compose up -d`
	- Docker will set up a network named `laravel` and start three containers based on the definitions in the `docker-compose.yml` >> `services` section. The `-d` flag keeps these containers running in the background even after their initial setup is complete. Without this flag, Docker would stop them once they've finished starting up.
- Configuring Laravel in `.env`:
	- `DB_HOST=db`: This tells your Laravel application to connect to the MySQL database service you defined in your Docker Compose file.
	- `APP_URL=http://localhost:8080`: This sets the URL for your Laravel application. The port number `(8080)` should match the one you've exposed in your Nginx container, ensuring that your Laravel app can be accessed from outside your Docker container.
		```
		# Changes for Dockerizing - as specified in docker-compose.yml 
		DB_CONNECTION=mysql
		DB_HOST=db # Use the service name 'db' since that's the name of your MySQL container in docker-compose.yml
		DB_PORT=3306 # MySQL listens on port 3306 inside the container
		DB_DATABASE=laravel-crud-api
		DB_USERNAME=laravel
		DB_PASSWORD=secret
		```
- When change anything in these files: `docker-compose.yml`, `Dockerfile`, `nginx\default.conf`
	- `docker-compose down`
	- `docker-compose up --build -d`
- Integration with XAMPP - Since XAMPP runs a separate Apache and MySQL service, ensure there are no port conflicts:
	- XAMPP Apache usually runs on port `80`, so we used port `8000` for Nginx in Docker.
	- XAMPP MySQL usually runs on port `3306`. The MySQL container in Docker will not conflict since Docker isolates container ports.
- To view the logs of a specific container, use:
	- `docker-compose logs app`
	- `docker-compose logs webserver`
	- `docker-compose logs db`
	
- If you cannot access your application at `http://localhost:8000`, ensure:
	- The containers are running without errors (`docker ps` and `docker-compose logs` can help).
	- The Nginx configuration `nginx/default.conf` points to the correct document root `/var/www/public`.
	- Your Laravel application files are correctly copied into the Docker container by SSH into it:
		- `docker-compose exec php bash`
		- `cd /var/www`
		- `ls`

- Accessing the application("php") container: 
	- Note: Instead of a VM where you ssh into the system and execute commands directly on the OS, Docker prefers that you pass commands through to the container(s) and then the output of those commands is echoed back to your terminal. 
		- `docker-compose exec php php /var/www/artisan migrate`
		- `docker-compose exec php php /var/www/artisan route:list`
	- Still if you want you can SSH directly into the `php` container: `docker-compose exec php bash`
		- Now can run any command:
			- `cd /var/www`
			- `ls`
			- `php artisan migrate`
			- `composer install (not required)`
			- `composer clear-cache (not required)`
			- `composer dump-autoload (not required)`
			- `exit`
- Accessing the application on the browser: 
	- No need to run `php artisan serve` which is used for development when running the built-in PHP development server, but in this Docker setup, Nginx is configured to serve the application.
	- `http://localhost:8080/`
- Accessing Swagger documentation: `http://localhost:8080/api/documentation`
- Accessing MySQL database using MySQL Workbench:
	- Hostname: `localhost` or `127.0.0.1` (this points to your local machine)
	- Port: `3307` (since you're mapping container's MySQL port `3306` to port `3307` on your local machine)
	- Username: `laravel` (as set in your `docker-compose.yml` under `MYSQL_USER`)
	- Password: `secret` (as set in `MYSQL_PASSWORD`)
	- Database: `laravel-crud-api` (optional, you can specify the database here or leave it blank to see all databases)
	- **important**: SSL tab > No SSL. Otherwise you'll get SSL connection error.
	- Troubleshooting
		- Firewall: Ensure your firewall allows connections to port `3306`
		- MySQL Configuration: Ensure the MySQL server in the Docker container is properly configured to accept connections from the host.

- Running multiple projects on your local machine: 
	- Create `docker-compose.yml` in that project folder with different network name, ports to expose on your local machine (e.g. `8080` for one, `8081` for another). 

### Automatic Deployment of a locally Dockerized Laravel application to AWS using CI/CD ###
#### Summary ####
- **CI/CD stack used(not in exact order):**
	- Local Docker env >> Bitbucket >>
	- AWS CodePipeline 
		- SourceStage: source Bitbucket, Input Artifacts: SourceArtifacts
		- BuildStage: AWS CodeBuild 
			- uses buildspec.yml commands to Push Docker image to AWS ECR
		- DeploymentStage: AWS CodeDeploy
			- AWS ECR >> Connect and get image Artifacts build previously by CodeBuild from ECR and deploy to ECS cluster >>  AWS ECS
			- Output Artifacts: BuildArtifacts
			- AWS EC2/Fargate  
		- EC2 > Route 53 to setup custom domain
- **AWS ECR(Elastic Container Registry):** A fully managed container registry service that allows you to store, share, and deploy your container images securely and efficiently.
- **AWS Elastic Beanstalk:** Easiest for deployment but less configurations and control.
- **AWS ECS - Elastic Container Service (with EC2 or Fargate):** More control and scalability.
- **AWS Route 53:** Manage your custom domain and SSL certificates.
- **AWS Fargate:** A serverless cloud computing service that allows you to run  Docker containers without managing servers or infrastructure. It automatically scales resources based on demand and charges only for the resources used.
#### Step 1: Dockerize the Laravel Application Locally as done above ####

#### Step 2 -  Add relevant commands to buildspec.yml to be used in AWS CodePipeline > AWS CodeBuild (This setup streamline the process of building and deploying Docker images to AWS ECS through AWS ECR): ####
- Set following environment variables in the AWS CodeBuild project:
	```
	AWS_ACCOUNT_ID
	AWS_DEFAULT_REGION
	ECR_REPOSITORY_NAME
	ECS_CLUSTER_NAME
	ECS_SERVICE_NAME
	```
- **Install Phase:** Added Docker installation along with PHP and AWS CLI.
- **Pre-build Phase:** Logs into ECR and sets up variables for the repository URI and the image tag (using the CodeBuild commit ID).
- **Build Phase:** Builds the Docker image and tags it with both latest and the specific commit ID tag.
- **Post-build Phase:**
	- Pushes the Docker image to ECR.
	- Deploys the updated image to ECS by forcing a new deployment.

**buildspec.yml:**
```
version: 0.2
phases:
  install:
    runtime-versions:
      php: 8.2
      docker: 20
    commands:
      - apt-get update
      - apt-get install -y zip unzip awscli docker
      - cd src
      - composer install --no-interaction --no-scripts
      - cd ..
  pre_build:
    commands:
      - echo "Logging in to Amazon ECR..."
      - aws ecr get-login-password --region $AWS_DEFAULT_REGION | docker login --username AWS --password-stdin $AWS_ACCOUNT_ID.dkr.ecr.$AWS_DEFAULT_REGION.amazonaws.com
      - REPOSITORY_URI=$AWS_ACCOUNT_ID.dkr.ecr.$AWS_DEFAULT_REGION.amazonaws.com/$ECR_REPOSITORY_NAME
      - IMAGE_TAG=$(echo $CODEBUILD_RESOLVED_SOURCE_VERSION | cut -c 1-7)
  build:
    commands:
      - echo "Building the Docker image started on:"  `date`
      - docker build -t $ECR_REPOSITORY_NAME ./src
      - docker tag $ECR_REPOSITORY_NAME:latest $REPOSITORY_URI:latest
      - docker tag $ECR_REPOSITORY_NAME:latest $REPOSITORY_URI:$IMAGE_TAG
  post_build:
    commands:
      - echo "Pushing the Docker images to ECR..."
      - docker push $REPOSITORY_URI:latest
      - docker push $REPOSITORY_URI:$IMAGE_TAG
      - echo "Updating the ECS service..."
      - aws ecs update-service --cluster $ECS_CLUSTER_NAME --service $ECS_SERVICE_NAME --force-new-deployment
artifacts:
  files: '**/*'
  base-directory: 'src/'
  exclude-paths: '**/src/vendor'
```
 
#### Step 3 - ECR configuration - Create an ECR Repository: ####
Amazon ECR > Create a new repository for the Laravel Docker image

#### Step 4 - Option 1: Deploy to AWS (Elastic Beanstalk Option - for simpler deployments) ####
Elastic Beanstalk handles orchestration (like provisioning EC2, load balancers, etc.) for you, making it simpler to deploy the Dockerized Laravel app.

##### Step 1: Create an Elastic Beanstalk Application #####
- Go to **AWS Management Console** > **Elastic Beanstalk**
- Click **Create Application**
- Choose **Platform**: Docker (not PHP)
- For the application, configure Docker as the environment

##### Step 2: Setup AWS CodePipeline for CI/CD #####
- Go to **AWS Management Console > CodePipeline > Create Pipeline**
- **Source: Bitbucket** as the repository provider, link your Bitbucket repository
- **Build Stage:**
	- Choose **AWS CodeBuild** for building the Docker image
	- Create a `buildspec.yml` file in your project root as above
- **Deploy Stage:**
	- AWS CodeDeploy will take Artifacts from AWS ECR 
	- Choose **Elastic Beanstalk** as the deploy provider
	- Choose the previously created **Elastic Beanstalk environment**

##### Step 3: Route 53 Domain Setup #####
- Go to **Route 53 > Create Hosted Zone** for abc.com
- Add an **A Record** pointing to the **Elastic Beanstalk URL**
- Use **SSL certificates** (via ACM) for HTTPS

#### Step 4 - Option 2: Deploy to AWS (ECS with EC2 or Fargate Option) ####
##### Step 1: Create ECS Cluster and Task Definition #####
- **Amazon ECS > Create Cluster**
	- Choose **EC2 Linux + Networking** or **Fargate** based on your use case. If you want a serverless option select Fargate.
- **Amazon ECS > Create Task Definition:**
	- Define a new **Task Definition** that specifies how to run the Docker container
- **Specify the Docker image location** (from your ECR)
	- Select **EC2** or **Fargate** and configure your container with your image from ECR (Docker image, ports, etc.)
	- Set environment variables such as DB_CONNECTION, DB_HOST, DB_USERNAME, DB_PASSWORD, etc.
- **Create Service:**
	- Cluster >> create a service that uses above task definition
	- Set up autoscaling, load balancers (If you need high availability, set up an Application Load Balancer under EC2) , and security groups as needed.
- **Set Up Database (RDS):**
	- Use Amazon RDS to create a MySQL or PostgreSQL instance.
	- Set environment variables in your ECS task to connect to the RDS database.

##### Step 2: Push Docker Image to ECR #####
- Create an **ECR repository**:
	- Go to ECR > Create Repository.
	- Tag and push your Docker image to ECR - commands added in buildspec.yml

##### Step 3: Configure AWS CodePipeline for CI/CD #####
- Go to **AWS CodePipeline > Create Pipeline**
- **Source: Bitbucket**
- **Build Stage:**
	- Use **AWS CodeBuild** to build and push the Docker image to ECR.
	- `buildspec.yml` similar to above.
- **Deploy Stage:**
	- Choose **Amazon ECS**
	- Select the ECS Cluster and Service to deploy the new task.

##### Step 4: Set Up Route 53 Domain #####
- Go to **Route 53 > Create Hosted Zone** for abc.com
- Add an **A Record** pointing to the **ECS Load Balancer**
- Use **SSL certificates** (via ACM) for HTTPS

#### Step 5: Additional AWS Setup ####
- EC2 Instances for ECS Cluster
	- When using **EC2**, create EC2 instances to host your ECS containers.
	- If you opt for **Fargate**, you won’t need to manage EC2 instances (serverless).
- Setup Storage (S3)
	- If your Laravel app uses local file storage, you should switch to using AWS S3. Install the S3 filesystem package for Laravel:
		`composer require league/flysystem-aws-s3-v3`

- Update .env with S3 credentials:
```
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket_name
```

- Update config/filesystems.php to use S3:
```
'disks' => [
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
    ],
],
```


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
		- `app\Http\Controllers\Api\V2\PostController.php` >> `addPostComment()`
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
	- Consider using event sourcing for storing a complete history of events, enabling easier auditing, replay and history reporting. Use `Spatie Laravel Event Sourcing` packag: https://spatie.be/index.php/docs/laravel-event-sourcing/v7/introduction

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


<h2 id="webSockets">Event Broadcasting (WebSockets)</h2>


**Note:** 

I use Pusher over Reverb since I have previous experince in Pusher with Node.js(Nest.js) + React.js using websockets for sending notifications. Reverb is the priority of Laravel 11.

**What is a real-time application?**

- Traditional web applications/Rest APIs as requests and responses. Client send a request and server send back a response.
- Information must be streamed between the client and the server continuously in real-time applications use cases like online multiplayer games, chat apps. This is done using websockets.
	
**WebSockets:** 

A communication protocol enabling full-duplex (both/double side) communication channels over a single TCP connection, ideal for real-time updates.

**Broadcasting in Laravel:** 

The process of transmitting server-side events to client-side applications in real-time, often using WebSocket connections. Useful for live notifications, real-time updates, chat applications, etc.

**Laravel Echo:** 

A JavaScript library providing an easy-to-use API for subscribing to channels and listening to events broadcasted by Laravel via WebSockets. Uses Pusher as `broadcaster` with other Pusher configurations taken from `.env`.

**Pusher:** 

A hosted service that simplifies the integration of real-time data and functionality into web and mobile applications through WebSocket APIs.

**Create Account in Pusher:**
- pusher.com >> SignUp for free
- Channels >> Create a new app 
	- Name your app: `laravel-advance-crud-777`
	- Select a cluster: `ap2` (since Asia Pacific)
	- Choose your tech stack (optional)
		- `Front end: React`
		- `Back end: Laravel`
		- a FE and BE samples and steps will be given.
	- App keys tab: Add these in FE, BE .env files
		`app_id = "xxxxxx"`
		`key = "xxxxxxxxxxx"`
		`secret = "xxxxxxxxxxxxxxxxxx"`
		`cluster = "ap2"`

**Server Side:**

- By default, broadcasting is not enabled in new Laravel applications. Enable broadcasting: `php artisan install:broadcasting`
	- `Would you like to install Laravel Reverb? (yes/no)` - n
	- `Would you like to install and build the Node dependencies required for broadcasting? (yes/no)` - y
	- These files will create:
		- `config/broadcasting.php` configuration file
		- `routes/channels.php` file to register application's broadcast authorization routes and callbacks

- Install the Pusher PHP SDK: `composer require pusher/pusher-php-server`
	
- Laravel app .env:
	```
	#BROADCAST_CONNECTION=log #--- use for local testing
	BROADCAST_CONNECTION=pusher
	PUSHER_APP_ID="your-pusher-app-id"
	PUSHER_APP_KEY="your-pusher-key"
	PUSHER_APP_SECRET="your-pusher-secret"
	PUSHER_HOST=
	#PUSHER_PORT=443
	#PUSHER_SCHEME="https"
	PUSHER_APP_CLUSTER="ap2"
	```
	
- (We already have `PostCreated` in our project) Create an event that you want to broadcast: `php artisan make:event PostCreated`
	- `app/Events/PostCreated.php` should implement `ShouldBroadcast`
	- Setting `data/payload` to transmit:
		- All of events public properties are automatically serialized and broadcast as the event's payload.
		- To customize use `broadcastWith()`:
			```
			public function broadcastWith(): array
			{
				return ['id' => $this->user->id];
			}
			```
	- Setting channel:
		- Public channels:
			- Channel name in `broadcastOn()` : `new Channel('PostCreated.PublicChannel'),`
		- Private channels:
			- Channel name in `broadcastOn()` : `new PrivateChannel('PostCreated.PrivateChannel.'.$this->post->id),`
	
- Broadcast the Event: `app\Http\Controllers\Api\V2\PostController.php`  >> `store()` >> `PostCreated::dispatch($post);`
- Public Channels:
	- No changes need to be added to `routes\channels.php`
- Private Channels - Authorization:
	- Here's already an example channel defined for us by default in `routes\channels.php`:
		```
		Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
			return (int) $user->id === (int) $id;
		});
		```
	
	- Users must be authorized to listen on private channels. Define channel authorization rules in `routes/channels.php`. Below is to verify that any user attempting to listen on the private PostCreated.PrivateChannel.1 channel is actually the creator of the Post.
		```
		Broadcast::channel('PostCreated.PrivateChannel.{postId}', function (User $user, int $postId) {
			return $user->id === Post::findOrNew($postId)->user_id;
		});
		```
	- Add to FE: `echo.js`:
		```
		authEndpoint: import.meta.env.VITE_PUSHER_BE_AUTH_ENDPOINT,
		auth: {
			headers: {
				Authorization: `Bearer ${localStorage.getItem('authToken')}` // or however you manage tokens
			}
		}
		```
	- Laravel echo send Auth request to Laravel app's `/broadcasting/auth route`.
		- Run `php artisan route:list` to check if it's available and if not add it to `routes\api_v2.php`:
			```
			use Illuminate\Support\Facades\Broadcast;
			
			Route::group(['middleware' => 'auth:sanctum'], function () {
				Broadcast::routes(); 
			});
			```
		If you run `php artisan route:list` again, you will see `api/v2/broadcasting/auth`
	- Errors with private channels:
		- Use Postman request for testing: http://127.0.0.1:8000/api/v2/broadcasting/auth
		- Some resources advise to add BroadcastServiceProvider to `config\app.php`. But it's not required since `BroadcastServiceProvider` is added by default in `vendor\laravel\framework\src\Illuminate\Support\DefaultProviders.php`
		- `php artisan channel:list`
			- ERROR: `Your application doesn't have any private broadcasting channels.`
				- Try:
					- `php artisan config:clear`
					- `php artisan route:clear`
					- `php artisan cache:clear`
					- `php artisan config:cache`
					- Seems a known issue: https://laracasts.com/discuss/channels/reverb/artisan-channellist-return-doesnt-have-any-private-broadcasting-channels
		- Same empty private channels issue in `vendor\laravel\framework\src\Illuminate\Broadcasting\Broadcasters\Broadcaster.php` >> `verifyUserCanAccessChannel()` >> `$this->channels`
		- Get 403 for http://127.0.0.1:8000/api/v2/broadcasting/auth in React App
			- ToDo
		

**Client Side:**
- FE in: https://github.com/NisalG/laravel-crud-api-fe
- Laravel Echo and the Pusher JS library are necessary for client-side real-time updates: `npm install --save-dev laravel-echo pusher-js`
- Client(React.js) `.env`:
	```
	VITE_PUSHER_APP_KEY="7bb8395a4f6ec286cf65"
	VITE_PUSHER_APP_CLUSTER="ap2"

	# Enable pusher logging - disable this in production
	VITE_PUSHER_LOG_TO_CONSOLE=true

	VITE_PUSHER_BE_AUTH_ENDPOINT="http://127.0.0.1:8000/api/v2/broadcasting/auth"
	```
	
- Configure Laravel Echo - create a new JavaScript file `echo.js`
	```
	import Echo from 'laravel-echo';
	import Pusher from 'pusher-js';

	window.Pusher = Pusher;

	// Enable pusher logging - disable this in production
	Pusher.logToConsole = import.meta.env.VITE_PUSHER_LOG_TO_CONSOLE;

	const echo = new Echo({
		broadcaster: 'pusher',
		key: import.meta.env.VITE_PUSHER_APP_KEY,
		cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
		// key: process.env.MIX_PUSHER_APP_KEY,
		// cluster: process.env.MIX_PUSHER_APP_CLUSTER,
		forceTLS: true,
		authEndpoint: import.meta.env.VITE_PUSHER_BE_AUTH_ENDPOINT,
		auth: {
			headers: {
				Authorization: `Bearer ${localStorage.getItem('authToken')}`, // or however you manage tokens
				Accept: 'application/json',
				// 'Content-Type': 'application/json',
			}
		}
	});

	window.Echo = echo;

	export default echo;
	```

- Listening for Event Broadcasts- `src\components\PusherNotifications\PusherNotifications.tsx`
	```
	import React, { useState, useEffect } from "react";
	import echo from "../../../echo";

	interface Message {
	  id: number;
	  title: string;
	}

	const PusherNotifications: React.FC = () => {
	  const [posts, setPosts] = useState<Message[]>([]);

	  useEffect(() => {

		// listen for events on a public channel
		echo.channel("PostCreated.PublicChannel").listen("PostCreated", (event: any) => {
		  console.log("Public event data: ", event);
		  console.log("Public event data JSON.stringify: ", JSON.stringify(event));
		  setPosts((prevPosts) => [...prevPosts, event.post]);
		});
		
		// listen for events on a private channel
		// echo
		//   .private(`PostCreatedPrivateChannel.${postId}`)
		//   .listen("PostCreated", (event: any) => {
		//     console.log("Private event data: ", event);
		//     console.log("Private event data JSON.stringify: ", JSON.stringify(event));
		//   });

		echo
		.private(`PostCreatedPrivateChannel`)
		.listen("PostCreated", (event: any) => {
		  console.log("Private event data: ", event);
		  console.log("Private event data JSON.stringify: ", JSON.stringify(event));
		});


		// Cleanup on component unmount
		return () => {
		  echo.leaveChannel("PostCreated.PublicChannel");
		  echo.leaveChannel("PostCreatedPrivateChannel");
		};
	  }, []);
	```

**Testing:** 
	
- BE: 
	- `php artisan serve`
	- `php artisan queue:work`
		- Event `PostCreated` get added to the `jobs` table
- FE:
	- `echo.js` > 
		- Enable pusher logging - don't include this in production: `Pusher.logToConsole = true;`
	- `npm run dev` | `npm run build`
- Postman: Send POST request to `Post` > `Create`
- Check Chrome > Dev tools:
	- Network Tab > WS(Websockets) tab
	- Console tab
- Public Channels You will get the: 
	- Notification on Chrome Dev Console 
	- Post Title on React FE UI
- Pusher Admin Panel:
	- Login to https://dashboard.pusher.com/channels to view info about channels, connections, messages etc.
- Private Channels:
	- React app authenticated user (`user_id` of the localStorage's `authToken`) and Create `Post's` Postman POST request's user (`user_id`) and Auth tokan should be same.
	- Get 403 for http://127.0.0.1:8000/api/v2/broadcasting/auth in React App - when dig into Laravel library the root cause is empty channels
		- ToDo
		- Resources:
			- https://www.interviewsolutionshub.com/blog/realtime-notification-with-laravel-and-reactjs#google_vignette
				- https://gist.github.com/sudkumar/c84be58dd644730fd3ce0ebae98a56db
			- https://stackoverflow.com/questions/69283215/laravel-broadcasting-private-channels-not-working
			- https://laracasts.com/discuss/channels/laravel/403-error-on-private-broadcast-channel
			- https://stackoverflow.com/questions/46225636/pusher-doesnt-broadcast-on-private-channels-php-laravel
			- https://medium.com/@mihkelallorg/laravel-react-jwt-tokens-and-laravel-echo-with-pusher-for-dummies-like-me-cafc5a09a1a1
	- `php artisan channel:list`
		- ERROR: `Your application doesn't have any private broadcasting channels.`
			- Try:
				- `php artisan config:clear`
				- `php artisan route:clear`
				- `php artisan cache:clear`
				- `php artisan config:cache`
				- Seems a known issue: https://laracasts.com/discuss/channels/reverb/artisan-channellist-return-doesnt-have-any-private-broadcasting-channels
		- Same empty private channels issue in `vendor\laravel\framework\src\Illuminate\Broadcasting\Broadcasters\Broadcaster.php` >> `verifyUserCanAccessChannel()` >> `$this->channels`
			- https://stackoverflow.com/questions/66447911/laravel-broadcasting-broadcasting-in-private-chanel-not-working-i-use-laravel-ec?rq=2
				- https://github.com/laravel/echo/issues/302
	- Channel name should be equal in these 3 places:
		- `app\Events\PostCreated.php` >> `broadcastOn()`
		- `routes\channels.php` >> `Broadcast::channel()`
		- `src\components\PusherNotifications\PusherNotifications.tsx` >> `echo.private()`

			
---


## Notifications

**Introduction**

- Laravel provides notifications for various channels (`email, SMS, Slack,` etc.)
- Notifications are short messages informing users about application events.
- You can store notifications in the database for display in your app's UI.

**Generating Notifications**
- Each notification is represented by a class in `app/Notifications`.
- `php artisan make:notification PostCommented` to create `app\Notifications\PostCommented.php` notification class.
- The notification class defines methods for different channels (e.g., `toMail`).

**Sending Notifications**
There are two ways to send notifications:
- Using `notify` method of the `Notifiable` trait on your models (e.g., `$user->notify(new PostCommented($post))`)
- Using the `Notification` facade in `app\Http\Controllers\Api\V2\PostController.php` >> `show()` action to send to multiple recipients (e.g., `Notification::send($users, new PostCommented($post))`)

**Specifying Delivery Channels**
- The `via` method on the notification class specifies delivery channels (e.g., `mail`, `slack`, `database`).
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
- This data is encoded as `JSON` and stored in the database table `notifications`.

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

- Requires `laravel/vonage-notification-channel` and `guzzlehttp/guzzle` packages. (GuzzleHTTP is a PHP library for making HTTP requests from PHP code easily. It handles different request types, response formats, and authentication.)
- Environment variables for `Vonage` keys and sender number.

**Formatting SMS Notifications**

- `toVonage` method defines the SMS message content.
- `unicode` method for unicode characters.
- `from` method to customize sender number.
- `clientReference` for cost tracking.

**Routing SMS Notifications**

- `routeNotificationForVonage` method defines routing logic.

**Slack Notifications**

- Requires `laravel/slack-notification-channel` package: `composer require laravel/slack-notification-channel`
- Configure Slack App:
	- `.env` >> `SLACK_WEBHOOK_URL=https://hooks.slack.com/services/your/webhook/url`
	- `config/services.php` >> 
	```
	'slack' => [
		'webhook_url' => env('SLACK_WEBHOOK_URL'),
	],
	
	//or
	'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
	```
- Update `app\Notifications\PostViewed.php` to use Slack notifications	

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
	Notification::send($users, new PostCommented($post));
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
