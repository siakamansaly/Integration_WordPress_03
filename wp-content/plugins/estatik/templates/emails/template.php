<?php

global $es_settings;

$logo = $es_settings->email_logo_attachment_id ?
	wp_get_attachment_image_url( $es_settings->email_logo_attachment_id, 'full' ) : null;

?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .content {
            font-size: 14px;
            line-height: 16px;
            color:#6c6c6c;
            font-family: 'Open Sans', Arial, sans-serif;
        }

        h1 {
            color:#666;
            margin: 0;
            padding:0;
            padding-bottom: 10px;
            font-size: 26px;
            font-weight: 600;
        }
    </style>
</head>
<body width="100%" style="background-color:#FAFAFA; margin: 0; padding: 0; width: 100%;">
<center style="width: 100%; background-color: #FAFAFA;">
	<!--[if mso | IE]>
	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #FAFAFA;">
		<tr>
			<td>
	<![endif]-->

	<div style="max-width: 600px; margin-left:auto; margin-right: auto;" class="email-container">
		<!--[if mso]>
		<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600">
			<tr>
				<td>
		<![endif]-->

		<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-left: 1px solid #EBEAEA; border-right: 1px solid #EBEAEA; border-bottom: 1px solid #EBEAEA; margin-left:auto; margin-right: auto; background-color: #FFFFFF;">
			<?php if ( $logo ) : ?>
            <tr>
				<td style="padding: 30px 30px 15px;"><img src="<?php echo $logo; ?>" style="width: 100px; height: auto;"></td>
			</tr>
            <?php endif; ?>
			<tr>
				<td style="padding: 15px 30px 30px 30px;" class="content">
					<?php echo $content; ?>
				</td>
			</tr>
		</table>

		<!--[if mso]>
		</td>
		</tr>
		</table>
		<![endif]-->
	</div>

	<!--[if mso | IE]>
	</td>
	</tr>
	</table>
	<![endif]-->
</center>
</body>
</html>