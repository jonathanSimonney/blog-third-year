# installation

You need to have [composer](https://getcomposer.org/) installed.

run `composer install`

## database setup

create a database for this project (for more safety, also create a user for this database).

change your `.env` file `DATABASE_URL` var to the url to connect to the database.

generate the database with  
`php bin/console doctrine:schema:update --force`

and load some dummy data in it with `php bin/console doctrine:fixtures:load`
   
## admin user
The automatically generated admin user has the email `admin@gmail.com` and
the password `password`

## other default user
An author is also automatically generated, with some articles written, and some commentary
written on his article. 

there is : 

- an author whose email is : `author@gmail.com`
- two simple users (who commented or not), whose emails are : `commenter@gmail.com`
and `stalker@gmail.com`


All users have the same password : `password`
## assets setup
Since this project uses encore, don't forget to run `yarn encore dev` (if you're in a development
environment) or `yarn encore production` (if you're in a production environment)

## launching the server
The last step is now to run the server by running
`php bin/console server:run`

and you may then visit [localhost:8000](http://127.0.0.1:8000) to see your website up and running