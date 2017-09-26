<?php namespace Empari\Support\Http\Menu;

class Navbar
{
    /**
     * Get Authorized Links
     *
     * @param $links
     * @return array
     */
    public function getLinksAuthorized($links)
    {
        $linksAuthorized = [];
        foreach ($links as $link) {
            if(isset($link[0])) {
                $l = $this->getLinksAuthorized($link[1]);
                if (count($l)) {
                    $linksAuthorized[] = [
                        $link[0],
                        $l
                    ];
                }
            } elseif (isset($link['permission']) && $link['permission'] === true) {
                $linksAuthorized[] = $link;
            } elseif (isset($link['permission']) && auth()->user()->can($link['permission'])) {
                $linksAuthorized[] = $link;
            }
        }
        return $linksAuthorized;
    }
}