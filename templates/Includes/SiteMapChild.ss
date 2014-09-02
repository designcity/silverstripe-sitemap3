<ul><% loop $ChildrenMap %>
	<li>
		<a href="$ParentMap.Link">$ParentMap.MenuTitle</a>
<% if $ChildrenMap %><% include SiteMapChild %><% end_if %>
	</li><% end_loop %>
</ul>
