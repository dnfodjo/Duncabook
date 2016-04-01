<?php

// General
$_['text_no_results'] = 'No results';
$_['text_success'] = 'Success';

$_['error_permission'] = 'Warning: You do not have permission to modify this!';

// Menu items
$_['ms_menu_shipping_methods'] = 'Shipping methods';

// Settings
$_['ms_config_download_limit_applies'] = 'Apply product downloads limits for';
$_['ms_config_download_limit_applies_note'] = 'Select "All products" to apply download limits for all the new products created by sellers. Select "Only digital products" to apply this limit only to digital products.';
$_['ms_config_download_limit_applies_all'] = 'All products';
$_['ms_config_download_limit_applies_digital'] = 'Only digital products';

// Settings shipping tab
$_['ms_tab_shipping'] = 'Shipping';
$_['ms_config_enable_product_shipping_cost_estimation'] = 'Enable product page shipping cost estimation';
$_['ms_config_enable_product_shipping_cost_estimation_note'] = 'This setting enables shipping cost estimation in the product page.';
$_['ms_config_enable_minicart_shipping_estimate'] = 'Enable minicart shipping cost estimation';
$_['ms_config_enable_minicart_shipping_estimate_note'] = 'This setting enables shipping cost estimation in the minicart. Estimation is approximate, since delivery address is not known and customer has not selected shipping method.';
$_['ms_config_physical_product_categories'] = 'Make categories physical';
$_['ms_config_physical_product_categories_note'] = 'Only allow to list tangible products in categories selected here.';
$_['ms_config_digital_product_categories'] = 'Make categories digital';
$_['ms_config_digital_product_categories_note'] = 'Only allow to list digital products in categories selected here.';


// Catalog - Shipping methods
$_['ms_catalog_shipping_methods_heading'] = 'Shipping methods';
$_['ms_catalog_shipping_methods_breadcrumbs'] = 'Shipping Methods';
$_['ms_catalog_shipping_settings'] = 'Shipping Settings';

$_['ms_shipping_methods_column_id'] = 'ID';
$_['ms_shipping_methods_column_name'] = 'Name';
$_['ms_shipping_methods_column_action'] = 'Actions';

$_['ms_catalog_insert_shipping_method_heading'] = 'New Shipping Method';
$_['ms_catalog_edit_shipping_method_heading'] = 'Edit Shipping Method';

$_['ms_shipping_method_entry_name'] = 'Name';
$_['ms_shipping_method_entry_description'] = 'Description';
$_['ms_shipping_method_entry_language'] = 'Language';

$_['ms_error_shipping_method_name'] = 'Error: Name should be between 3 and 32 characters long';
$_['ms_error_shipping_method_language'] = 'Error: Language is not selected';

$_['ms_error_shipping_methods_exist'] = 'Error: Product/Seller shipping methods using this method exist in the database';

// Transactions
$_['ms_transaction_shipping'] = 'Shipping cost for %s';
$_['ms_transaction_shipping_order'] = 'Shipping cost for order %s, seller %s';
$_['ms_transaction_shipping_refund'] = 'Shipping refund: %s';
$_['ms_transaction_shipping_refund_order'] = 'Shipping refund for order %s, seller %s';

// Mail - product bought
$_['ms_mail_product_purchased_physical'] = <<<EOT
Your product(s) have been purchased from %s.
Customer: %s (%s)
Products:
%s
EOT;

$_['ms_mail_product_purchased_physical_no_email'] = <<<EOT
Your product(s) have been purchased from %s.
Customer: %s
Products:
%s
EOT;

$_['ms_mail_product_shipping_info_fixed'] = <<<EOT
Shipping: %s (%s)
EOT;

$_['ms_mail_product_total_price'] = <<<EOT
\n
Total price: %s
EOT;

$_['ms_mail_total_price_with_shipping'] = <<<EOT
\n
Total: %s
EOT;

$_['ms_mail_product_shipping_method'] = <<<EOT
\n
Shipping method: %s
EOT;

$_['ms_mail_product_total_shipping'] = <<<EOT
\n
Total shipping cost: %s
EOT;

$_['ms_mail_product_total'] = <<<EOT
\n
Total: %s
EOT;


?>