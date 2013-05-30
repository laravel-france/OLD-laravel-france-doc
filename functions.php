<?php

function determineHome($version)
{
    if ($version == 3) {
        return 'home';
    } elseif ($version == 4) {
        return 'introduction';
    }
}

/**
 * Get the root path for the documentation Markdown.
 *
 * @return string
 */
function doc_root()
{
    return Bundle::path('docs').'documents/';
}

/**
 * Get the parsed Markdown contents of a given page.
 *
 * @param  string  $page
 * @return string
 */
function document($page)
{
    return MdParser\Markdown(file_get_contents(doc_root().$page.'.md'));
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
 * Get the title of the document
 *
 * @param string $document
 * @return string
 */
function document_title($document)
{
    $result = array();
    $string = " ".$document;
    $offset = 0;
    while(true)
    {
        $ini = strpos($string,'<h1>',$offset);
        if ($ini == 0)
            break;
        $ini += strlen('<h1>');
        $len = strpos($string,'</h1>',$ini) - $ini;
        $result[] = substr($string,$ini,$len);
        $offset = $ini+$len;
    }
    return (isset($result[0])) ? $result[0] : null;

}
