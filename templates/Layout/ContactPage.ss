<div class="row">
	<div class="col-md-12">
		<% include PageHeader %>
	</div>
	<div class="col-sm-8 form">
		<% if Action = success %>
			<h2>$SuccessTitle</h2>
		    $SuccessText
		<% else_if Action = error %>
			<div class="message required">$ErrorMessage</div>
		<% else %>
			$SideContent
			$ContactInquiryForm
		<% end_if %>
	</div>
	<% if Content || Image %>
	<div class="col-sm-4 contact-info">
		<div class="content">
			$Content
			<% if Image %>
			<div class="contact-img">
				<% with Image %><img src="$setWidth(280).URL" alt="$CurrentPage.Title" /><% end_with %>
			</div>
			<% end_if %>
		</div>
	</div>
	<% end_if %>
</div>