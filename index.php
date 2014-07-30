<?php
require_once('bridgeService.php');
require_once('functions.php');

// Get bridge status
$bridge_status = getBridgeStatus();

$last_bridge = getLastBridge();
$last_bridge_date = $last_bridge ? $last_bridge['timestamp'] : '';

?><!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8" />
        <title itemprop="name">Hisingsbron.se</title>
        <meta name="description" itemprop="description" content="Är Göta älvbron öppen?">
        <link rel="author" href="https://twitter.com/Hisingsbron"/>
        <meta property="twitter:account_id" content="2660045456"/>
        <meta itemprop="datePublished" content="<?php echo $last_bridge_date; ?>">
        <link rel="canonical" href='http://hisingsbron.se' />
        <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
        <style>
        *, *:before, *:after {
            -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
        }
        ::selection {
            color: #fff;
            background: #1ba8e8;
        }
        
        ::-moz-selection {
            color: #fff;
            background: #1ba8e8;
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            margin: 0;
            font-family: 'Roboto Slab', serif;
            color: #fff;
            background-image: url(http://data.goteborg.se/TrafficCamera/v0.1/CameraImage/96afab3b-43c1-4684-8515-3ac2a1d972f0/26?<?php echo time(); ?>);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        h1, p {
            margin: 0;
            padding: 5%;
            font-family: 'Roboto Slab', serif;
            font-size: 5em;
            text-align: center;
        }
        
        p {
            font-size: 7em;
            font-weight: bold;
        }
        
        footer {
            width: 100%;
            padding: 1em;
            display: block;
            position: absolute;
            bottom: 0;
            text-align: center;
        }
        
        a {
            color: #fff;
            text-decoration: none;
            transition: all 100ms ease-in-out;
        }
        
        a:hover {
            color: #0094d8;
            text-decoration: none;
        }
        </style>
	</head>
	<body>
		<h1>Är Göta älvbron öppen?</h1>
		<p><?php echo $bridge_status ? 'Ja' : 'Nej'; ?></p>
        <footer><small>
            <a href="mailto:info@hisingsbron.se">info@hisingsbron.se</a><br/>
            <a target="_blank" href="https://twitter.com/Hisingsbron">@Hisingsbron</a>
            </small></footer>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-2643871-16', 'auto');
            ga('send', 'pageview');
        </script>
	</body>
</html>