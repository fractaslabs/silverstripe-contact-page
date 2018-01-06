<% if $ElementalArea %><div class="empty"><% end_if %>
	<div class="contact-wrapper<% if $ElementalArea || Image %> with-blocks<% end_if %>">
		<div class="row equal">
			<div class="col-sm-8 form">
				<% if Action = success %>
					<h2>$SuccessTitle</h2>
				    $SuccessText
				<% else_if Action = error %>
					<div class="message required">$ErrorMessage</div>
				<% else %>
					$ContactInquiryForm
				<% end_if %>
			</div>
			<% if SideContent %>
			<div class="col-sm-4 contact-info">
				<div class="content">
					$SideContent
				</div>
			</div>
			<% end_if %>
		</div>
	</div>
<% if $ElementalArea %></div><% end_if %>
