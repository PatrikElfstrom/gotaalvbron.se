var express = require('express'),
    exphbs  = require('express-handlebars'),
        twitter = require('./twitter'),
    bridge  = require('./bridge'),
    CronJob = require('cron').CronJob,
    config  = require("./config"),
    app     = express(),
    router  = express.Router(),
    path = require('path');

// Handlebars :-}
app.engine('handlebars', exphbs({ defaultLayout: 'main' }));
app.set('view engine', 'handlebars');

// Use CSS dir
app.set('views', path.join(__dirname, '/views'));
app.use( '/', express.static( path.join(__dirname, '/views/assets/css') ) );

// Block users
if(config.blockUsers) {
    app.use(function(req, res, next) {
        var ip = req.headers['x-forwarded-for'];

        if ( ip != config.whitelist && ip != '127.0.0.1' && ip != '127.0.1.1' ) {
            res.status(403).send('Forbidden');
        } else {
            next();
        }
    });
}

// Load Index
app.get('*', function(req, res) {
    var timestamp = Date.now(),
        bridgeStatus,
        lastBridgeDate;



    if( req.query.tweet != undefined ) {
        twitter.tweetBridgeStatus(1);
    }

    // If ?updateStatus is set, update bridgeStatus from API
    if( req.query.updateStatus != undefined ) {
        bridge.updateStatus();
    }

    // Get current bridge status
    bridge.status( function( lastBridge ) {
        bridgeDate = lastBridge.timestamp;
        bridgeStatus = lastBridge.status == 1 ? 'Ja' : 'Nej';

        // Render the index page
        res.render( 'index', {
            timestamp: timestamp,
            bridgeStatus: bridgeStatus,
            bridgeDate: bridgeDate
        });
    });
});

// Run cron job every minute to check the current bridge status
var job = new CronJob( config.cron, function() {
    bridge.updateStatus();
}, function () {
    console.log( 'Cron job stopped!' );
}, true,  'Europe/Stockholm');

router.use( function( req, res, next ) {
    next();
});

app.use( router );

// Fallback to 404
// app.use('*', function( req, res ){
//     res.render( '404' );
// });

// Hey! Listen!
var server = app.listen( config.server.port, config.server.host, function() {
    console.log("Server started on %s:%d in %s mode", server.address().address, server.address().port, app.settings.env);
});
