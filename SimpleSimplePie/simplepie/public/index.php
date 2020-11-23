<?php

error_reporting(0);
// Start counting time for the page load
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

// Include SimplePie
// Located in the parent directory
include_once('../autoloader.php');
include_once('../idn/idna_convert.class.php');
include('rss.php');

// Create a new instance of the SimplePie object


//$feed->force_fsockopen(true);

if (isset($_GET['js']))
{
    SimplePie_Misc::output_javascript();
    die();
}

$url = $_SERVER['QUERY_STRING'];

if(strpos($url, "rss_prod_feed") !== false){
    $tmp = (explode("=", $url));
    $url = end($tmp);
}
else{
    $url='http://blog.soreatu.com/atom.xml';
}
// Make sure that page is getting passed a URL


$data = url_get_contents($url);
$feed = new SimplePie();

if ($url)
{
    // Strip slashes if magic quotes is enabled (which automatically escapes certain characters)
    if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
    {
        $url= stripslashes($url);
    }

    $feed->set_cache_location('memcache://172.22.0.4:11211/?timeout=60&prefix=byc_');
    // Use the URL that was passed to the page in SimplePie
    $feed->set_feed_url($url);
}


// Allow us to change the input encoding from the URL string if we want to. (optional)
if (!empty($_GET['input']))
{
    $feed->set_input_encoding($_GET['input']);
}

// Allow us to choose to not re-order the items by date. (optional)
if (!empty($_GET['orderbydate']) && $_GET['orderbydate'] == 'false')
{
    $feed->enable_order_by_date(false);
}

// Trigger force-feed
if (!empty($_GET['force']) && $_GET['force'] == 'true')
{
    $feed->force_feed(true);
}

// Initialize the whole SimplePie object.  Read the feed, process it, parse it, cache it, and
// all that other good stuff.  The feed's information will not be available to SimplePie before
// this is called.
$success = $feed->init();

// We'll make sure that the right content type and character encoding gets set automatically.
// This function will grab the proper character encoding, as well as set the content type to text/html.
$feed->handle_content_type();

// When we end our PHP block, we want to make sure our DOCTYPE is on the top line to make
// sure that the browser snaps into Standards Mode.
?><!DOCTYPE html>

<html lang="en-US">
<head>
    <title>SimplePie: Demo</title>

    <link rel="stylesheet" href="./for_the_demo/sIFR-screen.css" type="text/css" media="screen">
    <link rel="stylesheet" href="./for_the_demo/sIFR-print.css" type="text/css" media="print">
    <link rel="stylesheet" href="./for_the_demo/simplepie.css" type="text/css" media="screen, projector" />

    <script type="text/javascript" src="./for_the_demo/sifr.js"></script>
    <script type="text/javascript" src="./for_the_demo/sifr-config.js"></script>
    <script type="text/javascript" src="./for_the_demo/sleight.js"></script>
    <script type="text/javascript" src="?js"></script>

</head>

<body id="bodydemo">

<div id="header">
    <div id="headerInner">
        <div id="logoContainer">
            <div id="logoContainerInner">
                <div align="center"><a href="http://simplepie.org"><img src="./for_the_demo/logo_simplepie_demo.png" alt="SimplePie Demo: PHP-based RSS and Atom feed handling" title="SimplePie Demo: PHP-based RSS and Atom feed handling" border="0" /></a></div>
                <div class="clearLeft"></div>
            </div>

        </div>
        <div id="menu">
            <!-- I know, I know, I know... tables for layout, I know.  If a web standards evangelist (like me) has to resort
            to using tables for something, it's because no other possible solution could be found.  This issue?  No way to
            do centered floats purely with CSS. The table box model allows for a dynamic width while centered, while the
            CSS box model for DIVs doesn't allow for it. :( -->
            <table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td>
                        <ul><li id="demo"><a href="./">SimplePie Demo</a></li><li><a href="http://simplepie.org/wiki/faq/start">FAQ/Troubleshooting</a></li><li><a href="http://simplepie.org/support/">Support Forums</a></li><li><a href="http://simplepie.org/wiki/reference/start">API Reference</a></li><li><a href="http://simplepie.org/blog/">Weblog</a></li><li><a href="../test/test.php">Unit Tests</a></li></ul>

                        <div class="clearLeft"></div>
                    </td></tr></tbody></table>
        </div>
    </div>
</div>

