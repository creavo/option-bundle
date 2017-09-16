# CREAVO Option-Bundle

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e9e9b2a1-b0ad-4919-9a98-486f1d1f471e/mini.png)](https://insight.sensiolabs.com/projects/e9e9b2a1-b0ad-4919-9a98-486f1d1f471e)

### Installation

please use composer with

    composer require creavo/option-bundle
    
Add the bundle to your `app/AppKernel.php` with 

    new Creavo\OptionBundle\CreavoOptionBundle(),

Update the doctrine-schema - use 

    php bin/console doctrine:schema:update

or do a migration:

    php bin/console doctrine:migration:diff
    php bin/console doctrine:migration:migrate
    
### configuration



### use console-commands
set a setting with `php bin/console crv:ob:set name value [type] [section]` type and section are optional - if you omit them, the type will be a string and section `null`.

    $ php bin/console crv:ob:set test1 "2017-09-16 12:00:00" dateTime parameters

to get a setting use get:

    $ php bin/console crv:ob:get test1
    +-----------+---------------------+
    | Element   | Value               |
    +-----------+---------------------+
    | name      | test1               |
    | type      | dateTime            |
    | section   | parameters          |
    | updatedAt | 2017-09-16 12:30:17 |
    | value     | 2017-09-16 12:00:00 |
    +-----------+---------------------+
    
