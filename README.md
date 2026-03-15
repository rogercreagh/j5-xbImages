# j5-xbImages
Joomla site module to display a single random image carousel. Image changes every N seconds (configurable).
 
Images may come from either a subfolder of the `/images/` folder in the Joomla root, or from the xbMusic_Albums table if the xbMusic Joomla component is installed.

If displaying images from a folder then:
 - images from all subfolders of the selected folder will be included.
 - valid image file types can be specified by extension. This defaults to 'jpg,jpeg,png' but any valid image file type can be included
 - ordering of images is by javascript rand() function each time selecting from the entire file list. There is no other ordering
 - an optional sub-title text can be displayed above the image (same on every image). 
 
If displaying images from the xbMusic Albums database then:
 - the random selection will be made from the list of image files in the database. It assumes that the files are accessible and valid.
 - the list can be filtered by any tags assigned to albums in xbMusic. If multiple tags are selected for filtering then the presence of ANY of the selected tags is a match.
 - as well as the sub-title text the album title & year and/or artist can be displayed below the image. 


General points: 
 - the module display is wrapped in a div with class `modxbimages`, the image has class `coverimg`, the title span class is `albumtitle`, the year span is `relyear` on the same line if it fits, and the artist has a line-break and a span class `albumartist`. Use these classes to style the module as you wish. If you edit the module css file `/media/mod_xbimages/css`, then your values will be overwritten during any update. So you probably want to use the template `user.css` file which you can edit from the Joomla admin backend.

If using the template css then specify the `.xbimages` wrapper class in the selectors which will ensure your changes take precedence over the defaults in the module css (which use just `.albumtitle` rather than `.modxbimages .albumtitle` for example)

Future enhancements:

Current ideas include for the xbMusic list source:
 - including ALL or NONE logic in tag filtering options
 - adding category filtering (by album category, or possibly artist category)
 - adding ordering options as alternative to random eg by year or alphabetical by artist or title
 
For both source options:
 - possible image transitions rather than straight replacement
 
It is likely that none of these will get done unless I have a personal use for them, or there is some kind of incentive
