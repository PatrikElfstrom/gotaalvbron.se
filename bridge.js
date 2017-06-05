var http    = require("http"),
    https   = require("https"),
    mysql   = require('mysql'),
    twitter = require('./twitter'),
    config  = require("./config");

module.exports = {
    // Get current bridge status from database
    status: function ( callback ) {

        db.query( 'SELECT * FROM bridge_status ORDER BY timestamp DESC LIMIT 1', function( err, rows, fields ) {
            if (err) return done(err);

            callback( rows[0] );
        });
    },

    // Insert new bridge status
    insertStatus: function ( status, callback ) {

        db.query( 'INSERT INTO bridge_status SET status = ' + status, function( err, rows, fields ) {
            if (err) throw err;

            callback( rows );
        });
    },

    // Update the current bridge status
    updateStatus: function () {

        // Get current bridge status
        module.exports.getStatus( function( bridgeStatus ) {

            // Get last bridge status
            module.exports.status( function( lastBridgeStatus ) {

                // If the current status is not the same as the last, update the database
                if( bridgeStatus != lastBridgeStatus.status ) {

                    // For safety
                    var status = bridgeStatus == 1 ? 1 : 0;

                    // Insert current status into the database
                    module.exports.insertStatus( status, function() {
                        cl('Updated bridge status to %d', status);
                    });

                    twitter.tweetBridgeStatus( status );
                }
            });
        });
    },

    // Get the current bridge status from API
    getStatus: function ( callback ) {

        var apiHost = 'data.goteborg.se',
            apiPath = '/BridgeService/v1.0/GetGABOpenedStatus/' + config.gbgApi.key + '?format=json';

        var options = {
            host: apiHost,
            port: 80,
            path: apiPath,
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        };

        return getJSON( options, function( statusCode, bridgeStatus ) {

            cl('Requesting bridge status... ', bridgeStatus);

            if( bridgeStatus && bridgeStatus.Value === true ) {
                callback( 1 );
            } else {
                callback( 0 );
            }

        });
    }
};

var db = {

    query: function( query, results ) {

        var connection = mysql.createConnection({
            host: config.database.host,
            user: config.database.username,
            password: config.database.password,
            database : config.database.database
        });

        connection.connect();

        connection.query( query, results );

        connection.end();
    }
}

var getJSON = function ( options, onResult ) {

    var protocol = options.port == 443 ? https : http;

    var request = protocol.request( options, function( res ) {
        var output = '';

        res.setEncoding('utf8');

        res.on('data', function ( chunk ) {
            output += chunk;
        });

        res.on('end', function() {
            var obj = JSON.parse( output );
            onResult( res.statusCode, obj );
        });
    });

    request.on('error', function(err) {
        //res.send('error: ' + err.message);
    });

    request.end();
}

var cl = function () {
    if( config.debug == true) {
        console.log.apply(console, arguments);
    }
}
