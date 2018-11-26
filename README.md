# installation

You need to have [composer](https://getcomposer.org/) installed.

run `composer install`

## database setup

create a database for this project (for more safety, also create a user for this database).

change your `.env` file `DATABASE_URL` var to the url to connect to the database.

generate the database with  
`php bin/console doctrine:schema:update --force`
   
## admin user
create a soon to-be admin user with `php bin/console fos:user:create`, 
(you will be prompted for his informations), and effectively give him the admin role by running
`php bin/console fos:user:promote` and choosing `ROLE_ADMIN` when asked for the role.

## launching the server
The last step is now to run the server by running
`php bin/console server:run`

and you may then visit [localhost:8000](http://127.0.0.1:8000) to see your website up and running