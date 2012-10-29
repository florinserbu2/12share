12share - a (very) tiny web tracking system
====================

Short description
---------------------

The main idea: 

A basic click and conversions tracking system based with two user types ( advertisers and publishers );

### Advertisers

An advertiser can easly create a basic campaign by adding a product that he want to promote using 12share;
In the same time the product is the advertiser exchange currency for his campaigns clicks and conversions,
by allowing the advertiser's publshers to earn it for a number of credits. 

The advertiser can implement a tracking pixel into his site page in order to track conversions.
This tracking pixel is provided in the Dashboard pannel for each campaign.

### Publishers

A publisher can choose the advertiser and promote his campaigns, by sharing the campaign urls. The system 
counts the clicks gained trough the publisher and transform them in credits ( witch are added to publisher's account).
If the advertiser implemented the conversion pixel, then also the conversions are transformed in credits.
1 click = 1 credit.
1 conversion = 100 creadits. 

Shorter idea: Users can earn cool products by sharing their status on web and providing traffic for advertisers.

User experince
---------------------

The main parts of the system:

### Advertisers

Manage campaigns: Creates the campains, by submitting the campaign URL, the prize,the number of items available for buying with credits and
the credits cost. Here also are available details on how many products have been earned with credits.

Dashboard: See statistics about their campaigns, see the targeting pixel, build basic (date range) reports on each campaing
clicks and conversions.

### Publishers

Campaigns: See the available prizes and have the possiblity to earn them if have enough credits;
Dashboard: See the statistics and also the URL that they have to promote for each campaign;
My prizes: The already earned prizes - here should be a form for delivery informations

Setup
---------------------

Install the project on a apache + php + mysql server.
Run the script in /db_script folder

You can find the current added fresh publishers: user_1 and user_2
Also you can play with existing advertisers: telefoane-cool.net, 12trade.ro, oua-eco.ro
All with the 123123 password;

Do not forget the Zend Framework library.

The virtual:
<VirtualHost *:80>
        ServerName 12share.lh
		ServerAlias www.12share.lh

        ServerAdmin webmaster@12share.ro
        LogLevel notice
		
		
        <Directory d:\Projects\12share\public>
                Options FollowSymlinks MultiViews
                DirectoryIndex index.html index.htm index.php
                AllowOverride all
                Order allow,deny
                allow from all
        </Directory>
		SetEnv APPLICATION_ENV development
		DocumentRoot d:\Projects\12share\public
        	
</VirtualHost>



<VirtualHost *:80>
        ServerName static.12share.lh
		ServerAlias www.static.12share.lh

        ServerAdmin webmaster@12share.ro
		LogLevel notice
		
        <Directory d:/Projects/12share/static/>
                Options FollowSymlinks MultiViews
                DirectoryIndex index.html index.htm index.php
                AllowOverride all
                Order allow,deny
                allow from all
        </Directory>
		SetEnv APPLICATION_ENV development	
        DocumentRoot d:/Projects/12share/static

</VirtualHost>
