<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF;

class FPDF extends FPDFBase implements FPDFInterface
{
    /**
     * FPDF constructor.
     *
     * @param string $orientation
     * @param string $unit
     * @param string $size
     */
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
    }

    public function acceptPageBreak()
    {
        return parent::acceptPageBreak();
    }

    public function addFont($family, $style = '', $file = '')
    {
        parent::addFont($family, $style, $file);
    }

    public function addLink()
    {
        return parent::addLink();
    }

    public function addPage($orientation = '', $size = '', $rotation = 0)
    {
        parent::addPage($orientation, $size, $rotation);
    }

    public function aliasNbPages($alias = '{nb}')
    {
        parent::aliasNbPages($alias);
    }

    public function cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    public function close()
    {
        parent::close();
    }

    public function error($msg)
    {
        parent::error($msg);
    }

    public function footer()
    {
    }

    public function getPageHeight()
    {
        return parent::getPageHeight();
    }

    public function getPageWidth()
    {
        return parent::getPageWidth();
    }

    public function getStringWidth($s)
    {
        return parent::getStringWidth($s);
    }

    public function getX()
    {
        return parent::getX();
    }

    public function getY()
    {
        return parent::getY();
    }

    public function header()
    {
    }

    public function image($file, $x = null, $y = null, $w = 0, $h = 0, $type = '', $link = '')
    {
        parent::image($file, $x, $y, $w, $h, $type, $link);
    }

    public function line($x1, $y1, $x2, $y2)
    {
        parent::line($x1, $y1, $x2, $y2);
    }

    public function link($x, $y, $w, $h, $link)
    {
        parent::link($x, $y, $w, $h, $link);
    }

    public function ln($h = null)
    {
        parent::ln($h);
    }

    public function multiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        parent::multiCell($w, $h, $txt, $border, $align, $fill);
    }

    public function output($dest = '', $name = '', $isUTF8 = false)
    {
        return parent::output($dest, $name, $isUTF8);
    }

    public function pageNo()
    {
        return parent::pageNo();
    }

    public function rect($x, $y, $w, $h, $style = '')
    {
        parent::rect($x, $y, $w, $h, $style);
    }

    public function setAuthor($author, $isUTF8 = false)
    {
        parent::setAuthor($author, $isUTF8);
    }

    public function setAutoPageBreak($auto, $margin = 0)
    {
        parent::setAutoPageBreak($auto, $margin);
    }

    public function setCompression($compress)
    {
        parent::setCompression($compress);
    }

    public function setCreator($creator, $isUTF8 = false)
    {
        parent::setCreator($creator, $isUTF8);
    }

    public function setDisplayMode($zoom, $layout = 'default')
    {
        parent::setDisplayMode($zoom, $layout);
    }

    public function setDrawColor($r, $g = null, $b = null)
    {
        parent::setDrawColor($r, $g, $b);
    }

    public function setFillColor($r, $g = null, $b = null)
    {
        parent::setFillColor($r, $g, $b);
    }

    public function setFont($family, $style = '', $size = 0)
    {
        parent::setFont($family, $style, $size);
    }

    public function setFontSize($size)
    {
        parent::setFontSize($size);
    }

    public function setKeywords($keywords, $isUTF8 = false)
    {
        parent::setKeywords($keywords, $isUTF8);
    }

    public function setLeftMargin($margin)
    {
        parent::setLeftMargin($margin);
    }

    public function setLineWidth($width)
    {
        parent::setLineWidth($width);
    }

    public function setLink($link, $y = 0, $page = -1)
    {
        parent::setLink($link, $y, $page);
    }

    public function setMargins($left, $top, $right = null)
    {
        parent::setMargins($left, $top, $right);
    }

    public function setRightMargin($margin)
    {
        parent::setRightMargin($margin);
    }

    public function setSubject($subject, $isUTF8 = false)
    {
        parent::setSubject($subject, $isUTF8);
    }

    public function setTextColor($r, $g = null, $b = null)
    {
        parent::setTextColor($r, $g, $b);
    }

    public function setTitle($title, $isUTF8 = false)
    {
        parent::setTitle($title, $isUTF8);
    }

    public function setTopMargin($margin)
    {
        parent::setTopMargin($margin);
    }

    public function setX($x)
    {
        parent::setX($x);
    }

    public function setXY($x, $y)
    {
        parent::setXY($x, $y);
    }

    public function setY($y, $resetX = true)
    {
        parent::setY($y, $resetX);
    }

    public function text($x, $y, $txt)
    {
        parent::text($x, $y, $txt);
    }

    public function write($h, $txt, $link = '')
    {
        parent::write($h, $txt, $link);
    }
}
