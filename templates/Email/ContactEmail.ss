<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<style>
			p{
				font-size: 1.2em;
				color: #444;
			}
				p span{
					font-size: 1.2em;
					font-weight:bold;
					color: #444;
				}
			p.comments{
				font-size: 1.4em;
				color: #222;
				padding: 20px;
			}
		</style>
	</head>
	<body style="font-size:15px;">	
		<p><span><% _t("ContactPage.FIRSTNAME", "First Name") %></span>$FirstName</p>
		<p><span><% _t("ContactPage.LASTNAME", "Last Name") %></span>$LastName</p>
		<p><span><% _t("ContactPage.EMAIL", "Email") %></span>$Email</p>
		<p><span><% _t("ContactPage.PAGETITLE", "Page Title") %></span>$PageTitle</p>
		<p><span><% _t("ContactPage.LOCALE", "Locale") %></span>$Locale</p>
		<p class="comments"><span><% _t("ContactPage.DESCRIPTION", "Description") %></span></br>$Description</p>
	</body>
</html>