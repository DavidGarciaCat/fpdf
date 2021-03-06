<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF;

/**
 * @author FPDF Team
 */
interface FPDFInterface
{
    /**
     * Whenever a page break condition is met, the method is called, and the break is issued or not depending on the
     * returned value. The default implementation returns a value according to the mode selected by SetAutoPageBreak().
     */
    public function acceptPageBreak();

    /**
     * Imports a TrueType, OpenType or Type1 font and makes it available. It is necessary to generate a font definition
     * file first with the MakeFont utility.
     *
     * The definition file (and the font file itself when embedding) must be present in the font directory.
     * If it is not found, the error "Could not include font definition file" is raised.
     *
     * @param string $family Font family. The name can be chosen arbitrarily. If it is a standard family name,
     *                       it will override the corresponding font.
     * @param string $style  Font style. Possible values are (case insensitive / The default value is regular):
     *                       empty string: regular
     *                       B: bold
     *                       I: italic
     *                       BI or IB: bold italic
     * @param string $file   The font definition file.
     *                       By default, the name is built from the family and style, in lower case with no space.
     */
    public function addFont($family, $style = '', $file = '');

    /**
     * Creates a new internal link and returns its identifier. An internal link is a clickable area which directs
     * to another place within the document.
     *
     * The identifier can then be passed to Cell(), Write(), Image() or Link().
     * The destination is defined with SetLink().
     */
    public function addLink();

    /**
     * Adds a new page to the document. If a page is already present, the Footer() method is called first to output the
     * footer. Then the page is added, the current position set to the top-left corner according to the left and top
     * margins, and Header() is called to display the header.
     *
     * The font which was set before calling is automatically restored. There is no need to call SetFont() again if you
     * want to continue with the same font. The same is true for colors and line width.
     *
     * The origin of the coordinate system is at the top-left corner and increasing ordinates go downwards.
     *
     * @param string $orientation Page orientation. Possible values are (case insensitive):
     *                            P or Portrait
     *                            L or Landscape
     *                            The default value is the one passed to the constructor.
     * @param string $size        Page size. It can be either one of the following values (case insensitive):
     *                            A3; A4; A5; Letter; Legal
     *                            or an array containing the width and the height (expressed in user unit).
     *                            The default value is the one passed to the constructor.
     * @param int    $rotation    Angle by which to rotate the page. It must be a multiple of 90;
     *                            positive values mean clockwise rotation. The default value is 0.
     */
    public function addPage($orientation = '', $size = '', $rotation = 0);

    /**
     * Defines an alias for the total number of pages. It will be substituted as the document is closed.
     *
     * @param string $alias The alias. Default value: {nb}.
     */
    public function aliasNbPages($alias = '{nb}');

