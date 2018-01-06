<footer class="footer hidden-print">
	<div class="footer-top">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<a href="{$BaseHref}" class="navbar-brand">
						$SiteConfig.Title
					</a>
				</div>
				<div class="col-sm-6 footer-menu">
					<ul class="">
						<% loop $MenuSet('Footer').MenuItems %>
						<li<% if First %> class="first"<% else_if Last %> class="last"<% end_if %>>
							<a href="$Link" class="$LinkingMode trans-col">$MenuTitle</a>
						</li>
						<% end_loop %>
					</ul>
				</div>
				<div class="col-sm-3">
					$SocialNav
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<p class="copyright">Copyright &copy;{$Now.Year} {$SiteConfig.Title} Sva prava pridržana.</p>
				</div>
			</div>
		</div>
	</div>
</footer>
