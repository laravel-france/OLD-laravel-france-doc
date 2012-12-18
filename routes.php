<?php

require 'functions.php';

Route::any('(:bundle)', function(){
    return View::make('guides::guide.home');
});


// route for documentation
require 'routes_docs.php';
