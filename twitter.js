var config  = require("./config"),
    twitter = require('twitter'),
    util = require('util');

module.exports = {

    client: new twitter({
        consumer_key: config.twitter.consumerKey,
        consumer_secret: config.twitter.consumerSecret,
        access_token_key: config.twitter.accessTokenKey,
        access_token_secret: config.twitter.accessTokenSecret
    }),

    tweetBridgeStatus: function( status ) {

        if( status == 1 ) {
            var tweets = config.twitter.tweets.open;
        } else {
            var tweets = config.twitter.tweets.closed;
        }

        // get random tweet
        var tweet = tweets[Math.floor(Math.random()*tweets.length)];

        // Inject the current date and time
        // not really necessary but will make the tweets more unique
        var now = new Date();
        var monthIndex = now.getMonth();
        var month = config.months[monthIndex];
        var day = now.getDate();
        var hour = now.getHours();
        var minutes = now.getMinutes();

        minutes = ( minutes < 10 ? '0' : '' ) + minutes;

        // var date = day + ' ' + month + ' ' + hour + ':' + minutes;
        var date = 'Klockan ' + hour + ':' + minutes;

        tweet = util.format(tweet, date);

        module.exports.tweet(tweet);
    },

    tweet: function( tweet ) {
        module.exports.client.post('statuses/update',
            {
                'status': tweet,
                'lat': '57.714653',
                'long': '11.966836',
                'place_id': '53e060d6652640f4',
                'display_coordinates': true
            },
            function(error, tweet, response){
                if(error) throw error;
                console.log(tweet);  // Tweet body.
                console.log(response);  // Raw response object.
            }
        );
    }
};
