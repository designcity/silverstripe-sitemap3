<?php

/**
 *	Generates a sitemap for a silverstripe site.
 *
 *	Display filtering is dependant on the canSiteMap
 *	of the page or through the updateCanSiteMap when decorating
 *
 *	@author Ryan Cotter
 *	@author Michael Bollig
 *
 *	@package sitemap3
 */
class SiteMapPage extends Page
{

    private static $icon = "sitemap3/images/treeicons/sitemap-file.png";

    /**
     *	Retrieves a map of a page and it's direct descendants
     *
     *	@params int $pid Parent ID of the page(s) to start recursively displaying
     *	@return ArrayList
     */
    public static function getSiteMap($pid=0)
    {
        $tmp = new ArrayList();
        $dl = SiteTree::get()->filter(array(
            "ParentID" => $pid,
        ));
        foreach ($dl as $d) {
            //Should we display this? If not, don't forget it's children
            if (!$d->canSiteMap()) {
                $children = self::getSiteMap($d->ID);
                if ($children) {
                    foreach ($children as $c) {
                        $tmp->add(
                            ArrayData::create(array(
                                'ParentMap' => $c->ParentMap,
                                'ChildrenMap' => $c->ChildrenMap,
                        )));
                    }
                }
            } else {
                $tmp->add(
                    ArrayData::create(array(
                        'ParentMap' => $d,
                        'ChildrenMap' =>  ($ar = self::getSiteMap($d->ID)) ? $ar : null
                )));
            }
        }
        return $tmp;
    }

    /**
     *	Parses a {@link SiteMapPage}'s shortcode
     *
     *	Sitemap css and javascript can be selected using
     *	the SiteMapPage:Themes option in the config.yml.
     *
     *	Reads from {$themename}SiteMap.ss template, falling back to
     *	default SiteMap.ss
     *
     *	@param $arguments array Arguments to the shortcode
     *  @param $content string Content of the returned link (optional)
     *  @param $parser object Specify a parser to parse the content (see {@link ShortCodeParser})
     *  @return string SiteMap template render
     */
    public static function SiteMapShortCodeHandler($arguments, $caption=null, $parser=null)
    {
        // Are we on a SiteMap page? If not, return.
        if (Director::is_cli() || Controller::curr()->ClassName != "SiteMapPage") {
            return;
        }

        $theme = Config::inst()->get('SiteMapPage', 'theme');
        Requirements::css(SITEMAP3_DIR."/themes/".$theme."/css/" . $theme . ".css");
        Requirements::javascript(SITEMAP3_DIR."/themes/".$theme."/javascript/" . $theme . ".js");
        $data = self::getSiteMap(0);
        //Slickmap column generation etc
        $TotalColumns = count($data) - 1;
        //if (($TotalColumns - 1) < 11 ) { /* remove home page from count */
        //	$TotalColumns = $totalItems - 1;
        //} else { $slickmapWidth = "col10"; }
        $template = new SSViewer(array($theme.'SiteMap', 'SiteMap'));
        return $template->process(new ArrayData(array(
            'CurrentPage' => Controller::curr(),
            'SiteMapTree' => $data,
            'TotalColumns' => $TotalColumns,
        )));
    }

    /**
     *  Creates a SiteMapPage at the top level when the database is built.
     *  Config MUST have 'autobuildpage' set to true, and site MUST NOT be in live mode.
     */
    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords();

        $smp = DataObject::get_one('SiteMapPage');
        $autobuild = Config::inst()->get('SiteMapPage', 'autobuildpage');
        //TODO: This does not check for whether this SiteMapPage is an orphan or not
        if (!$smp && !Director::isLive() && $autobuild === true) {
            $smp = new SiteMapPage();
            $smp->Title = _t('SiteMapPage.DEFAULTTITLE', 'Site Map');
            $smp->Content = "<div>[SiteMap]</div><p>&nbsp;</p>";
            $smp->URLSegment = singleton('SiteTree')->generateURLSegment(_t('SiteMapPage.DEFAULTTITLE', 'Site Map'));
            $smp->Status = "Published";
            $smp->write();
            $smp->publish("Stage", "Live");

            DB::alteration_message("Default site map page created ;)", "created");
        }
    }

    /**
     *  Adds default page title as set in Lang to the page when created using the CMS.
     *  Ensures our sitemap is not rendered inside <p> tags, using <div> tags instead
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        if (!$this->ID) {
            $this->Title = _t('SiteMapPage.DEFAULTTITLE', 'Site Map');
            $this->MenuTitle = _t('SiteMapPage.DEFAULTTITLE', 'Site Map');
            $this->Content = "<div>[SiteMap]</div><p>&nbsp;</p>";
        } else {
            $content = $this->Content;
            if (preg_match('/(<p(.*)>\[SiteMap\])/', $content) !== false) {
                $pattern = '/<p(.*)>(\SiteMap(\]))<\/p>/';
                $replacement = '<div>$2</div>';

                $new = preg_replace($pattern, $replacement, $content);
                $this->Content = $new;
            }
        }
    }
}
