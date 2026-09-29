=== Sendcloud for WooCommerce: Labels, Tracking & Returns ===
Version: 1.0.34
Developer: SendCloud Global B.V.
Developer URI: http://sendcloud.com
Tags: shipping, shipping rates, order tracking, service points, woocommerce
Requires at least: 4.9
Requires PHP: 7.0
Tested up to: 7.0
Stable tag: 1.0.34
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Contributors: sendcloudbv

Fast, automated shipping for WooCommerce: label creation, branded tracking & returns, all in one place.

== Description ==

[youtube https://www.youtube.com/watch?v=0GUV5W0bNi0 ]

= Fast, automated shipping for WooCommerce: label creation, branded tracking & returns, all in one place. =

Join 30,000+ ecommerce businesses by connecting your WooCommerce store to Sendcloud in minutes to automate shipping from checkout to returns, all from one platform. Automatically print labels, offer branded tracking and a self-service returns portal, and give customers delivery choices - from home, pickup point, same-day, or next-day, based on your carrier set-up.

= Features =

* Quick, code-free set-up
* Choose from 170+ carriers on Sendcloud rates, or add your own contracts
* Customized delivery experience: home delivery, pickup points, same day, or next day, based on your carrier set-up
* Branded tracking emails, SMS and WhatsApp
* Branded return portal
* Print labels, sync orders, and manage returns in one dashboard

= Why WooCommerce shops ship with Sendcloud: =

* Cut manual shipping work as you grow: Set shipping rules to auto-select the right carrier and method, reducing manual work and errors as order volume increases.

* Pick and pack faster: Speed up order processing with Pack & Go, so your team ships more orders in less time.

* Turn post-purchase into a retention channel: A branded tracking page and returns portal keep customers engaged with your brand, not the carrier's, through delivery and returns.

* Bring delivery into the tools your team already uses: Integrate with Gorgias, Klaviyo, and other CRM and CS tools to surface delivery updates where support and marketing already work.

* Get set up and supported from day one: Code-free onboarding backed by dedicated support, so you're shipping within minutes, not days.

= Supported carriers =
DHL, DHL Express, UPS, FedEx, DPD, GLS, Royal Mail, PostNL, PostNord, Colissimo, Correos, Poste Italiane, Bpost, InPost, and 170+ more carriers across Europe.

= 3rd Party Services =
Our plugin connects to a Sendcloud API and syncs order information in real time.
Please find the links to Terms of service and privacy policy for Sendcloud on following websites:

• [Terms of service](https://www.sendcloud.com/terms-conditions/)
• [Privacy Policy](https://www.sendcloud.com/privacy-policy)

== Installation ==

= General instructions =

1. Navigate to your WordPress store's dashboard and select Plugins > Add New Plugin. Search for 'Sendcloud connected shipping'.
2. Activate the plugin via the 'Plugins' screen in WordPress or install it directly through the WordPress plugins screen (recommended method). If you need more help getting set up, visit our [Help Center](https://support.sendcloud.com/hc/en-us/articles/35936346704017-Self-hosted-Shop-Systems-Troubleshooting-Integration-Issues) for quick fixes and setup tips for self-hosted shop systems.
3. Once connected to Sendcloud, navigate to Integrations > WooCommerce within the Sendcloud panel and enable service points.
4. In WooCommerce, go to Settings > Shipping and enable 'Service Point Delivery' for the desired zones.

= Checking the installation =

Your customers should be able to select _Service Point Delivery_ in the checkout page, alongside with a button labeled _Select Service Point_.

== Frequently Asked Questions ==

= How can I get started with Sendcloud? =

Learn how Sendcloud works and how to set it up in our [help center](https://support.sendcloud.com/hc/en-us/articles/360024833452-Getting-started-with-Sendcloud-).

= Do I need a Sendcloud account to use this plugin? =

Yes. In order to connect, you must register for an account and then, follow the installation instructions.

= What is a service point? =

Service Points are places that accept packages to be retrieved later by the customer.
e.g. A grocery store near your house or work may accept those packages.

= Need more help getting set up? =
Visit our [Help Center](https://support.sendcloud.com/hc/en-us/articles/35936346704017-Self-hosted-Shop-Systems-Troubleshooting-Integration-Issues) for quick fixes and setup tips for self-hosted shop systems.

== Screenshots ==

1. Easy shipping automation | Sendcloud
2. Try multi-carrier shipping | Sendcloud
3. Offer checkout options | Sendcloud
4. Put work on autopilot | Sendcloud
5. Automatically print shipping labels | Sendcloud
6. Provide branded tracking | Sendcloud
7. Automate returns | Sendcloud
8. More than 2k 5-star reviews | Sendcloud

== Changelog ==

= version 1.0.34 =
* Update README

= 1.0.33 =
* Fix for wp_postmeta table pollution

= 1.0.32 =
* Prevent broken access control in admin AJAX endpoints

= 1.0.28 =
* Fixed config table creation in multisite setups with over 100 multisites

= 1.0.27 =
* Added compatibility with Sendcloud Dynamic Checkout Plugin V2

= 1.0.26 =
* Compatible with Wordpress 7.0

= 1.0.25 =
* Compatible with Woocommerce 10.6.1
* Removed rudimentary Legacy API activation

= 1.0.24 =
* Compatible with Woocommerce 10.4.3

= 1.0.23 =
* Fixed connect to Sendcloud

= 1.0.22 =
* New redesigned plugin interface for speed optimization and translations.

= 1.0.21 =
* Add missing files, fix minor UI issues.

= 1.0.20 =
* New redesigned plugin interface for a more intuitive and modern user experience.

= 1.0.19 =
* Fixed service point data now correctly clears when changing the shipping method from a service point option.

= 1.0.18 =
* Track product EAN change and update related unprocessed orders.

= 1.0.17 =
* Service Point validation on initial load in Block Checkout.
* Ensured compatibility with other checkout modules.

= 1.0.16 =
* Implement compatibility with WooCommerce's block-based checkout

= 1.0.15 =
* Change plugin description

= 1.0.14 =
* Fixed checkout translation

= 1.0.13 =
* Fixed excessive DB queries

= 1.0.12 =
* Fixed woocommerce email template

= 1.0.11 =
* Changed sendcloud user creation logic

= 1.0.10 =
* Changed the user name created for the plugin. Added connect agreement

= 1.0.9 =
* Fixed issue with creating order with service point method when using block checkout

= 1.0.8 =
* Fixed carrier selection issue in WooCommerce admin panel.
* Resolved deprecated dynamic property warnings on PHP 8.2+.

= 1.0.7 =
* Integrate WooCommerce with an existing SendCloud account enabling service point delivery
  locations to be selected at the checkout.
