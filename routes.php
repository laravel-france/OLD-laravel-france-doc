<?php

require 'functions.php';

Route::any('(:bundle)/(:any?)', function($version = 'v3'){
    return View::make('guides::guide.home'.$version);
});


// route for documentation
require 'routes_docs.php';
