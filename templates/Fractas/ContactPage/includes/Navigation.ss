<div role="navigation" class="navbar">
	<div class="container">
		<div class="navbar-header">
			<button class="toggle-button navbar-toggle hidden-print" type="button">
				<span class="sr-only hidden">Menu</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<% with SiteConfig %>
			<a href="{$SiteHref}" class="navbar-brand" title="$Title<% if Tagline %> - $Tagline<% end_if %>">
				$Title
			</a>
			<% end_with %>
		</div>
		<div class="main-navigation text-center hidden-print">
			<ul class="nav navbar-nav hidden-xs">
				<% loop $MenuSet('Main').MenuItems %>
				<li<% if First %> class="first"<% else_if Last %> class="last"<% end_if %>>
					<a href="$Link" class="main-nav-link $LinkingMode trans-all">$MenuTitle</a>
				</li>
				<% end_loop %>
			</ul>
			<%-- <div class="right-nav">
				<button class="search-icon" role="button"></button>r
				<div class="search-wrap">$SearchForm</div>
			</div> --%>
		</div>
	</div>
</div>
