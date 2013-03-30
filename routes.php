<?php

require 'functions.php';

Route::any('(:bundle)/(:any?)', array('as' =>  'docs_home', function($version = 'v3'){
    return View::make('docs::guide.home'.$version, compact("version"));
}));


// route for documentation
require 'routes_docs.php';
