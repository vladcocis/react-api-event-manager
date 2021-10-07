<?php
/**
 * Create a webpage with a navbar menu
 * 
 * @author Vlad Cocis
 * 
 */
class WebPageWithNav extends WebPage
{
    private $nav;
    private $navItems;

    /**
     * Creates the header of the page
     * 
     * @param $pageHeading1 - a string to appear as an <h1>
     *
     */
    protected function set_header($pageHeading1)
    {
$this->set_nav();
$nav = $this->nav;
$this->header = <<<HEADER
<header>
<h1>$pageHeading1</h1>
$nav
</header>
HEADER;
    }

    /**
     * Creates the navbar
     * 
     * @param $listItems - array containing items to be added to navbar
     *
     */
    private function navHTML($listItems)
    {
        return <<<MYNAV
<nav>
<ul>
$listItems
<ul>
</nav>
MYNAV;
    }

    /**
     * This generates the menu as an unordered list and
     * then sets the nav property
     *
     */
    private function set_nav()
    {
        $listItems = "";
        $this->set_navItems();
        foreach ($this->navItems as $key => $value) {
            $listItems .= "<li><a href='" . BASEPATH . "$value'>$key</a></li>";
        }
        $this->nav = $this->navHTML($listItems);
    }

    /**
     * Creates an associative array of menu items generated from routes.ini
     */
    private function set_navItems()
    {
        $ini['routes'] = parse_ini_file("config/routes.ini", true);
        foreach ($ini['routes'] as $key => $value) {
            if ($key != "error") {
                $this->navItems[$key] = $key . "/";
            }
        }
    }

}
