=== Woo Track list and Sample Player ===
Contributors: Dean Walker
Tags: audio, music, WooCommerce, Sample, Sampler, Music Player, file, display, track, tracks, player
Requires at least: 4.0
Tested up to: 4.3.1
Stable tag: 1.0.0
License: GPLv2 or later

Adds album track list and sample audio player on the WooCommerce producs page.

== Description ==
Adds the track list for an album on the WooCommerce products page to the short description.
It will also attempt to discover a sample version of the track and display an audio player for the sample. If no sample is found then just the track title is displayed.

== Installation ==

1. Upload the plugin to your WordPress installation
2. Activate the plugin through the "Plugins" menu in Wordpress.

== Usage ==

Once the plugin is activated then the short descriptions of your album products will be updated with the list of tracks.
If you wish to display an audio player that will play a sample of the track, then upload a sample track with the name of the track prefixed with 'sample-'. For example if you have a file named 'WooHoo.mp3' then create and upload a file call 'sample-WooHoo.mp3' in the Media Library.
Creating the samples is not possible with this plugin. I recommend the use of Sound eXchange (SoX) at http://sox.sourceforge.net/ 
On Linux I use the script

for i in *.mp3 ; 
do sox "$i" "sample-`basename "$i"`" trim 10 30 fade h 1 0 1 ; 
done

to convert a whole folders worth of mp3 files to sample files (run the script from the folder). In this example the sample will start 10 seconds into the track, play for 30 seconds, and will fade in and out for 1 second.
The SoX download contains a similar example for Windows.
SoX has a multitude of options and I suggest reading the documentation to get the most out of it.

If you sell both hard copies and virtual versions of your albums, then the track list and sample audio player can be added to the short description of the hard copy provided you follow the naming convention. If your album is 'The best of WooHoo' then create products titled 'The best of WooHoo - Digital Download' and 'The best of WooHoo - CD Hardcopy'.

These file naming conventions can be changed by editing the global variables at the top of woo-tlasp.php. There is no options screen.

The audio file sample icon style can be changed by modifying css file css/woo-tlasp.css or overriding the styles in your theme stylesheet.

== Screenshots ==

1. Album product with track list and sample player

== Frequently Asked Questions ==

None

== Upgrade Notice ==

None

== Changelog ==

None

