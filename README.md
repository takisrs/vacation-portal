# Vacation Portal
A simple portal that handles vacation requests.   
The backend exposes a REST api, that the frontend consumes.   
Below you will find the nessasary documentation with instructions on how to run this app.

## Folder structure
    .
    ├── backend                 # Source files of the backend
    │   ├── eer-diagram         # An EER diagram of the database schema
    |   ├── sql                 # A Database dump
    |   ├── .env.sample         # An sample .env file for the required environment variables
    |   ├── ...
    ├── frontend                # Source files of the frontend
    │   ├── ...                 
    └── docker-compose.yml      # A docker-compose yaml file to run the project easily

## Backend
Some key notes about the backend:
* Requires PHP 7.4 with the pdo_mysql extension
* Follows the MVC architecture
* Exposes a REST API
* You may find the defined routes at `backend/src/VacationApp.php`
* The authentication / authorization is performed with JWT access tokens
* Each class / method is documented with doc blocks

## Frontend
Some key notes about the frontend:
* Requires nodejs 14 if you intend to set it up locally in a dev environment
* It has been built with the VueJS framework
* Communicates with the backend through the REST api
* Keeps a state about the logged in users and sends a bearer authorization token in the headers on each request 

## Installation
I provide below some instructions on how to run the app.  
You have the option to use the provided docker setup or run in manually.

### Docker
Let's start with docker, as it is the easiest option. Just run the command below or follow the detailed instructions:
```sh
git clone https://github.com/takisrs/vacation-portal.git && cd vacation-portal && mv backend/.env.sample backend/.env && docker-compose up
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
2. Serve the app with `npm run serve` or build it with `npm run build` and move the `frontend/dist` in the webserver of your choice