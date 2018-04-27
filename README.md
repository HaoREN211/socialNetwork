# Preparation
1. Get codes of the file `facebook`, `linkedin`, `twitter`, `instagram` and `google`.
2. Go to each folder listed and make `composer install` command to install the libraries.
3. Analyze the php file codes in the `main` folder of each social network, especially `index.php` (which manages the redirection button) and `callback.php` (if it exists, on Instagram and LinkedIn it is contained in the index.php file in the part of `$ _GET ['code']`)
4. YouTube and Google Analytics are all in the `google` folder.


# Application configuration of each social network.
### Facebook
1. Go to the site https://developers.facebook.com/apps/
2. The `Facebook Login` tab in the menu at the bottom left. Then `Settings`.
3. Add the callback.php file url to the 'valid OAuth redirection URI' field that you will use in the next step.


### Twitter
1. Go to the site https://apps.twitter.com/app/
2. Then in the tab `Callback URL`.
3. Add the callback.php file url to the `Callback URL` field that you will use in the next step.


### LinkedIn
1. Go to the site https://www.linkedin.com/developer/apps/
2. Then add the url of the file callback.php in the `OAuth 2.0`.`URL redirection allowed` field that you will use in the next step.


### Instagram
1. Go to the site https://www.instagram.com/developer/clients/
2. Then in the `Security` tab.
3. Add the url of the file callback.php in the `Valid redirect URIs` field that you will use in the next step.


# Configuring the redirect URL.
### Facebook
Change the redirect URL by what you use.


### Instagram
Change the `$ APP_CALLBACK` parameter value to the redirection URL used in the index.php file.


### LinkedIn
Change the `$ url_redirect` parameter value by the redirection URL used in the index.php file.


### Twitter
Change the `$ api_callback_uri` parameter value by the redirection URL used in the index.php file.


### YouTube & Google Analytics
Modify the `$ redirect_uris` and` $ redirect_uri` parameter value with the redirect URL used in the index.php file. $ redirect_uris is a string array and $ redirect_uri is a simple string.