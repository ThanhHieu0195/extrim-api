# API Extrim

## Configs:
- copy .htaccess & extrim-api to root website

- change info connect db in classes/Constant.php
 
 ## Supports:
 ### Login: 
 - /api/v1/login [post] 
 
 - params: username, password
 
 - Res: {"status":true,"message":"Login completed","token":"923b72fa629bb92ec3130659d3f09088"}

### Register 

 - /api/v1/register [post] 
 
 - params: username, birthday, email, password
 
 - Res: {"status":true,"message":"Register completed","token":"533780ab61fe28d3c830ef3c0a9de375"}
 
 ### Product
 
 - /api/v1/product [get] => get all product (limit default = 10)
 - /api/v1/product/:id [get] => get product with id
 
 ### Attachment
 - /api/v1/attachment/:id [get] => get attachment with id
 - /api/v1/attachment/upload => upload image
 