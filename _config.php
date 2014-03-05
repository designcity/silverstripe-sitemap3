<?php

ShortcodeParser::get()->register('SiteMap',array('SiteMapPage','SiteMapShortCodeHandler'));
define('SITEMAP3_DIR', basename(dirname(__FILE__)));