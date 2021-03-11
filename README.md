# Vacation Portal
A simple portal that handles vacation requests.   
The backend has been written in PHP and exposes a REST API.   
The frontend that consumes the API, is a single page application (SPA) implemented with the help of VueJS javascript framework.   
Below you will find the nessasary documentation with instructions on how to run this app.

## Folder structure
    .
    ├── backend/                 # Source files of the backend
    │   ├── sql/                 # A database dump
    │   ├── .env.sample          # A sample .env file that contains the required environment variables
    │   ├── composer.json        # Project's composer file (dependencies, autoload)
    │   ├── ...
    ├── frontend/                # Source files of the frontend
    │   ├── ...         
    ├── documentation/           # Documentation related files
    │   ├── eer-diagram/         # An EER diagram of the database schema
    │   ├── phpdoc/              # Documentation files that has been generated with phpDocumentor
    │   ├── postman-collection/  # A postman collection with all the endpoints of the api and examples
    │   ├── screenshots/         # Some screenshots that demonstrating the frontend interface
    └── docker-compose.yml       # A docker-compose yaml file to run the project easily

## Installation
I provide some instructions on how to run the app down below.
You have the option to use the provided docker setup or run in manually.

### Docker
Let's start with docker, as it is the easiest option. Just run the command below or follow the detailed instructions.
Make sure though that the ports 80 (backend), 8080(frontend), 8081(phpmyadmin that used during development) are not occupied in your system as they get exposed by the docker containers.

```sh
git clone https://github.com/takisrs/vacation-portal.git && \
cd vacation-portal && \
mv backend/.env.sample backend/.env && \
docker-compose up
```
Detailed instructions:
1. Clone this repo `git clone https://github.com/takisrs/vacation-portal.git`  
2. Move to the project's folder `cd vacation-portal` 
3. Rename the provided sample .env file located in the backend folder which keeps the nessasary configuration `mv backend/.env.sample backend/.env`. It is safe to keep the defaults.  
4. Build the images and spin up the containers with `docker-compose up`
5. You can access the frontend at http://localhost:8080

### Manually

#### Backend
You could upload the files of the backend (`backend/*`) in the web server of your choice but make sure to do the steps below:   
1. Of course, you will need a mysql server and you will have to import the dump file `backend/sql/dump.sql`
2. Use the provided .env.sample file and make a .env file where you will have to adjust the configuration (about the connection to the mysql and the smtp server).   
3. Install the required dependencies with `composer install`

#### Frontend
1. Install the required dependecies with `npm install`
2. Serve the app in dev mode with `npm run serve` or build it with `npm run build` and move the `frontend/dist` in the webserver of your choice

### Demo Accounts
The db dump includes the following demo accounts that you may use:  
**User**    
demo@vacationapp.gr   
user123#   

**Admin**   
admin@vacationapp.gr   
admin123#   

## Backend
**Some key notes about the backend:**
* Requires PHP 7.4 with the pdo_mysql extension
* Follows the MVC architecture
* Exposes a REST API
* The authentication / authorization is performed with JWT access tokens
* Each class / method is documented with DocBlocks
* You may find the defined routes at `backend/src/VacationApp.php`, so you could follow the flow of the application starting from there.

### Rest API
The rest api exposes the endpoints below. Any required payload is given as a json in the body of the request.   
Each endpoint, except the login one, requires an authorization bearer token in the headers.   
`POST /auth/login` **public** Returns a token to the successfully authenticated users   
`GET /users` **admin access** Returns a list of all users   
`POST /users` **admin access** Creates a new user   
`GET /users/:id` **admin access** Return user's details   
`POST /users/:id` **admin access** Updates a user   
`GET /applications` **user acess** Returns a list of applications of the authorized user   
`POST /applications` **user access** Creates a new application   
`POST /applications/:id/approve` **admin access** Approves an application   
`POST /applications/:id/reject` **admin access** Rejects an application    

You may also find a postman collection at `/documentation/postman-collection/collection.json` in case you want to check the above endpoints.

### Source code documentation

