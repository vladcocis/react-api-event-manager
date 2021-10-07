<?php
/**
 * View, determines what users see
 *
 * @author Vlad Cocis
 *
 */
class View
{
    /**
     * Constructor
     * 
     * Renders the page with the requested JSON and HTML
     * 
     * @param recordset - JSON recordset
     */
    public function __construct($page)
    {
        $page->get_type() == "JSON"
        ? $this->JSONheaders()
        : $this->HTMLheaders();

        echo $page->get_page();
    }

    /**
     * Sets the JSON properties
     * 
     */
    private function JSONheaders()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET, POST");
    }

    /**
     * Sets the HTML properties
     * 
     */
    private function HTMLheaders()
    {
        header("Content-Type: text/html; charset=UTF-8");
    }
}
