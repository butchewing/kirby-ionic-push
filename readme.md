# Kirby Ionic Push Plugin

Version: 0.0.2

This Kirby plugin provides an easy way to schedule and send a push notification to an Ionic mobile app. [Download the plugin](https://github.com/butchewing/kirby-ionic-push/archive/master.zip) from Github and put it into /site/plugins. It will automatically be loaded by Kirby.

Set your Ionic API Token.

Modify the existing [sample blueprint](https://github.com/butchewing/kirby-ionic-push/blob/master/sample-push.txt) to create your Push Notifications.

Create a CRON Job that points to http://yourkirbywebsite.tld/push/any-vaild-request-path/even-child-pages/or-grand-child-pages. It should run every 15 minutes.

Your site is now able to communicate with the Ionic Push API.


## Known issues
Ionic.io Push is still releasing features - it is still in beta.


## Contributions

Please fork this repository and make it better.


## Change Log

[View the Change Log](https://github.com/butchewing/kirby-ionic-push/blob/master/changelog.md)


## Author

Butch Ewing
<butch@butchewing.com>