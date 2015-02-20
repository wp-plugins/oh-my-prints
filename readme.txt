=== Plugin Name ===
Contributors: jondor
Donate link: http://www.funsite.eu/ohmyprints/
Tags: widget,sale,omyprints,werkaandemuur
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connect a post or page to an object for sale on www.ohmyprint.com or www.werkaandemuur.nl as it's known in the Netherlands

== Description ==
Connect a post or page to an object for sale on www.ohmyprint.com/www.werkaandemuur.nl
If a work is not for sale it points to your shop.

Clicks on the shop/photo link are counted and shown as a column in the page/posts overview so you can see how many users clicked on 
the link. 

After installing this plugin you have to put the widget on the pages with sellable photo's, paintings and the like.
In the page or posts on the photo you will find a field to save the artcode. Pages with an artcode het the "for sale" texts
as given in the widget settings. All other photo's get the description.
In the description you can use an <a %s>... </a> for the link to the ohmyprint site. As this link works through an ajax call there are
some attributes needed and all those are filled in on the %s spot. After clicking the page is loaded in a new tab/window.

in the posts and pages overview there will be a column "sale clicks" which shows the number of times the widget is clicked. 
If the "for sale" link was followed on an photo which is not for sale an '*' is added to the counter.

Btw. This plugin has notthing to do with "Oh My Print" or "Werk aan de muur". They don't have an api or any programmer support and this 
plugin basically redirects your users to the right page. To bad for the lack of api, I would love to show some real statistics.. 

= example =

For an example of the plugin in action see: 
http://www.funsite.eu/2015/02/los-hoes-het-lammerinkswonner/

I use this widget together with WooSidebars (https://wordpress.org/plugins/woosidebars/) so I can easy controle where the widget ends up.. 
The widget isn't tested for 4.1 but works without problems for me. 
In my case it's only loaded for photo's in the "for sale" category. 

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the widget to a widgetarea and adjust the fields if needed.
4. Go to the pages/posts you want to connect and add the artcode.

== Frequently Asked Questions ==

= Why did you write this widget? =
To hopefully stimulate sales. 

= I would like some sales statistics on the admin screen =
Yep.. me too.. but no api. 

= the artcode is an image. Can't cut and paste?! =
Well, yes.. but the same code is also part of the URL. And there you CAN cut and past!

== Screenshots ==

1. Widget as the user sees it
2. The widget settings
3. the artcode window
4. the sale clicks overview.
4. where to find the artcode.

== Changelog ==

= 1.0 =

* First release

== Upgrade Notice ==

Nothing yet.
