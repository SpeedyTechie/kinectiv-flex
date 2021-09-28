# kinectiv-flex
A flexible WordPress theme for Kinectiv websites (based on [kinectiv-start](https://github.com/SpeedyTechie/kinectiv-start))

### Starting a New Theme
*Before beginning, ensure you are viewing the documentation from the latest release: https://github.com/SpeedyTechie/kinectiv-flex/releases*

#### Initial Configuration
* Update `Theme Name: Kinectiv Flex` at the top of `style.css` to include the name for your theme.

#### Gulp
* Run `npm install` to install all needed packages.
* Run `gulp` to verify everything is installed and working.

#### Fonts
* Replace the Google Fonts enqueued in `kinectiv_flex_scripts()` in `functions.php` with the desired fonts.
* Update the default font family in the `Typography` section in `style.css`.
* If necessary, update the default font size in the `Typography` section in `style.css`. Update the default size and any size variations in the `Blocks` > `Text` section.
* Update the title font family in the `Blocks` > `Title` section in `style.css`. If necessary, update any title font size variations.
* Update the button font family (and font size if necessary) in the `Blocks` > `Button` section in `style.css`.
* If necessary, update the text classes assigned to WYSIWYG content in the `kf_wysiwyg_text_classes()` function in `functions.php`.

#### Colors
* Update the color themes in the `Colors` section in `style.css` and in the `kf_color_id_list()` function in `functions.php`.
* Update the background color of `body` in the `Elements` section in `style.css`. This color is used to fill extra space on screens wider than 2100px.
* Update the default background color of `.site` in the `Content` section in `style.css`.
* Update the default text color in the `Typography` section in `style.css`.
* Update the default link styles in the `Links` section in `style.css`.
* Update the date picker styles in the `Date Picker` section in `style.css`.
* If necessary, update the color classes assigned to WYSIWYG content in the `kf_wysiwyg_color_classes()` function in `functions.php`.

#### Padding
* If necessary, update the padding size variations in the `Padding` section in `style.css`.
