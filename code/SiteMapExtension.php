<?php

/**
 *	SiteMap3 SiteTree extension
 *
 *	This class provides extension functionality for hiding
 *	pages from the sitemap.
 *
 *	The function filters pages depending on set {@link SiteMapPage}
 *	config.yml settings. Any key->pair value set in
 *	your config file will cause a page that contains a property
 *	that equals that value to be ignored (eg. Setting 'ShowInMenus: false' in the
 *	config file will not display any pages where ShowInMenus is
 *	false)
 *
 *	Provides an extension point to override functionality
 *	using updateCanSiteMap. Use this to provide more advanced or specific
 *	display filtering.
 *
 *
 *	@author Ryan Cotter
 *	@author Michael Bollig
 *
 *	@package sitemap3
 */
class SiteMapSiteTreeExtension extends DataExtension
{
	/**
	 *	Determines if a page should be displayed in the sitemap.
	 *	Uses values set under 'hidefrommap' applied to the {@link SiteMapPage}
	 *	class config.
	 *
	 *	@param Member $member
	 *	returns boolean
	 */
	public function canSiteMap($member=null) {
		$filter = Config::inst()->get('SiteMapPage', 'hidefrommap');
		if(empty($filter)) return true; //If no settings have been set, no reason to not show the page

		$result = true;
		foreach($filter as $k => $v)
		{
			$v = (is_array($v)) ? $v : array($v);
			if(in_array($this->owner->$k, $v)){
				$result = false;
				break;
			}
		}

		$extended = $this->owner->extend('updateCanSiteMap', $member);
		if(!empty($extended)){
			$result = $extended[0];
		}

		return $result;
	}
}
