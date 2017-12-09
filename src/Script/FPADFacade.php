<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF\Script;

use DavidGarciaCat\FPDF\FPDF;
use DavidGarciaCat\FPDF\FPDFInterface;

class FPADFacade implements FPDFInterface
{
    /**
     * @var FPDF
     */
    protected $baseFpdf;

    /**
     * FPADFacade constructor.
     *
     * @param FPDF $baseFpdf
     */
    public function __construct(FPDF $baseFpdf)
    {
        $this->baseFpdf = $baseFpdf;
    }

    public function acceptPageBreak()
    {
        $this->baseFpdf->acceptPageBreak();
    }

    public function addFont($family, $style = '', $file = '')
    {
        $this->baseFpdf->addFont($family, $style, $file);
    }

    public function addLink()
    {
        $this->baseFpdf->addLink();
    }

    public function addPage($orientation = '', $size = '', $rotation = 0)
    {
        $this->baseFpdf->addPage($orientation, $size, $rotation);
    }

    public function aliasNbPages($alias = '{nb}')
    {
        $this->baseFpdf->aliasNbPages($alias);
    }

    public function cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $this->baseFpdf->cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    public function close()
    {
        $this->baseFpdf->close();
    }

    public function error($msg)
    {
        $this->baseFpdf->error($msg);
    }

    public function footer()
    {
        $this->baseFpdf->footer();
    }

    public function getPageHeight()
    {
        return $this->baseFpdf->getPageHeight();
    }

    public function getPageWidth()
    {
        return $this->baseFpdf->getPageWidth();
    }

    public function getStringWidth($s)
    {
        return $this->baseFpdf->getStringWidth();
    }

    public function getX()
    {
        return $this->baseFpdf->getX();
    }

    public function getY()
    {
        return $this->baseFpdf->getY();
    }

    public function header()
    {
        $this->baseFpdf->header();
    }

    public function image($file, $x = null, $y = null, $w = 0, $h = 0, $type = '', $link = '')
    {
        $this->baseFpdf->image($file, $x, $y, $w, $h, $type, $link);
    }

    public function line($x1, $y1, $x2, $y2)
    {
        $this->baseFpdf->line($x1, $y1, $x2, $y2);
    }

    public function link($x, $y, $w, $h, $link)
    {
        $this->baseFpdf->link($x, $y, $w, $h, $link);
    }

    public function ln($h = null)
    {
        $this->baseFpdf->ln($h = null);
    }

    public function multiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        $this->baseFpdf->multiCell($w, $h, $txt, $border, $align, $fill);
    }

    public function output($dest = '', $name = '', $isUTF8 = false)
    {
        $this->baseFpdf->output($dest, $name, $isUTF8);
    }

    public function pageNo()
    {
        $this->baseFpdf->pageNo();
    }

    public function rect($x, $y, $w, $h, $style = '')
    {
        $this->baseFpdf->rect($x, $y, $w, $h, $style);
    }

    public function setAuthor($author, $isUTF8 = false)
    {
        $this->baseFpdf->setAuthor($author, $isUTF8);
    }

    public function setAutoPageBreak($auto, $margin = 0)
    {
        $this->baseFpdf->setAutoPageBreak($auto, $margin);
    }

    public function setCompression($compress)
    {
        $this->baseFpdf->setCompression($compress);
    }

    public function setCreator($creator, $isUTF8 = false)
    {
        $this->baseFpdf->setCreator($creator, $isUTF8);
    }

    public function setDisplayMode($zoom, $layout = 'default')
    {
        $this->baseFpdf->setDisplayMode($zoom, $layout);
    }

    public function setDrawColor($r, $g = null, $b = null)
    {
        $this->baseFpdf->setDrawColor($r, $g, $b);
    }

    public function setFillColor($r, $g = null, $b = null)
    {
        $this->baseFpdf->setFillColor($r, $g, $b);
    }

    public function setFont($family, $style = '', $size = 0)
    {
        $this->baseFpdf->setFont($family, $style, $size);
    }

    public function setFontSize($size)
    {
        $this->baseFpdf->setFontSize($size);
    }

    public function setKeywords($keywords, $isUTF8 = false)
    {
        $this->baseFpdf->setKeywords($keywords, $isUTF8);
    }

    public function setLeftMargin($margin)
    {
        $this->baseFpdf->setLeftMargin($margin);
    }

    public function setLineWidth($width)
    {
        $this->baseFpdf->setLineWidth($width);
    }

    public function setLink($link, $y = 0, $page = -1)
    {
        $this->baseFpdf->setLink($link, $y, $page);
    }

    public function setMargins($left, $top, $right = null)
    {
        $this->baseFpdf->setMargins($left, $top, $right);
    }

    public function setRightMargin($margin)
    {
        $this->baseFpdf->setRightMargin($margin);
    }

    public function setSubject($subject, $isUTF8 = false)
    {
        $this->baseFpdf->setSubject($subject, $isUTF8);
    }

    public function setTextColor($r, $g = null, $b = null)
    {
        $this->baseFpdf->setTextColor($r, $g, $b);
    }

    public function setTitle($title, $isUTF8 = false)
    {
        $this->baseFpdf->setTitle($title, $isUTF8);
    }

    public function setTopMargin($margin)
    {
        $this->baseFpdf->setTopMargin($margin);
    }

    public function setX($x)
    {
        $this->baseFpdf->setX($x);
    }

    public function setXY($x, $y)
    {
        $this->baseFpdf->setXY($x, $y);
    }

    public function setY($y, $resetX = true)
    {
        $this->baseFpdf->setY($y, $resetX);
    }

    public function text($x, $y, $txt)
    {
        $this->baseFpdf->text($x, $y, $txt);
    }

    public function write($h, $txt, $link = '')
    {
        $this->baseFpdf->write($h, $txt, $link);
    }
}
