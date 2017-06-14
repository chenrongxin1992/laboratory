=== Global Translator ===
Tags: translator, multilanguage, automatic translator
Author: Davide Pozza
Contributors:
Donate link: http://www.paypal.me/davidepozza
Requires at least: 3.6
Tested up to: 4.4.*
Stable Tag: 2.0.2

Automatically translates your blog in 63 different languages!

== Description ==
Global Translator can automatically translate your blog in the following 63 different languages:
Afrikaans, Albanian, Arabian, Armenian, Azeri, Basque, Belarusian, Bosnian, Bulgarian, Catalan, Croatian, Czech, Chinese, Danish, Dutch, English, Estonian, Finnish, French, Galician, Georgian, German, Greek, Haitian (Creole), Hebrew, Hungarian, Icelandic, Indonesian, Irish, Italian, Japanese, Kazakh, Korean, Kyrgyz, Latin, Latvian, Lithuanian, Macedonian, Malagasy, Malay, Maltese, Mongolian, Norwegian, Persian, Polish, Portuguese, Romanian, Russian, Spanish, Serbian, Slovak, Slovenian, Swahili, Swedish, Tagalog, Tajik, Tatar, Thai, Turkish, Uzbek, Ukrainian, Vietnamese, Welsh.

Main features:

* **A NEW translation engine**: it uses the Yandex Translate APIs, which provide a very high and proven quality on its translation. 
* **Easy to configure**: You just need to choose the translations to enable and to register your Yandex API keys and the plugin will automagically start translating your website!. 
* **Search Engine Optimized**: it uses the permalinks by adding the language code at the beginning of all your URI. 
	For example the english version on www.domain.com/mycategory/mypost will be automatically transformed in 
	www.domain.com/en/mycategory/mypost. 
	It also automatically adds the "hfrelang" tags to your pages telling Google that it can serve that page to users searching in that language.  
* **Fast Caching System**: new fast, smart, optimized, self-cleaning and built-in caching system.

