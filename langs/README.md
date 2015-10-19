###Getting started
===================
In this document i will try to explain how to properly translate plexWatch/Web to your own language.
A basic knowledge of XML and/or machine code is recommended, but not required.
If you already know what to do, just copy the contents of en_US.xml, and have fun translating!

###File requirements
=====================
All translation files need to be in this folder (includes/langs) in order to work.
PlexWatch/Web scans this dir for new translation files.
The translation file needs to use the xml file extension, and has to use your locale.
*Example: if you are writing a German translation, your file should be named de_DE.xml

In order to get your translation file to work properly, some strings in your file should be named correctly.
Let's go through some of the sections of the file.

<transInfo>
	<version>0.1</version>
	<author>Your Name</author>
	<date>28-01-2015</date>
</transInfo>

#transInfo
This section covers the basic info of the file itself.
Here you can set the version number of your translation file, write your name, and the date the file whas made.

<langinfo>
	<code>en_US</code>
	<loc_name>English</loc_name>
	<int_name>English</int_name>
	<siteTitle>plexWatch</siteTitle>
</langinfo>

#langinfo
This section covers everything related to the language you are translating to.
The tag <code></code> require you to write your locale. This needs to match your filename (without '.xml').
The tag <loc_name></loc_name> is used for your local language name.
The tag <int_name></int_name> is used for the English name of your language.
The tag <siteTitle></siteTitle> is used for the title of plexWatch. So if you change this, your site title changes.

*For example, if you are translating plexWatch/Web to German, your langinfo section might look like this:

<langinfo>
	<code>de_DE</code>
	<loc_name>Deutsch</loc_name>
	<int_name>German</int_name>
	<siteTitle>plexWatch Deutschland</siteTitle>
</langinfo>



These are all the available settings. The rest of the file is used for translating.
It is recommended that you copy the contents of a language file, to your new language file, and start translating from there on.