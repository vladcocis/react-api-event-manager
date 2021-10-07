<?php
/**
 * Creates an HTML webpage using the given params
 *
 * @author Vlad Cocis
 *
 */
abstract class WebPage
{
    private $main;
    private $pageStart;
    protected $header;
    private $css;
    private $footer;
    private $pageEnd;

    /**
     * Constructor
     * 
     * Contains all functions necessary to create web page
     * 
     * @param $pageTitle - A string to appear as web page title
     * @param $pageHeading1 - a string to appear as an <h1>
     * @param $footerText - footer text should include any html tags
     *
     */
    public function __construct($pageTitle, $pageHeading1, $footerText)
    {
        $this->main = "";
        $this->set_css();
        $this->set_pageStart($pageTitle, $this->css);
        $this->set_header($pageHeading1);
        $this->set_footer($footerText);
        $this->set_pageEnd();
    }

    /** 
     * Creates the path to the CSS file
     * 
     */
    private function set_css()
    {
        $this->css = BASEPATH . CSSPATH;
    }
    
    /**
     * 
     * Creates the first part of the page
     * 
     * @param $pageTitle - A string to appear as web page title
     * @param $css - path to CSS file
     * @param $footerText - footer text should include any html tags
     *
     */
    private function set_pageStart($pageTitle, $css)
    {
        $this->pageStart = <<<PAGESTART
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>$pageTitle</title>
<link rel="stylesheet" href="$css">
</head>
<body>
PAGESTART;
    }
    
    /**
     * Creates the header of the page
     * 
     * @param $pageHeading1 - a string to appear as an <h1>
     *
     */
    protected function set_header($pageHeading1)
    {
        $this->header = <<<HEADER
<header>
<h1>$pageHeading1</h1>
</header>
HEADER;
    }

    /**
     * Creates the main part of the page
     * 
     * @param $main - content which appears in main part of page
     *
     */
    private function set_main($main)
    {
        $this->main = <<<MAIN
<main>
$main
</main>
MAIN;
    }

    /**
     * Creates the last part of the page
     * 
     *
     */
    private function set_pageEnd()
    {
        $this->pageEnd = <<<PAGEEND
</body>
</html>
PAGEEND;
    }

    /**
     * Creates the footer
     * 
     * @param $footerText - content which appears in footer
     *
     */
    private function set_footer($footerText)
    {
        $this->footer = <<<FOOTER
<footer>
$footerText
</footer>
FOOTER;
    }

    /**
     * Adds content to body of page
     * 
     * @param $text - content to be added
     *
     */
    public function addToBody($text)
    {
        $this->main .= $text;
    }

    /**
     * Renders whole page
     *
     */
    public function get_page()
    {
        $this->set_main($this->main);
        return
        $this->pageStart .
        $this->header .
        $this->main .
        $this->footer .
        $this->pageEnd;
    }
}
