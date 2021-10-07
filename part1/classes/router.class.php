<?php
/**
 * This router will return a main, documentation or about page
 *
 * @author Vlad Cocis
 *
 */
class Router
{
    private $page;
    private $type = "HTML";

    /**
     * Constructor
     * 
     * Retrieves the requested route from the URL based on the term after the /
     * 
     * @param recordset - JSON recordset
     */
    public function __construct($recordset)
    {
        $url = $_SERVER["REQUEST_URI"];
        $path = parse_url($url)['path'];

        $path = str_replace(BASEPATH, "", $path);
        $pathArr = explode('/', $path);
        $path = (empty($pathArr[0])) ? "main" : $pathArr[0];

        ($path == "api")
        ? $this->api_route($pathArr, $recordset)
        : $this->html_route($path);

    }

    /**
     * Creates the JSON for the requested route
     *
     * @param $pathArr - an array containing the route information
     * @param $recordset - JSON recordset
     *
     */
    public function api_route($pathArr, $recordset)
    {
        $this->type = "JSON";
        $this->page = new JSONpage($pathArr, $recordset);
    }

    /**
     * Creates the HTML for the requested route
     *
     * @param $path -  route information
     *
     */
    public function html_route($path)
    {
        $ini['routes'] = parse_ini_file("config/routes.ini", true);
        $pageInfo = isset($path, $ini['routes'][$path])
        ? $ini['routes'][$path]
        : $ini['routes']['error'];

        $this->page = new WebPageWithNav($pageInfo['title'], $pageInfo['heading1'], $pageInfo['footer']);
        $this->page->addToBody($pageInfo['text']);
    }

    public function get_type()
    {
        return $this->type;
    }

    public function get_page()
    {
        return $this->page->get_page();
    }
}
