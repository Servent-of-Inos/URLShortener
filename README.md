# URLShortener
Installation:
1. git clone https://github.com/Servent-of-Inos/URLShortener.git
2. In the .env file set your db connection information 
(DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name) user, password, ip respectively.
3. php bin/console doctrine:migrations:migrate
4. composer install
5. yarn install
6. yarn run encore dev
7. run your server
