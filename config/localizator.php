<?php

return [

    /**
     * Localize types of translation strings.
     */
    'localize' => [
        /**
         * Short keys. This is the default for Laravel.
         * They are stored in PHP files inside folders name by their locale code.
         * Laravel installation comes with default: auth.php, pagination.php, passwords.php and validation.php
         */
        'default' => false,
        /**
         * Translations strings as key.
         * They are stored in JSON file for each locale.
         */
        'json' => true,
    ],

    /**
     * Search criteria for files.
     */
    'search' => [
        /**
         * Directories which should be looked inside.
         */
        'dirs' => ['database/seeders', 'resources/views', 'app'],

        /**
         * Subdirectories which will be excluded.
         * The values must be relative to the included directory paths.
         */
        'exclude' => [
            //
        ],

        /**
         * Patterns by which files should be queried.
         * The values can be a regular expresion, glob, or just a string.
         */
        'patterns' => ['*.php'],

        /**
         * Functions that the strings will be extracted from.
         * Add here any custom defined functions.
         * NOTE: The translation string should always be the first argument.
         */
        'functions' => ['__', 'trans', '@lang'],
    ],

    /**
     * Should the localize command sort extracted strings alphabetically?
     */
    'sort' => true,

];
