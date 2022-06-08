## Justin Mathew - Tech Test

### Bootstrapping the application

1. Clone the repo
~~~
git clone https://github.com/justinmathew89/justin_catch_tech_test.git
~~~

2. Run
~~~
composer install
~~~

3. cd to justin_catch_tech_test
4. Run the symphony command
~~~
php bin/console app:process-order
~~~


### Additional Dependencies Used

1. stolt/json-lines

    This package has a handy method *delineEachLineFromFile*, which allows 
to iterate over a large file without storing the entire delined file in memory.