    /**
     * Prints a cell (rectangular area) with optional borders, background color and character string. The upper-left
     * corner of the cell corresponds to the current position. The text can be aligned or centered. After the call,
     * the current position moves to the right or to the next line. It is possible to put a link on the text.
     *
     * If automatic page breaking is enabled and the cell goes beyond the limit, a page break is done before outputting.
     *
     * @param int    $w      Cell width. If 0, the cell extends up to the right margin.
     * @param int    $h      Cell height. Default value: 0.
     * @param string $txt    String to print. Default value: empty string.
     * @param int    $border Indicates if borders must be drawn around the cell. The value can be either a number:
     *                       0: no border ; 1: frame
     *                       or a string containing some or all of the following characters (in any order):
     *                       L: left ; T: top ; R: right ; B: bottom
     *                       Default value: 0.
     * @param int    $ln     Indicates where the current position should go after the call. Possible values are:
     *                       0: to the right ; 1: to the beginning of the next line ; 2: below
     *                       Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
     * @param string $align  Allows to center or align the text. Possible values are:
     *                       L or empty string: left align (default value) ; C: center ; R: right align
     * @param bool   $fill   Indicates if the cell background must be painted (true) or transparent (false).
     *                       Default value: false.
     * @param string $link   URL or identifier returned by AddLink().
     */
    public function cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '');

    /**
     * Terminates the PDF document. It is not necessary to call this method explicitly because Output() does it
     * automatically. If the document contains no page, AddPage() is called to prevent from getting an invalid document.
     */
    public function close();

    /**
     * This method is automatically called in case of a fatal error; it simply throws an exception with the provided
     * message.
     *
     * An inherited class may override it to customize the error handling but the method should never return,
     * otherwise the resulting document would probably be invalid.
     *
     * @param string $msg The error message.
     */
    public function error($msg);

    /**
     * This method is used to render the page footer. It is automatically called by AddPage() and Close() and should not
     * be called directly by the application. The implementation in FPDF is empty, so you have to subclass it and
     * override the method if you want a specific processing.
     */
    public function footer();

    /**
     * Returns the current page height.
     */
    public function getPageHeight();

    /**
     * Returns the current page width.
     */
    public function getPageWidth();

    /**
     * Returns the length of a string in user unit. A font must be selected.
     *
     * @param string $s The string whose length is to be computed.
     */
    public function getStringWidth($s);

    /**
     * Returns the abscissa of the current position.
     */
    public function getX();

    /**
     * Returns the ordinate of the current position.
     */
    public function getY();

    /**
     * This method is used to render the page header. It is automatically called by AddPage() and should not be called
     * directly by the application. The implementation in FPDF is empty, so you have to subclass it and override the
     * method if you want a specific processing.
     */
    public function header();

    /**
     * Puts an image. The size it will take on the page can be specified in different ways:
     * - explicit width and height (expressed in user unit or dpi)
     * - one explicit dimension, the other being calculated automatically in order to keep the original proportions
     * - no explicit dimension, in which case the image is put at 96 dpi.
     *
     * Supported formats are JPEG, PNG and GIF. The GD extension is required for GIF.
     *
     * For JPEGs, all flavors are allowed:
     * - gray scales
     * - true colors (24 bits)
     * - CMYK (32 bits)
     *
     * For PNGs, are allowed:
     * - gray scales on at most 8 bits (256 levels)
     * - indexed colors
     * - true colors (24 bits)
     *
     * For GIFs: in case of an animated GIF, only the first frame is displayed.
     *
     * Transparency is supported.
     *
     * The format can be specified explicitly or inferred from the file extension.
     *
     * It is possible to put a link on the image.
     *
     * Remark: if an image is used several times, only one copy is embedded in the file.
     *
     * @param string $file Path or URL of the image.
     * @param int    $x    Abscissa of the upper-left corner. If not specified or equal to null,
     *                     the current abscissa is used.
     * @param int    $y    Ordinate of the upper-left corner. If not specified or equal to null, the current ordinate is
     *                     used; moreover, a page break is triggered first if necessary (in case automatic page breaking
     *                     is enabled) and, after the call, the current ordinate is moved to the bottom of the image.
     * @param int    $w    Width of the image in the page. There are three cases:
     *                     If the value is positive, it represents the width in user unit
     *                     If the value is negative, the absolute value represents the horizontal resolution in dpi
     *                     If the value is not specified or equal to zero, it is automatically calculated
     * @param int    $h    Height of the image in the page. There are three cases:
     *                     If the value is positive, it represents the height in user unit
     *                     If the value is negative, the absolute value represents the vertical resolution in dpi
     *                     If the value is not specified or equal to zero, it is automatically calculated
     * @param string $type Image format. Possible values are (case insensitive): JPG, JPEG, PNG and GIF.
     *                     If not specified, the type is inferred from the file extension.
     * @param string $link URL or identifier returned by AddLink().
     */
    public function image($file, $x = null, $y = null, $w = 0, $h = 0, $type = '', $link = '');

    /**
     * Draws a line between two points.
     *
     * @param int $x1 Abscissa of first point.
     * @param int $y1 Ordinate of first point.
     * @param int $x2 Abscissa of second point.
     * @param int $y2 Ordinate of second point.
     */
    public function line($x1, $y1, $x2, $y2);

    /**
     * Puts a link on a rectangular area of the page. Text or image links are generally put via Cell(), Write() or
     * Image(), but this method can be useful for instance to define a clickable area inside an image.
     *
     * @param int    $x    Abscissa of the upper-left corner of the rectangle.
     * @param int    $y    Ordinate of the upper-left corner of the rectangle.
     * @param int    $w    Width of the rectangle.
     * @param int    $h    Height of the rectangle.
     * @param string $link URL or identifier returned by AddLink().
     */
    public function link($x, $y, $w, $h, $link);

    /**
     * Performs a line break. The current abscissa goes back to the left margin and the ordinate increases by the amount
     * passed in parameter.
     *
     * @param int $h The height of the break. By default, the value equals the height of the last printed cell.
     */
    public function ln($h = null);

    /**
     * This method allows printing text with line breaks. They can be automatic (as soon as the text reaches the right
     * border of the cell) or explicit (via the \n character). As many cells as necessary are output, one below the other.
     *
     * Text can be aligned, centered or justified. The cell block can be framed and the background painted.
     *
     * @param int    $w      Width of cells. If 0, they extend up to the right margin of the page.
     * @param int    $h      Height of cells.
     * @param string $txt    String to print.
     * @param int    $border Indicates if borders must be drawn around the cell block. The value can be either a number:
     *                       0: no border ; 1: frame
     *                       or a string containing some or all of the following characters (in any order):
     *                       L: left ; T: top ; R: right ; B: bottom ; Default value: 0.
     * @param string $align  Sets the text alignment. Possible values are:
     *                       L: left alignment ; C: center ; R: right alignment ; J: justification (default value)
     * @param bool   $fill   Indicates if the cell background must be painted (true) or transparent (false).
     *                       Default value: false.
     */
    public function multiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false);

    /**
     * Send the document to a given destination: browser, file or string. In the case of a browser, the PDF viewer may
     * be used or a download may be forced. The method first calls Close() if necessary to terminate the document.
     *
     * @param string $dest   Destination where to send the document. It can be one of the following:
     *                       I: send the file inline to the browser. The PDF viewer is used if available.
     *                       D: send to the browser and force a file download with the name given by name.
     *                       F: save to a local file with the name given by name (may include a path).
     *                       S: return the document as a string.
     *                       The default value is I.
     * @param string $name   The name of the file. It is ignored in case of destination S.
     *                       The default value is doc.pdf.
     * @param bool   $isUTF8 Indicates if name is encoded in ISO-8859-1 (false) or UTF-8 (true).
     *                       Only used for destinations I and D.
     *                       The default value is false.
     */
    public function output($dest = '', $name = '', $isUTF8 = false);

    /**
     * Returns the current page number.
     */
    public function pageNo();

    /**
     * Outputs a rectangle. It can be drawn (border only), filled (with no border) or both.
     *
     * @param int    $x     Abscissa of upper-left corner.
     * @param int    $y     Ordinate of upper-left corner.
     * @param int    $w     Width.
     * @param int    $h     Height.
     * @param string $style Style of rendering. Possible values are:
     *                      D or empty string: draw. This is the default value. ; F: fill ; DF or FD: draw and fill
     */
    public function rect($x, $y, $w, $h, $style = '');

    /**
     * Defines the author of the document.
     *
     * @param string $author The name of the author.
     * @param bool   $isUTF8 Indicates if the string is encoded in ISO-8859-1 (false) or UTF-8 (true).
     *                       Default value: false.
     */
    public function setAuthor($author, $isUTF8 = false);

    /**
     * Enables or disables the automatic page breaking mode. When enabling, the second parameter is the distance from
     * the bottom of the page that defines the triggering limit. By default, the mode is on and the margin is 2 cm.
     *
     * @param bool $auto   Boolean indicating if mode should be on or off.
     * @param int  $margin Distance from the bottom of the page.
     */
    public function setAutoPageBreak($auto, $margin = 0);

    /**
     * Activates or deactivates page compression. When activated, the internal representation of each page is compressed,
     * which leads to a compression ratio of about 2 for the resulting document. Compression is on by default.
     *
     * @param bool $compress
     */
    public function setCompression($compress);

    /**
     * Defines the creator of the document. This is typically the name of the application that generates the PDF.
     *
     * @param string $creator The name of the creator.
     * @param bool   $isUTF8  Indicates if the string is encoded in ISO-8859-1 (false) or UTF-8 (true).
     *                        Default value: false.
     */
    public function setCreator($creator, $isUTF8 = false);

    /**
     * Defines the way the document is to be displayed by the viewer. The zoom level can be set: pages can be displayed
     * entirely on screen, occupy the full width of the window, use real size, be scaled by a specific zooming factor or
     * use viewer default (configured in the Preferences menu of Adobe Reader). The page layout can be specified too:
     * single at once, continuous display, two columns or viewer default.
     *
     * @param mixed  $zoom   The zoom to use. It can be one of the following string values:
     *                       fullpage: displays the entire page on screen
     *                       fullwidth: uses maximum width of window
     *                       real: uses real size (equivalent to 100% zoom)
     *                       default: uses viewer default mode
     *                       or a number indicating the zooming factor to use.
     * @param string $layout The page layout. Possible values are:
     *                       single: displays one page at once
     *                       continuous: displays pages continuously
     *                       two: displays two pages on two columns
     *                       default: uses viewer default mode
     *                       Default value is default.
     */
    public function setDisplayMode($zoom, $layout = 'default');

    /**
     * Defines the color used for all drawing operations (lines, rectangles and cell borders). It can be expressed in
     * RGB components or gray scale. The method can be called before the first page is created and the value is retained
     * from page to page.
     *
     * @param int $r If g et b are given, red component; if not, indicates the gray level. Value between 0 and 255.
     * @param int $g Green component (between 0 and 255).
     * @param int $b Blue component (between 0 and 255).
     */
    public function setDrawColor($r, $g = null, $b = null);

    /**
     * Defines the color used for all filling operations (filled rectangles and cell backgrounds). It can be expressed
     * in RGB components or gray scale. The method can be called before the first page is created and the value is
     * retained from page to page.
     *
     * @param int $r If g and b are given, red component; if not, indicates the gray level. Value between 0 and 255.
     * @param int $g Green component (between 0 and 255).
     * @param int $b Blue component (between 0 and 255).
     */
    public function setFillColor($r, $g = null, $b = null);

    /**
     * Sets the font used to print character strings. It is mandatory to call this method at least once before printing
     * text or the resulting document would not be valid.
     *
     * The font can be either a standard one or a font added via the AddFont() method. Standard fonts use the Windows
     * encoding cp1252 (Western Europe).
     *
     * The method can be called before the first page is created and the font is kept from page to page.
     *
     * If you just wish to change the current font size, it is simpler to call SetFontSize().
     *
     * Note: the font definition files must be accessible. They are searched successively in:
     * - The directory defined by the FPDF_FONTPATH constant (if this constant is defined)
     * - The font directory located in the same directory as fpdf.php (if it exists)
     * - The directories accessible through include()
     *
     * Example using FPDF_FONTPATH:
     * define('FPDF_FONTPATH','/home/www/font');
     * require('fpdf.php');
     *
     * If the file corresponding to the requested font is not found, the error "Could not include font definition file"
     * is raised.
     *
     * @param string $family Family font. It can be either a name defined by AddFont() or one of the standard families
     *                       (case insensitive):
     *                       Courier (fixed-width)
     *                       Helvetica or Arial (synonymous; sans serif)
     *                       Times (serif)
     *                       Symbol (symbolic)
     *                       ZapfDingbats (symbolic)
     *                       It is also possible to pass an empty string. In that case, the current family is kept.
     * @param string $style  Font style. Possible values are (case insensitive):
     *                       empty string: regular
     *                       B: bold
     *                       I: italic
     *                       U: underline
     *                       or any combination. The default value is regular. Bold and italic styles do not apply to
     *                       Symbol and ZapfDingbats.
     * @param int    $size   Font size in points. The default value is the current size. If no size has been specified
     *                       since the beginning of the document, the value taken is 12.
     */
    public function setFont($family, $style = '', $size = 0);

    /**
     * Defines the size of the current font.
     *
     * @param int $size The size (in points).
     */
    public function setFontSize($size);

    /**
     * Associates keywords with the document, generally in the form 'keyword1 keyword2 ...'.
     *
     * @param string $keywords The list of keywords.
     * @param bool   $isUTF8   Indicates if the string is encoded in ISO-8859-1 (false) or UTF-8 (true).
     *                         Default value: false.
     */
    public function setKeywords($keywords, $isUTF8 = false);

    /**
     * Defines the left margin. The method can be called before creating the first page.
     * If the current abscissa gets out of page, it is brought back to the margin.
     *
     * @param int $margin The margin.
     */
    public function setLeftMargin($margin);

    /**
     * Defines the line width. By default, the value equals 0.2 mm. The method can be called before the first page is
     * created and the value is retained from page to page.
     *
     * @param int $width The width.
     */
    public function setLineWidth($width);

    /**
     * Defines the page and position a link points to.
     *
     * @param int $link The link identifier returned by AddLink().
     * @param int $y    Ordinate of target position; -1 indicates the current position.
     *                  The default value is 0 (top of page).
     * @param int $page Number of target page; -1 indicates the current page. This is the default value.
     */
    public function setLink($link, $y = 0, $page = -1);

    /**
     * Defines the left, top and right margins. By default, they equal 1 cm. Call this method to change them.
     *
     * @param int $left  Left margin.
     * @param int $top   Top margin.
     * @param int $right Right margin. Default value is the left one.
     */
    public function setMargins($left, $top, $right = null);

    /**
     * Defines the right margin. The method can be called before creating the first page.
     *
     * @param int $margin The margin.
     */
    public function setRightMargin($margin);

    /**
     * Defines the subject of the document.
     *
     * @param string $subject The subject.
     * @param bool   $isUTF8  Indicates if the string is encoded in ISO-8859-1 (false) or UTF-8 (true).
     *                        Default value: false.
     */
    public function setSubject($subject, $isUTF8 = false);

    /**
     * Defines the color used for text. It can be expressed in RGB components or gray scale.
     * The method can be called before the first page is created and the value is retained from page to page.
     *
     * @param int $r If g et b are given, red component; if not, indicates the gray level. Value between 0 and 255.
     * @param int $g Green component (between 0 and 255).
     * @param int $b Blue component (between 0 and 255).
     */
    public function setTextColor($r, $g = null, $b = null);

    /**
     * Defines the title of the document.
     *
     * @param string $title  The title.
     * @param bool   $isUTF8 Indicates if the string is encoded in ISO-8859-1 (false) or UTF-8 (true).
     *                       Default value: false.
     */
    public function setTitle($title, $isUTF8 = false);

    /**
     * Defines the top margin. The method can be called before creating the first page.
     *
     * @param int $margin The margin.
     */
    public function setTopMargin($margin);

    /**
     * Defines the abscissa of the current position. If the passed value is negative,
     * it is relative to the right of the page.
     *
     * @param int $x The value of the abscissa.
     */
    public function setX($x);

    /**
     * Defines the abscissa and ordinate of the current position. If the passed values are negative,
     * they are relative respectively to the right and bottom of the page.
     *
     * @param int $x The value of the abscissa.
     * @param int $y The value of the ordinate.
     */
    public function setXY($x, $y);

    /**
     * Sets the ordinate and optionally moves the current abscissa back to the left margin.
     * If the value is negative, it is relative to the bottom of the page.
     *
     * @param int  $y      The value of the ordinate.
     * @param bool $resetX Whether to reset the abscissa. Default value: true.
     */
    public function setY($y, $resetX = true);

    /**
     * Prints a character string. The origin is on the left of the first character, on the baseline.
     * This method allows to place a string precisely on the page, but it is usually easier to use Cell(),
     * MultiCell() or Write() which are the standard methods to print text.
     *
     * @param int    $x   Abscissa of the origin.
     * @param int    $y   Ordinate of the origin.
     * @param string $txt String to print.
     */
    public function text($x, $y, $txt);

    /**
     * This method prints text from the current position. When the right margin is reached (or the \n character is met)
     * a line break occurs and text continues from the left margin. Upon method exit, the current position is left just
     * at the end of the text.
     *
     * It is possible to put a link on the text.
     *
     * @param int    $h    Line height.
     * @param string $txt  String to print.
     * @param string $link URL or identifier returned by AddLink().
     */
    public function write($h, $txt, $link = '');
}