**Global Translator is the first real (and free) traffic booster for your blog!**
It can help you to reach a lot of new users and consequently to strongly increase your popularity: if you derive some benefits and if you want to support the development, 
please consider to support it with a [donation](http://www.paypal.me/davidepozza).


== Installation ==

1. 	Upload the folder "global-translator" to the "wp-content/plugins" directory.
2. 	Activate the plugin through the 'Plugins' menu in WordPress. 
3.	From the main menu choose "Global Translator" and configure your translation settings

== Changelog ==

= 2.0.2 =
* Improved speed on cache refresh actions

= 2.0.1 =
* Fixed flags bar refresh issue

= 2.0.0 =
* Plugin completely rewritten from scratch
* Integrated Yandex Translate APIs
* Better performances and translations thanks to translation enqueuing
* Up to 63 languages available 

= 1.3.2 =
* Fixed url fragments cleaning

= 1.3.1 =
* Removed N2H Link
* Fixed regexp patterns

= 1.3 =
* Added new option "Not yet translated pages management": you can choose between a 302 redirect and a 503 error on not yet translated pages
* Better flags layout on admin page

= 1.2.8 =
* fixed some 404 issues reported by Google WebMaster and related to bad parameters attached to the url (usg and rurl)

= 1.2.7 =
* Added 6 new languages

= 1.2.6 =
* Improvements on link cleaning
* default cache expire time switched to 15 days
* replaced 503 HTTP code ("Network Temporarily Unreachable") with a 302 redirect on not yet translated pages in order to remove the warning messages on GooGle WebMaster Tool

= 1.2.5.1 =
* some fixes on the new cleaning system

= 1.2.5 =
* updated page cleaning system in order to prevent new Google updates on the HTML sources

= 1.2.4 =
* Fixed trailing slash issue
* Replaced 404 errors with 302 redicection for better SEO
* Other fixes and optimizations

= 1.2.3 =
* Fixed sitemap integration for blogs not installed on the root path
* Fixed encoding problems related to the introduction of the new Google APIs

= 1.2.2.1 =
* Hacked new Google URL structure
* Added support for older PHP versions

= 1.2.1 =
* Added seven new languages: Albanian,Estonian,Galician,Maltese,Thai,Turkish,Hungarian
* Improved caching performances
* Added Show/Hide button for statistics on options page
* Optimized connections to google translation engine

= 1.2 =
* Fixed Chinese (Traditional) translation

= 1.1.2 =
* New configuration feature: flags bar in a single image (based on contribution by Amir - http://www.gibni.com)
* Translated Portuguese languages array (Thanks to Henrique Cintra)
* Added Chinese (Traditional) translation
* Fixed "division by zero" error
* Fixed image map configuration error

= 1.0.9.2 =
* Better IIS url rewriting support
* Fixed Norwegian configuration
* Moved shared function to header.php

= 1.0.9.1 =
* Changed HTTP error for not yet translated pages from 404 to 503 (Service Temporarily Unavailable)

= 1.0.9 =
* Added 404 error code for not yet translated pages
* Added support for IIS rewrite rules (based on the usage of "/index.php" at the beginning of the permalink)
* other performances improvements

= 1.0.8.1 =
* little fix for cached pages count on options page

= 1.0.8 =
* general performance improvement
* added check for blocking nested translation requests (i.e. www.mysite/en/fr/...)
* fixed A tags rendering
* moved cache dir outside the plugin dir
* fixed options page access problem
* fixed trailing slash issue

= 1.0.7.1 =
* removed call to "memory_get_usage" on debug method because it is not supported
  by certain php versions

= 1.0.7 =
* Added cache compression
* fixed layout bugs
* fixed link building problem (internal anchors not working)
* Added 11 new languages to Google Translation Engine!
 
= 1.0.6 =
* Added new optional cache invalidation time based parameter

= 1.0.5 =
* Random User Agent selection for translation requests
* Hacked new Google block introduced on (27th of August 2008)

= 1.0.4 =
* Performances improvement in cache cleaning algorithm
* fixed the sitemap plugin detection function
* fixed javascript errors on translated pages

= 1.0.3 =
* Added Debug option on the admin area
* Added Connection Interval option on the admin area
* Added more detailed messages and info on the admin page
* Updated new Promt translation url
* Fixed some issues about cache cleaning for blogs not using the permalinks
* Added experimental sitemap integration

= 1.0.2 =
* Fixed cache issue with blogs not using the pemalinks

= 1.0.1 =
* Fixed tags issue with older Wordpress versions (2.3.*)

= 1.0 =
* Improved cleaning system for translated pages
* New fast, smart, optimized, self-cleaning and built-in caching system. Drastically reduction of temporarily ban risk
* Added Widget Title
* Added 404 error page for deactivated translations

= 0.9.1.1 =
* Bug fix: Google translation issue

= 0.9.1 =
* Added file extension exclusion for images and resources (they don't need to be translated)
* Activated new Prompt configuration
* Fixed little issue with Portuguese translation
* Fixed Swedish, Arabic and Czech flags icons (thanks to Mijk Bee and Nigel Howarth)
* Added new (and better) event-based cache invalidation system

= 0.9 =
* Added support for 10 new languages for Google Translations engine: Bulgarian, Czech, Croat, Danish, Finnish, Hindi, Polish, Rumanian, Swedish, Greek, Norwegian
* Updated flags icons (provided by famfamfam.com)

= 0.8 =
* Updated Prompt engine
* Added experimental translation engines ban prevention system
* Improved caching management
* Improved setup process
* Fixed a bug on building links for "Default Permalink Structure"

= 0.7.2 =
* Fixed other bug on building links for "Default Permalink Structure"
* Optimized translation flags for search engines and bots
* changed cached filename in order to prevent duplicates
* added messages for filesystem permissions issues
* updated Google translation languages options (added Greek and Dutch)

= 0.7.1 =
* Fixed bug "Call to a member function on a non-object in /[....]/query.php". 
  It happens only on certain servers with a custom PHP configuration
* Fixed bug on building links for "Default Permalink Structure"

= 0.7 =
* Added two new translation engines: FreeTranslation and Promt Online Translation
* Added USER-AGENT filter in order to prevent unuseless connections to the translation services
* Added support for Default Permalink Structure (i.e.: "www.site.com/?p=111")
* Added widgetization: Global Translator is now widgetized!
* Fixed some bugs and file permission issues
* Excluded RSS feeds and trackback urls translation
* Fixed some problems on translated pages 

= 0.6.2 =
* Updated in order to handle the new Babelfish translation URL.(Thanks to Roel!)

= 0.6.1 =
* Fixed some layout issues
* Fixed url parsing bugs

= 0.6 =
* Fixed compatibility problem with Firestats
* Added the "gltr_" prefix for all the functions names in order to reduce naming conflicts with other plugins
* Added new configuration feature: now you can choose to enable a custom number of translations
* Removed PHP short tags
* Added alt attribute for flags IMG
* Added support to BabelFish Engine: this should help to solve the "403 Error" by Google
* Added my signature to the translation bar. It can be removed, but you should add a link to my blog on your blogroll.
* Replaced all the flags images
* Added help messages for cache support
* Added automatic permalink update system: you don't need to re-save your permalinks settings
* Fixed many link replacement issues
* Added hreflang attribute to the flags bar links
* Added id attribute to <A> Tag for each flag link
* Added DIV tag for the translation bar
* Added support for the following new languages: Russian, Greek, Dutch

= 0.5 =
* Added BLOG_URL variable
* Improved url replacement
* Added caching support (experimental): the cached object will be stored inside the following directory: "[...]/wp-content/plugins/global-translator/cache".
* Fixed japanese support (just another bug)

= 0.4.1 = 
* Better request headers
* Bug fix: the translated page contains also the original page

= 0.4 =
* The plugin has been completely rewritten
* Added permalinks support for all the supported languages
* Added automatic blog links substitution in order to preserve the selected language.
* Added Arabic support
* Fixed Japanese support
* Removed "setTimeout(180);" call: it is not supported by certain servers
* Added new option which permits to split the flags in more than one row

= 0.3/0.2 =
* Bugfix version
* Added Options Page

= 0.1 =
* Initial release