# Example "Auction" Laravel App

[Working example site](http://aberdeenwebsolutions.co.uk)

* Set up your .env file to suit your setup, create your DB table etc.
* `composer install`
* `php artisan migrate`
* `npm install` - we pull in ioredis and socket.io node packages
* `php artisan db:seed` - sets up your item categories
* `node socket.js` - although you may want to run this using "forever" or something if you want to keep it running - up to you.

Hit your site in the browser and set up 3 users (user 2 and 3 will be offered as auto-login accounts for playing with)
To disable this functionality remove the `fakeLogin()` method from the HomeController or remove the last route(named `loginAs`) in routes.php

You can then add some items as you please and play around with how it ties together.

### The files of interest are:

* `resources/views/layouts/app.blade.php` - this pulls in socket.io from a cdn, you'll need socket.io for this to work.
* `resource/views/items/show.blade.php` and `resource/views/home.blade.php` - particularly at the bottom where these files push to the scripts stack. This is where the listening for events takes place.
* `socket.js` this script runs your node server and sets up all the websockets and redis stuff.
* `app/Controllers/BidController.php` - accepts bids and broadcasts them to redis

The socket.js file listens for anything on the `bids-channel` channel and requires a `data.item_id` property be passed in from the server. It then emits (pushes) messages to the listeners on that `bids-channel[item_id]` channel.
(see the data we send in `app/Events/BidReceived.php` - Laravel pushes any public properties on an event as part of the data object) This allows us to use one server-side socket to listen for broadcasts but emit to different channels on the client end.
