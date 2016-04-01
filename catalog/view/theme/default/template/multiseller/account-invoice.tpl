<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link media="all" rel="stylesheet" href="catalog/view/theme/default/stylesheet/invoice.css">
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body>
<div class="container ms-account-order-info">
	<header id="header_invoice" class="row">
		<!-- logo -->
		<div class="col-xs-4">
			<img id="avatar" alt="<?php echo $company; ?>" title="<?php echo $company; ?>" src="<?php echo $logo; ?>">
		</div>
		<!-- contacts and addresses -->
		<div class="col-xs-8">
			<ul>
				<?php if(isset($phone) && $phone){ echo '<li><i class="fa fa-phone"></i>'.$phone.'</li>'; }?>
				<?php if(isset($fax) && $fax){ echo '<li><i class="fa fa-fax"></i>'.$fax.'</li>'; }?>
				<?php if(isset($mail) && $mail){ echo '<li><i class="fa fa-envelope"></i>'.$mail.'</li>'; }?>
				<?php if(isset($address) && $address){ echo '<li><i class="fa fa-map-marker"></i>'.$address.'</li>'; }?>
			</ul>
		</div>
	</header>	
	<h2>Invoice</h2>
	<!-- TO -->
	<div class="left-pad-5">
		<h3>TO:</h3>
		<?php echo $payment_address; ?>
	</div>
	<!-- products -->
	<div class="left-pad-5">
		<table>
			<thead>
				<tr>
					<th class="td-left"><?php echo $column_name; ?></th>
					<th class="td-left"><?php echo $column_model; ?></th>
					<th class="td-center"><?php echo $column_quantity; ?></th>
					<th class="td-center"><?php echo $column_price; ?></th>
					<th class="td-center"><?php echo $column_total; ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $product) { ?>
				<tr>
					<td class="td-left"><?php echo $product['name']; ?>
					<td class="td-left"><?php echo $product['model']; ?></td>
					<td class="td-center"><?php echo $product['quantity']; ?></td>
					<td class="td-center"><?php echo $product['price']; ?></td>
					<td class="td-center"><?php echo $product['total']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
			<tfoot style="text-align: center;">
				<?php foreach ($totals as $total) { ?>
				<tr>
					<td colspan="4"></td>
					<td class="total"><?php echo $total['text']; ?></td>
				</tr>
				<?php } ?>
			</tfoot>
		</table>
	</div>
	<div class="left-pad-5 row">
		<div class="col-xs-8 allwidth">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</div>
		<div class="col-xs-4 allwidth">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. </div>
	</div>
</div>