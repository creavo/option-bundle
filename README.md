# CREAVO Option-Bundle

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e9e9b2a1-b0ad-4919-9a98-486f1d1f471e/mini.png)](https://insight.sensiolabs.com/projects/e9e9b2a1-b0ad-4919-9a98-486f1d1f471e)

## Installation

please use composer with

    composer require creavo/option-bundle
    
Add the bundle to your `app/AppKernel.php` with 

    new Creavo\OptionBundle\CreavoOptionBundle(),

Update the doctrine-schema - use 

    php bin/console doctrine:schema:update

or do a migration:

    php bin/console doctrine:migration:diff
    php bin/console doctrine:migration:migrate
    
## Configuration

add the following lines to your `app/config/config.yml`:

    creavo_option:
        fetch_all: false
        simple_cache_service: null
        
* fetch_all: true/false - when true, all settings are fetched from the database on initializing the bundle - depending on your use-case it might be more efficient to fetch all settings in a single query instead of fetching them later, when used (which leads to more queries)
* simple_cache_service: inject a simple-cache here (something that implements `Psr\SimpleCache\CacheInterface`) - when null an ArrayCache for the request is used

## Usage

### as service

set a setting

    $container->get('crv.option')->set('option-name', $optionValue, $optionType, $section);
    
get a setting (cached):

    $optionValue=$container->get('crv.option')->get('option-name');
    
get a setting (uncached - will fetch value freshly from database without the cache)

    $optionValue=$container->get('crv.option')->getUnCached('option-name'); 

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
    
