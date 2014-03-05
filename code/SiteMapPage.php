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
class SiteMapPage extends Page {

	private static $defaults = array(
		'Content' => '<div>[SiteMap]</div>',
	);

	private static $icon = "sitemap3/images/treeicons/sitemap-file.png";

	/**
	 *	Retrieves a map of a page and it's direct descendants
	 *
	 *	@params int $pid Parent ID of the page(s) to start recursively displaying
	 *	@return ArrayList
	 */
	public static function getSiteMap($pid=0) {
		$tmp = new ArrayList();
		$dl = SiteTree::get()->filter(array(
			"ParentID" => $pid,
		));
		foreach($dl as $d){
			//Should we display this? If not, don't forget it's children
			if(!$d->canSiteMap()){
				$children = self::getSiteMap($d->ID);
				if($children){
					foreach($children as $c){
						$tmp->add(
							ArrayData::create(array(
								'ParentMap' => $c->ParentMap,
								'ChildrenMap' => $c->ChildrenMap,
						)));
					}
				}
			}
			else {
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
	 *	This will select the first theme in the list, if multiple are provided
	 *
	 *	@param $arguments array Arguments to the shortcode
	 *  @param $content string Content of the returned link (optional)
	 *  @param $parser object Specify a parser to parse the content (see {@link ShortCodeParser})
	 *  @return string SiteMap template render
	 */
	public static function SiteMapShortCodeHandler($arguments, $caption=null, $parser=null) {
		// Are we on a SiteMap page? If not, return.
		if(Controller::curr()->ClassName != "SiteMapPage") {return;}

		$theme = Config::inst()->get('SiteMapPage','theme');
		Requirements::css(SITEMAP3_DIR."/themes/".$theme[0]."/css/" . $theme[0] . ".css");
		Requirements::javascript(SITEMAP3_DIR."/themes/".$theme[0]."/javascript/" . $theme[0] . ".js");
		$data = self::getSiteMap(0);
		//Slickmap column generation etc
		$totalItems = count($data);
		if (($totalItems - 1) < 11 ) { /* remove home page from count */
			$slickmapWidth = "col".($totalItems - 1);
		} else { $slickmapWidth = "col10"; }
		$template = new SSViewer('SiteMap');
		return $template->process(new ArrayData(array(
			'SiteMapTree' => $data,
			'SlickmapWidth' => $slickmapWidth,
		)));
	}

	/**
	 *	Ensures our sitemap is not rendered inside
	 *	<p> tags, using <div> tags instead
	 */
	public function onBeforeWrite()
	{
		parent::onBeforeWrite();
		$content = $this->Content;

		if(preg_match('/(<p(.*)>\[SiteMap\])/', $content) !== false){
			$pattern = '/<p(.*)>(\SiteMap(\]))<\/p>/';
			$replacement = '<div>$2</div>';

			$new = preg_replace($pattern, $replacement, $content);
			$this->Content = $new;
		}
	}
}
