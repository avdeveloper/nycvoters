# nycvoters

## Environment

**LAMP stack**
*	php >=7.1
*	MySQL >=5.7
*	Apache >=2.2
*	mod_rewrite


## Installation
`cd /target/folder`

`git clone https://github.com/ardent-services/nycvoters .`


**MySQL**

Create database and user

`mysql -u user -p database < nycvoters.sql`

API tokens are generated manually
*	generate some API key for example here `https://www.guidgenerator.com/`
*	save md5 hash of API key into `tokens` table

Edit environment files
*	api/_env.php
*	app/include/env.php
