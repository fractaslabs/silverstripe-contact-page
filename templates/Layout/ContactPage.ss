<div class="page-header">
	<div class="container">
		<div class="row">
			<div class="span12"><% include BreadCrumbs %></div>
			<h1 class="span12">$Title</h1>
		</div>
	</div>
</div>
<div class="container">
	<div class="row contact-page">
		<div class="span7">
		<% if Action = success %>
			<h2>$SuccessTitle</h2>
		    $SuccessText
		<% else_if Action = error %>
			<div class="message required">$ErrorMessage</div>
		<% else %>
			$SideContent
			$ContactForm
		<% end_if %>
		</div>
		<aside class="span5 contact-info">
			$Content
			<% if Image %>
				<% loop Image %>
				<div class="contact-img">
					<% loop setWidth(280) %>
						<img src="$URL" alt="$CurrentPage.Title" rel="art" />
					<% end_loop %>
				<% end_loop %>
				</div>
			<% end_if %>
		</aside>
		<div class="clearfix"></div>
	</div>
</div>