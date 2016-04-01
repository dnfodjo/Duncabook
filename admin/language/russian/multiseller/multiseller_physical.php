<?php

// General
$_['text_no_results'] = 'Нет результатов';
$_['text_success'] = 'Успешно';

$_['error_permission'] = 'Внимание: У вас нет доступа для модификации этой страницы!';

// Menu items
$_['ms_menu_shipping_methods'] = 'Способы доставки';

// Settings
$_['ms_config_download_limit_applies'] = 'Применить лимиты файлов скачиваний';
$_['ms_config_download_limit_applies_note'] = 'Выберите "Для всех продуктов", чтобы применить лимиты файлов скачиваний для всех создаваемых продавцами продуктов. Выберите "Только для цифровых продуктов", чтобы применить лимиты файлов скачиваний только для цифровых продуктов.';
$_['ms_config_download_limit_applies_all'] = 'Для всех продуктов';
$_['ms_config_download_limit_applies_digital'] = 'Только для цифровых продуктов';

// Settings shipping tab
$_['ms_tab_shipping'] = 'Доставка';
$_['ms_config_enable_product_shipping_cost_estimation'] = 'Добавить оценку стоимости доставки на страницу продукта';
$_['ms_config_enable_product_shipping_cost_estimation_note'] = 'С данной настройкой покупатель может оценить стоимость доставки на странице продукта. ';
$_['ms_config_enable_minicart_shipping_estimate'] = 'Добавить оценку стоимости доставки в мини-корзину';
$_['ms_config_enable_minicart_shipping_estimate_note'] = 'Данная настройка добавляет примерную оценку стоимости доставки в мини-корзину. Оценка очень приблизительная и имеет цель лишь показать интервал стоимости, так как адрес доставки неизвестен и покупатель на данный момент не выбрал способ доставки.';

// Catalog - Shipping methods
$_['ms_catalog_shipping_methods_heading'] = 'Способы доставки';
$_['ms_catalog_shipping_methods_breadcrumbs'] = 'Способы доставки';

$_['ms_shipping_methods_column_id'] = 'ID';
$_['ms_shipping_methods_column_name'] = 'Название';
$_['ms_shipping_methods_column_action'] = 'Действие';

$_['ms_catalog_insert_shipping_method_heading'] = 'Новый Способ Доставки';
$_['ms_catalog_edit_shipping_method_heading'] = 'Редактировать Способ Доставки';

$_['ms_shipping_method_entry_name'] = 'Название';
$_['ms_shipping_method_entry_description'] = 'Описание';
$_['ms_shipping_method_entry_language'] = 'Язык';

$_['ms_error_shipping_method_name'] = 'Ошибка: Название должно содержать от 3 до 32 символов';
$_['ms_error_shipping_method_language'] = 'Ошибка: Язык не выбран';

$_['ms_error_shipping_methods_exist'] = 'Ошибка: Способ доставки для продукта/продавца, использующий данный способ доставки существует(ют) в базе данных.';

// Transactions
$_['ms_transaction_shipping'] = 'Стоимость доставки для %s';
$_['ms_transaction_shipping_order'] = 'Стоимость доставки для заказа %s, у продавца %s';
$_['ms_transaction_shipping_refund'] = 'Возмещение доставки: %s';
$_['ms_transaction_shipping_refund_order'] = 'Возмещение доставки для заказа %s, у продавца %s';

// Mail - product bought
$_['ms_mail_product_purchased_physical'] = <<<EOT
Ваши продукты были куплены в магазине %s.
Покупатель: %s (%s)
Продукты:
%s
EOT;

$_['ms_mail_product_shipping_info_fixed'] = <<<EOT
Доставка: %s (%s)
EOT;

$_['ms_mail_product_total_price'] = <<<EOT
\n
Суммарная стоимость: %s
EOT;

$_['ms_mail_total_price_with_shipping'] = <<<EOT
\n
Сумма: %s
EOT;

$_['ms_mail_product_shipping_method'] = <<<EOT
\n
Способ доставки: %s
EOT;

$_['ms_mail_product_total_shipping'] = <<<EOT
\n
Суммарная стоимость доставки: %s
EOT;

$_['ms_mail_product_total'] = <<<EOT
\n
Сумма: %s
EOT;


?>