<?php

require 'functions.php';

/**
 * Handle the documentation homepage.
 *
 * This page contains the "introduction" to Laravel.
 */
Route::get('(:bundle)/(:any?)', array('before'=>'beforeDocsCacheable', 'after' => 'afterDocsCacheable', 'as' => 'doc_home', function($version="4")
{
    $home = determineHome($version);

    if(!document_exists($version.DIRECTORY_SEPARATOR . $home)) return Response::error('404');

    $document = document($version.DIRECTORY_SEPARATOR.$home);
    $title = document_title($document);

    return View::make('docs::page')
        ->with('title', $title)
        ->with('content', $document)
        ->with('isHome', true)
        ->with('version', $version)
        ->with('bc_title', 'Documentation de Laravel '.$version)
        ->with('sidebar', document($version.DIRECTORY_SEPARATOR.'contents'));
}));

/**
 * Handle documentation routes for sections and pages.
 *
 * @param  string  $version
 * @param  string  $section
 * @param  string  $page
 * @return mixed
 */
Route::get('(:bundle)/(:any)/(:any)/(:any?)', array('before'=>'beforeDocsCacheable', 'after' => 'afterDocsCacheable', function($version, $section, $page = null)
{
    $home = determineHome($version);

    $file = rtrim(implode('/', func_get_args()), '/');
    // If no page was specified, but a "home" page exists for the section,
    // we'll set the file to the home page so that the proper page is
    // displayed back out to the client for the requested doc page.
    if (is_null($page) and document_exists($file.DIRECTORY_SEPARATOR . $home))
    {
        $file .= DIRECTORY_SEPARATOR . $home;
    }

    if (document_exists($file))
    {

        $document = document($file);
        $title = document_title($document);

        return View::make('docs::page')
            ->with('title', $title)
            ->with('content', $document)
            ->with('section', $section)
            ->with('version', $version)
            ->with('bc_title', 'Documentation de Laravel '.$version)
            ->with('sidebar', document($version.'/contents'));
    }
    else
    {
        return Response::error('404');
    }
}));

Route::filter('beforeDocsCacheable', function()
{
    if ($content = Cache::get('docs_'.md5(URL::full()), false))  {
        return $content;
    }
});


Route::filter('afterDocsCacheable', function($content)
{
    if (!Cache::has('docs_'.md5(URL::full()))) {
        Cache::put('docs_'.md5(URL::full()), $content, 1440);
    }
});