<div id="site">

    <div id="content">

        <div class="chunk">
            <form action="" method="get" name="sp_form" id="sp_form">
                <div id="sp_input">


                    <!-- If a feed has already been passed through the form, then make sure that the URL remains in the form field. -->
                    <p><input type="text" name="feed" value="<?php if ($feed->subscribe_url()) echo $feed->subscribe_url(); ?>" class="text" id="feed_input" />&nbsp;<input type="submit" value="Read" class="button" /></p>


                </div>
            </form>


            <?php
            // Check to see if there are more than zero errors (i.e. if there are any errors at all)
            if ($feed->error())
            {
                // If so, start a <div> element with a classname so we can style it.
                echo '<div class="sp_errors">' . "\r\n";

                // ... and display it.
                echo '<p>' . htmlspecialchars($feed->error()) . "</p>\r\n";

                // Close the <div> element we opened.
                echo '</div>' . "\r\n";
            }
            ?>

            <!-- There should be sample feeds. -->
            <!-- However I changed the feed param and hide all the urls for secutiry reasons :) -->
            <p class="sample_feeds"><strong>Or try one of the following:</strong>
           <!--     <a href="?feed=http://osnews.com/files/recent.rdf" title="News about every OS ever">OS News</a>,
                <a href="?feed=http://blog.japan.cnet.com/lessig/index.rdf" title="Test: EUC-JP Encoding">Japanese Language</a>,
                <a href="?feed=http://blogs.law.harvard.edu/home/feed/rdf/" title="Test: Tag Stripping">Harvard Law</a>,
                <a href="?feed=http://inessential.com/xml/rss.xml" title="Developer of NetNewsWire">Brent Simmons</a>,
                <a href="?feed=http://rss.news.yahoo.com/rss/topstories" title="World News">Yahoo! News</a> --></p>
        </div>

        <div id="sp_results">

            <!-- As long as the feed has data to work with... -->
            <?php if ($success): ?>
                <div class="chunk focus" align="center">

                    <!-- If the feed has a link back to the site that publishes it (which 99% of them do), link the feed's title to it. -->
                    <h3 class="header">
                        <?php
                        $link = $feed->get_link();
                        $title = $feed->get_title();
                        if ($link)
                        {
                            $title = "<a href='$link' title='$title'>$title</a>";
                        }
                        echo $title;
                        ?>
                    </h3>

                    <!-- If the feed has a description, display it. -->
                    <?php echo $feed->get_description(); ?>

                </div>

                <!-- Let's begin looping through each individual news item in the feed. -->
                <?php foreach($feed->get_items() as $item): ?>
                    <div class="chunk">

                        <!-- If the item has a permalink back to the original post (which 99% of them do), link the item's title to it. -->
                        <h4><?php if ($item->get_permalink()) echo '<a href="' . $item->get_permalink() . '">'; echo $item->get_title(); if ($item->get_permalink()) echo '</a>'; ?>&nbsp;<span class="footnote"><?php echo $item->get_date('j M Y, g:i a'); ?></span></h4>

                        <!-- Display the item's primary content. -->
                        <?php echo $item->get_content(); ?>

                        <?php
                        // Check for enclosures.  If an item has any, set the first one to the $enclosure variable.
                        if ($enclosure = $item->get_enclosure(0))
                        {
                            // Use the embed() method to embed the enclosure into the page inline.
                            echo '<div align="center">';
                            echo '<p>' . $enclosure->embed(array(
                                    'audio' => './for_the_demo/place_audio.png',
                                    'video' => './for_the_demo/place_video.png',
                                    'mediaplayer' => './for_the_demo/mediaplayer.swf',
                                    'altclass' => 'download'
                                )) . '</p>';

                            if ($enclosure->get_link() && $enclosure->get_type())
                            {
                                echo '<p class="footnote" align="center">(' . $enclosure->get_type();
                                if ($enclosure->get_size())
                                {
                                    echo '; ' . $enclosure->get_size() . ' MB';
                                }
                                echo ')</p>';
                            }
                            if ($enclosure->get_thumbnail())
                            {
                                echo '<div><img src="' . $enclosure->get_thumbnail() . '" alt="" /></div>';
                            }
                            echo '</div>';
                        }
                        ?>

                    </div>

                    <!-- Stop looping through each item once we've gone through all of them. -->
                <?php endforeach; ?>

                <!-- From here on, we're no longer using data from the feed. -->
            <?php endif; ?>

        </div>

        <div>
            <!-- Display how fast the page was rendered. -->
            <p class="footnote">Page processed in <?php $mtime = explode(' ', microtime()); echo round($mtime[0] + $mtime[1] - $starttime, 3); ?> seconds.</p>

            <!-- Display the version of SimplePie being loaded. -->
            <p class="footnote">Powered by <a href="<?php echo SIMPLEPIE_URL; ?>"><?php echo SIMPLEPIE_NAME . ' ' . SIMPLEPIE_VERSION . ', Build ' . SIMPLEPIE_BUILD; ?></a>.  Run the <a href="../compatibility_test/sp_compatibility_test.php">SimplePie Compatibility Test</a>.  SimplePie is &copy; 2004&ndash;<?php echo date('Y'); ?>, Ryan Parman and Sam Sneddon, and licensed under the <a href="http://www.opensource.org/licenses/bsd-license.php">BSD License</a>.</p>
        </div>

    </div>

</div>

</body>
</html>

