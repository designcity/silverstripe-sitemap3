#Site map module for Silverstripe 3.1

##Description
Dynamically generates a SiteMap tree for a silverstripe website. Offers alternate display themes, page filtering options and accessibility markup.

##Requirements
* Silverstripe ~3.1

##Example Usage

Example config.yml file in your project root 
```yaml
---
Name: {projectconfig}
---
SiteMapPage:
  #Determines what Pages to not display
  hidefrommap:
    #Checks each value against a Page's ClassName and does not display if it matches
    ClassName:
      - Terms&CondsPage
      - SecretPage
    #Hides everything that isn't shown in the menu
    ShowInMenus: false
    #Hide any Page with this particular title
    Title: The Unmappable Page
```

Example SiteMapPage content
```
This is the site's sitemap.
This and the line above are just flavour text
[SiteMap]
This line will appear after the generated SiteMap tree
```

##Displaying Pages
By default, Pages are hidden from the site map by altering the config.yml values for SiteMapPage.
Anything under the 'hidefrommap' key is checked against a Page's properties and if the property value matches or is
in the config-set value, it is not displayed.

Be specific with what to hide; hiding a parent page will still cause it's children to be displayed. They will be inserted at the tree depthin which the parent would originally inhabit.

##Extending
The module provides a function that can be overriden on each Page type, canSiteMap($member).
You can conceal Pages this way or add your own display functionality to the Page.

Another method, if you wish for direct descendants to retain the default functionality of canSiteMap(), is that you can
overwrite the result by extending the Page and implementing the updateCanSiteMap($member) function. This takes precedence over
canSiteMap() and can allow for specific functionality on select pages.

##Installing with Composer
Simply call 
```
composer require designcity/silverstripe-sitemap3 dev-master
```

##Adding themes
If you think you have a pretty tip-top theme, submit a pull request and we'll look at integrating it into the project.
