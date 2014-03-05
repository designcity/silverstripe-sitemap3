<ul class="$SlickmapWidth" id="sitemap"><% loop $SiteMapTree %>
	<li<% if ParentMap.ClassName == HomePage %> id="home"<% end_if %>>
		<a href="$ParentMap.Link">$ParentMap.Title</a>
<% if $ChildrenMap %><% include SiteMapChild %><% end_if %>
	</li><% end_loop %>
</ul>