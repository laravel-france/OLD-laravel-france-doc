<?php

/**
 * Load the Markdown library.
 */
require_once __DIR__.'/libraries/markdown.php';

/**
 * Get the root path for the documentation Markdown.
 *
 * @return string
 */
function doc_root()
{
	return Bundle::path('guides').'documents/';
}

/**
 * Get the parsed Markdown contents of a given page.
 *
 * @param  string  $page
 * @return string
 */
function document($page)
{
	return Markdown(file_get_contents(doc_root().$page.'.md'));
}

/**
 * Determine if a documentation page exists.
 *
 * @param  string  $page
 * @return bool
 */
function document_exists($page)
{
	return file_exists(doc_root().$page.'.md');
}


/**
 * Handle the documentation homepage.
 *
 * This page contains the "introduction" to Laravel.
 */
Route::get('(:bundle)/(:any?)', function($version="v3")
{
	return View::make('guides::page')->with('content', document($version.DIRECTORY_SEPARATOR.'home'))->with('sidebar', document($version.DIRECTORY_SEPARATOR.'contents'));
});

/**
 * Handle documentation routes for sections and pages.
 *
 * @param  string  $version
 * @param  string  $section
 * @param  string  $page
 * @return mixed
 */
Route::get('(:bundle)/(:any)/(:any)/(:any?)', function($version, $section, $page = null)
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
		return View::make('guides::page')->with('content', document($file))->with('sidebar', document($version.'/contents'));
	}
	else
	{
		return Response::error('404');
	}
});