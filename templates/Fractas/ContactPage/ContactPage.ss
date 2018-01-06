<!DOCTYPE html>
<!--[if !IE]><!-->
<html lang="$ContentLocale">
<!--<![endif]-->
<!--[if IE 8 ]><html lang="$ContentLocale" class="ie ie8"><![endif]-->
<!--[if IE 9]><html lang="$ContentLocale" class="ie ie9"><![endif]-->
	<head>
		<% include PageHead %>
	</head>
	<body class="contact">
		<div class="wrapper-all">
			<header class="header">
				<% include Navigation %>
			</header>
			<div class="middle">
				<% if $ElementalArea %>
				<div class="blocks">$ElementalArea</div>
				<% else_if Image %>
				<div class="contact-img-wrapper">
					<div class="contact-img" style="background-image: url({$Image.URL});">
						<div class="container">
							<h1 class="page-title">$Title</h1>
							<% if $Content %>
							<div class="row">
								<div class="col-sm-8">
									$Content
								</div>
							</div>
							<% end_if %>
						</div>
					</div>
				</div>
				<% end_if %>
				<div class="container">
					$Layout
				</div>
			</div>
			<% include Footer %>
		</div>
	</body>
</html>
