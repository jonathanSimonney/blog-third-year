# installation

You need to have [composer](https://getcomposer.org/) installed.

run `composer install`

## database setup

create a database for this project (for more safety, also create a user for this database).

change your `.env` file `DATABASE_URL` var to the url to connect to the database.

generate the database with  
`php bin/console doctrine:schema:update --force`

and load some dummy dta in it with `php bin/console doctrine:fixtures:load`
   
## admin user
The automatically generated admin user has the email `admin@gmail.com` and
the password `password`

## assets setup
Since this project uses encore, don't forget to run `yarn encore dev` (if you're in a development
environment) or `yarn encore production` (if you're in a production environment)

## launching the server
The last step is now to run the server by running
`php bin/console server:run`

and you may then visit [localhost:8000](http://127.0.0.1:8000) to see your website up and running