- [\takisrs\VacationApp](#class-takisrsvacationapp)
- [\takisrs\Controllers\ApplicationController](#class-takisrscontrollersapplicationcontroller)
- [\takisrs\Controllers\UserController](#class-takisrscontrollersusercontroller)
- [\takisrs\Controllers\AuthController](#class-takisrscontrollersauthcontroller)
- [\takisrs\Core\Router](#class-takisrscorerouter)
- [\takisrs\Core\MySQLConnection](#class-takisrscoremysqlconnection)
- [\takisrs\Core\Controller](#class-takisrscorecontroller)
- [\takisrs\Core\Response](#class-takisrscoreresponse)
- [\takisrs\Core\Model](#class-takisrscoremodel)
- [\takisrs\Core\HttpException](#class-takisrscorehttpexception)
- [\takisrs\Core\Authenticator](#class-takisrscoreauthenticator)
- [\takisrs\Core\Request](#class-takisrscorerequest)
- [\takisrs\Helpers\EmailTemplate](#class-takisrshelpersemailtemplate)
- [\takisrs\Models\Application](#class-takisrsmodelsapplication)
- [\takisrs\Models\User](#class-takisrsmodelsuser)

<hr />

### Class: \takisrs\VacationApp

> App's main class Initialize the Request, Response, Router objects. Contains the defined routes. Provides a catch block for the whole application execution.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct()</strong> : <em>void</em> |
| public | <strong>init()</strong> : <em>void</em><br /><em>App's main method. It all starts here. It wraps the whole execution in a try/catch block to catch any unhandled exeption and return a meaningful json response to the client.</em> |
| public | <strong>registerRoutes()</strong> : <em>void</em><br /><em>Declares the routes that the app uses.</em> |

<hr />

### Class: \takisrs\Controllers\ApplicationController

> Application Controller Methods related to applications' handling (list, create, approve, reject).

| Visibility | Function |
|:-----------|:---------|
| public | <strong>approve()</strong> : <em>void</em><br /><em>Approves an application The method changes the status of an application to "approved", and informs the user with an email.</em> |
| public | <strong>create()</strong> : <em>void</em><br /><em>Creates a new application and returns the result of the operation</em> |
| public | <strong>index()</strong> : <em>void</em><br /><em>Retrieves and returns the list of applications of the authorized user</em> |
| public | <strong>reject()</strong> : <em>void</em><br /><em>Rejects an application The method changes the status of an application to "rejected", and inform the user with an email.</em> |

*This class extends [\takisrs\Core\Controller](#class-takisrscorecontroller)*

<hr />

### Class: \takisrs\Controllers\UserController

> User Controller Handles any request about users (list, view, create, update).

| Visibility | Function |
|:-----------|:---------|
| public | <strong>create()</strong> : <em>void</em><br /><em>Creates a new user</em> |
| public | <strong>index()</strong> : <em>void</em><br /><em>Retrieves and returns the list of all users</em> |
| public | <strong>single()</strong> : <em>void</em><br /><em>Retrieves and returns the fields of the requested user</em> |
| public | <strong>update()</strong> : <em>void</em><br /><em>Updates an user</em> |

*This class extends [\takisrs\Core\Controller](#class-takisrscorecontroller)*

<hr />

### Class: \takisrs\Controllers\AuthController

> Auth Controller Handles the requests regarding authentication, such as login, signup. Currently, only the login method has been implemented.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>login()</strong> : <em>void</em><br /><em>Login</em> |

*This class extends [\takisrs\Core\Controller](#class-takisrscorecontroller)*

<hr />

### Class: \takisrs\Core\Router

> Router class

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\takisrs\Core\Request](#class-takisrscorerequest)</em> <strong>$request</strong>, <em>[\takisrs\Core\Response](#class-takisrscoreresponse)</em> <strong>$response</strong>)</strong> : <em>void</em><br /><em>Router constructor</em> |
| public | <strong>get(</strong><em>\string</em> <strong>$pattern</strong>, <em>\string</em> <strong>$controller</strong>, <em>\string</em> <strong>$method</strong>, <em>integer/\int</em> <strong>$access</strong>)</strong> : <em>void</em><br /><em>Registers a get route</em> |
| public | <strong>matchAndExtractParams(</strong><em>\string</em> <strong>$uri</strong>, <em>\string</em> <strong>$pattern</strong>)</strong> : <em>bool if uri matches the pattern or not</em><br /><em>Checks if uri matches the pattern and extracts the params</em> |
| public | <strong>post(</strong><em>\string</em> <strong>$pattern</strong>, <em>\string</em> <strong>$controller</strong>, <em>\string</em> <strong>$method</strong>, <em>integer/\int</em> <strong>$access</strong>)</strong> : <em>void</em><br /><em>Registers a post route</em> |
| public | <strong>run()</strong> : <em>void</em><br /><em>Matches the request with the corresponding controller/method and processes it</em> |

<hr />

### Class: \takisrs\Core\MySQLConnection

> A class that handles the mysql connection Follows the singleton pattern.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct()</strong> : <em>void</em><br /><em>Constructor</em> |
| public | <strong>getConnection()</strong> : <em>[\PDO](http://php.net/manual/en/class.pdo.php)</em><br /><em>Returns the PDO connection</em> |
| public static | <strong>getInstance()</strong> : <em>[\takisrs\Core\MySQLConnection](#class-takisrscoremysqlconnection)</em><br /><em>Returns the instance of the MySQLConnection</em> |

<hr />

### Class: \takisrs\Core\Controller

> Base Controller class Each controller should extend this class.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>[\takisrs\Core\Request](#class-takisrscorerequest)</em> <strong>$request</strong>, <em>[\takisrs\Core\Response](#class-takisrscoreresponse)</em> <strong>$response</strong>)</strong> : <em>void</em><br /><em>Base controller constructor</em> |

<hr />

### Class: \takisrs\Core\Response

> Response class A simple class that sends the api response

| Visibility | Function |
|:-----------|:---------|
| public | <strong>send(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>void</em><br /><em>Sends a response to the client</em> |
| public | <strong>status(</strong><em>int</em> <strong>$code</strong>)</strong> : <em>[\takisrs\Core\Response](#class-takisrscoreresponse)</em><br /><em>Sets the response status code</em> |

<hr />

### Class: \takisrs\Core\Model

> Base Controller class Any model extends this class. Provides some methods that simplify SELECT, CREATE and UPDATE sql queries.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct()</strong> : <em>void</em> |
| public | <strong>create()</strong> : <em>object/null</em><br /><em>Performs an insert query</em> |
| public | <strong>find(</strong><em>integer/\int</em> <strong>$id</strong>)</strong> : <em>object/null</em><br /><em>Accepts an id and retrieves the corresponding record from the database</em> |
| public | <strong>findAll(</strong><em>\string</em> <strong>$sort=null</strong>)</strong> : <em>array</em><br /><em>Retrieves all the records of the corresponding table from the database</em> |
| public | <strong>findBy(</strong><em>array</em> <strong>$params</strong>, <em>\string</em> <strong>$sort=null</strong>)</strong> : <em>array/null array of objects</em><br /><em>Retrieves a list of records of the database and return an object for each record</em> |
| public | <strong>findOneBy(</strong><em>array</em> <strong>$params</strong>)</strong> : <em>object/null</em><br /><em>Retrieves a record from the database</em> |
| public | <strong>update()</strong> : <em>bool</em><br /><em>Performs an update query</em> |
| protected | <strong>buildWhere(</strong><em>array</em> <strong>$params</strong>)</strong> : <em>array sql where clause and bind params</em><br /><em>Gets and array of field-value pairs and returns an sql where clause</em> |
| protected | <strong>getBindParams()</strong> : <em>array</em><br /><em>Returns an array with the pdo bindings</em> |
| protected | <strong>mapResultToObject(</strong><em>array</em> <strong>$record</strong>, <em>\object</em> <strong>$object=null</strong>)</strong> : <em>object</em><br /><em>Maps the result of a select query to the corresponding model</em> |

<hr />

### Class: \takisrs\Core\HttpException

> A simple HttpException class Throw this exception when you want to provide a meaningfull API response to the user with the appropiate status code. Any other exception type returns a 500 status code in the response.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\int</em> <strong>$code=500</strong>, <em>\string</em> <strong>$message=`''`</strong>, <em>\Throwable</em> <strong>$previous=null</strong>)</strong> : <em>void</em><br /><em>HttpException constructor</em> |

*This class extends \Exception*

*This class implements \Throwable*

<hr />

### Class: \takisrs\Core\Authenticator

> A class that handles the tasks related to JWT authorization

| Visibility | Function |
|:-----------|:---------|
| public static | <strong>decodeToken(</strong><em>\string</em> <strong>$jwt</strong>)</strong> : <em>array The decoded data</em><br /><em>Receives a token, decodes it and returns the data</em> |
| public static | <strong>getToken(</strong><em>array</em> <strong>$payload</strong>)</strong> : <em>string a JWT token</em><br /><em>Encodes the given payload and returns a JWT token</em> |

<hr />

### Class: \takisrs\Core\Request

> Request class Helps with the request manipullation.

| Visibility | Function |
|:-----------|:---------|
| public | <strong>authorizationToken()</strong> : <em>string/null</em><br /><em>Retrieves the authorization token of the request</em> |
| public | <strong>body(</strong><em>\string</em> <strong>$key=null</strong>)</strong> : <em>mixed</em><br /><em>Retrieves a parameter from the json request body</em> |
| public | <strong>get(</strong><em>\string</em> <strong>$key=null</strong>)</strong> : <em>mixed</em><br /><em>Retrieves a $_GET parameter</em> |
| public | <strong>getMethod()</strong> : <em>string the request method</em><br /><em>Returns the request method (POST, GET)</em> |
| public | <strong>getUri()</strong> : <em>string the uri</em><br /><em>Returns the request uri</em> |
| public | <strong>header(</strong><em>\string</em> <strong>$key=null</strong>)</strong> : <em>mixed header value</em><br /><em>Retrieves a header from the request</em> |
| public | <strong>param(</strong><em>\string</em> <strong>$key=null</strong>)</strong> : <em>mixed</em><br /><em>Retrieves a param from the request according to the provided key</em> |
| public | <strong>post(</strong><em>\string</em> <strong>$key=null</strong>)</strong> : <em>mixed</em><br /><em>Retrieves a $_POST parameter</em> |
| public | <strong>setParam(</strong><em>\string</em> <strong>$key</strong>, <em>string/int/\string</em> <strong>$value</strong>)</strong> : <em>[\takisrs\Core\Request](#class-takisrscorerequest)</em><br /><em>Sets a route param</em> |
| public | <strong>setUser(</strong><em>[\takisrs\Models\User](#class-takisrsmodelsuser)</em> <strong>$user</strong>)</strong> : <em>[\takisrs\Core\Request](#class-takisrscorerequest)</em><br /><em>Sets the authentication user</em> |
| public | <strong>user()</strong> : <em>[\takisrs\Models\User](#class-takisrsmodelsuser)</em><br /><em>Returns the authorized user of the request</em> |
| public | <strong>validate(</strong><em>array</em> <strong>$rulesArr</strong>)</strong> : <em>bool/null</em><br /><em>A simple validation method for all request params (get, post, body)</em> |

<hr />

### Class: \takisrs\Helpers\EmailTemplate

> Handles email templating and sending

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>\takisrs\Helpers\sting/\string</em> <strong>$templatePath</strong>)</strong> : <em>void</em><br /><em>Constructor</em> |
| public | <strong>getBody()</strong> : <em>string</em><br /><em>Returns email's body</em> |
| public | <strong>getSubject()</strong> : <em>string</em><br /><em>Returns email's subject</em> |
| public | <strong>replaceVar(</strong><em>string</em> <strong>$key</strong>, <em>string/int</em> <strong>$value</strong>)</strong> : <em>\takisrs\Helpers\self</em><br /><em>Replaces a variable in the html content with the provided value</em> |
| public | <strong>replaceVars(</strong><em>array</em> <strong>$data</strong>)</strong> : <em>\takisrs\Helpers\self</em><br /><em>Replaces all the variables in the html content of the email template</em> |
| public | <strong>send(</strong><em>\string</em> <strong>$email</strong>)</strong> : <em>void</em><br /><em>Sends the email</em> |

<hr />

### Class: \takisrs\Models\Application

> Application Model class

| Visibility | Function |
|:-----------|:---------|
| public | <strong>approve()</strong> : <em>boolean</em><br /><em>Approves the application (Updates status to "approved")</em> |
| public | <strong>days()</strong> : <em>integer</em><br /><em>Returns the duration of the vacation in days</em> |
| public | <strong>isApproved()</strong> : <em>boolean</em><br /><em>Returns true if the application has been approved</em> |
| public | <strong>isRejected()</strong> : <em>boolean</em><br /><em>Returns true if the application has been rejected</em> |
| public | <strong>reject()</strong> : <em>boolean</em><br /><em>Rejects the application (Updates status to "rejected")</em> |
| public | <strong>user()</strong> : <em>[\takisrs\Models\User](#class-takisrsmodelsuser)</em><br /><em>Returns application's user object</em> |

*This class extends [\takisrs\Core\Model](#class-takisrscoremodel)*

<hr />

### Class: \takisrs\Models\User

> User Model class

| Visibility | Function |
|:-----------|:---------|
| public | <strong>applications()</strong> : <em>[\takisrs\Models\Application](#class-takisrsmodelsapplication)[]/null</em><br /><em>Returns an array of user's applications</em> |
| public | <strong>isAdmin()</strong> : <em>boolean</em><br /><em>Returns true if the user is an admin</em> |
| public | <strong>isUser()</strong> : <em>boolean</em><br /><em>Returns true if the user is a simple user</em> |

*This class extends [\takisrs\Core\Model](#class-takisrscoremodel)*

## Frontend
**Some key notes about the frontend:**
* Requires nodejs 14 if you intend to set it up locally in a dev environment
* It is a SPA that has been built with the VueJS framework
* Communicates with the backend through the REST api
* Keeps a state about the logged in users and sends a bearer authorization token in the headers on each request 
