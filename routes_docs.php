<?php


/**
 * Handle the documentation homepage.
 *
 * This page contains the "introduction" to Laravel.
 */
Route::get('(:bundle)/(:any?)/doc', array('as' => 'doc_home', function($version="v3")
{
    if(!document_exists($version.DIRECTORY_SEPARATOR.'home')) return Response::error('404');


    $document = document($version.DIRECTORY_SEPARATOR.'home');
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
Route::get('(:bundle)/(:any)/doc/(:any)/(:any?)', function($version, $section, $page = null)
{
    $file = rtrim(implode('/', func_get_args()), '/');

    // If no page was specified, but a "home" page exists for the section,
    // we'll set the file to the home page so that the proper page is
    // displayed back out to the client for the requested doc page.
    if (is_null($page) and document_exists($file.DIRECTORY_SEPARATOR.'home'))
    {
        $file .= DIRECTORY_SEPARATOR.'home';
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
});