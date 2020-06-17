# Orders Exporter
A Customized Magento 2 module
that allows for exporting of orders in a CSV format
which can then be integrated with ETM/CUDI CRM as an Internet order.

## Commands
```php
php bin/magento prymag:orders:export --store_ids=1,2,3 --range="24 hours" --filenames="hal,sel,emp"
```

## Params
| Params | Required | Desc |
|-|-|-|
| store_ids | yes | Comma separated store ids |
| filenames | no | Comma separated filenames <br>that matches comma separated <br>store_ids<br><br>If no store_ids is provided <br>each exported file will <br>default to the format:<br>Export-"STORE_ID"-DATE<br><br>If comma separation does <br>not match, the missing <br>index will follow the <br>default format |
| range_start | no | Default: -24 Hours<br><br>A date/time string indicating <br>the start range of the orders will be selected<br>Valid formats are explained <br>in PHP Date and Time Formats. |