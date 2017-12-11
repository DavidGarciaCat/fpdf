<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF\Script;

use DavidGarciaCat\FPDF\FPDFInterface;

/**
 * This extension adds bookmark support.
 */
class BookmarkDecorator implements FPDFInterface
{
    /**
     * @var FPDFInterface
     */
    private $baseFpdf;

    /**
     * @var array
     */
    protected $outlines = [];

    /**
     * @var int
     */
    protected $outlineRoot;

    /**
     * BookmarkDecorator constructor.
     *
     * @param FPDFInterface $fpdf
     */
    public function __construct(FPDFInterface $fpdf)
    {
        $this->baseFpdf = $fpdf;
    }

    /**
     * @param string $txt    The bookmark title.
     * @param bool   $isUTF8 Tndicates if the title is encoded in ISO-8859-1 (false) or UTF-8 (true). Default value: false.
     * @param int    $level  The bookmark level (0 is top level, 1 is just below, and so on). Default value: 0.
     * @param int    $y      The y position of the bookmark destination in the current page. -1 means the current position. Default value: 0.
     */
    public function bookmark($txt, $isUTF8 = false, $level = 0, $y = 0)
    {
        if (!$isUTF8) {
            $txt = utf8_encode($txt);
        }

        if ($y == -1) {
            $y = $this->baseFpdf->getY();
        }

        $this->outlines[] = ['t'=>$txt, 'l'=>$level, 'y'=>($this->baseFpdf->h - $y) * $this->baseFpdf->k, 'p'=>$this->baseFpdf->pageNo()];
    }

    public function putBookmarks()
    {
        $nb = count($this->outlines);

        if ($nb == 0) {
            return;
        }

        $lru = [];
        $level = 0;

        foreach ($this->outlines as $i=>$o) {
            if ($o['l'] > 0) {
                $parent = $lru[$o['l'] - 1];
                // Set parent and last pointers
                $this->outlines[$i]['parent'] = $parent;
                $this->outlines[$parent]['last'] = $i;

                if ($o['l'] > $level) {
                    // Level increasing: set first pointer
                    $this->outlines[$parent]['first'] = $i;
                }
            } else {
                $this->outlines[$i]['parent'] = $nb;
            }

            if ($o['l'] <= $level && $i > 0) {
                // Set prev and next pointers
                $prev = $lru[$o['l']];

                $this->outlines[$prev]['next'] = $i;
                $this->outlines[$i]['prev'] = $prev;
            }

            $lru[$o['l']] = $i;
            $level = $o['l'];
        }

        // Outline items
        $n = $this->baseFpdf->n + 1;

        foreach ($this->outlines as $i=>$o) {
            $this->baseFpdf->newObject();
            $this->baseFpdf->put('<</Title '.$this->baseFpdf->textString($o['t']));
            $this->baseFpdf->put('/Parent '.($n + $o['parent']).' 0 R');

            if (isset($o['prev'])) {
                $this->baseFpdf->put('/Prev '.($n + $o['prev']).' 0 R');
            }

            if (isset($o['next'])) {
                $this->baseFpdf->put('/Next '.($n + $o['next']).' 0 R');
            }

            if (isset($o['first'])) {
                $this->baseFpdf->put('/First '.($n + $o['first']).' 0 R');
            }

            if (isset($o['last'])) {
                $this->baseFpdf->put('/Last '.($n + $o['last']).' 0 R');
            }

            $this->baseFpdf->put(sprintf('/Dest [%d 0 R /XYZ 0 %.2F null]', $this->baseFpdf->PageInfo[$o['p']]['n'], $o['y']));
            $this->baseFpdf->put('/Count 0>>');
            $this->baseFpdf->put('endobj');
        }

        // Outline root
        $this->baseFpdf->newObject();
        $this->outlineRoot = $this->baseFpdf->n;
        $this->baseFpdf->put('<</Type /Outlines /First '.$n.' 0 R');
        $this->baseFpdf->put('/Last '.($n + $lru[0]).' 0 R>>');
        $this->baseFpdf->put('endobj');
    }

    public function putResources()
    {
        $this->baseFpdf->putResources();

        $this->putbookmarks();
    }

    public function putCatalog()
    {
        $this->baseFpdf->putCatalog();

        if (count($this->outlines) > 0) {
            $this->baseFpdf->put('/Outlines '.$this->outlineRoot.' 0 R');
            $this->baseFpdf->put('/PageMode /UseOutlines');
        }
    }

    // ----

    public function acceptPageBreak()
    {
        return $this->baseFpdf->acceptPageBreak();
    }

    public function addFont($family, $style = '', $file = '')
    {
        $this->baseFpdf->addFont($family, $style, $file);
    }

    public function addLink()
    {
        return $this->baseFpdf->addLink();
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
        return $this->baseFpdf->getStringWidth($s);
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
        $this->baseFpdf->ln($h);
    }

    public function multiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        $this->baseFpdf->multiCell($w, $h, $txt, $border, $align, $fill);
    }

    public function output($dest = '', $name = '', $isUTF8 = false)
    {
        return $this->baseFpdf->output($dest, $name, $isUTF8);
    }

    public function pageNo()
    {
        return $this->baseFpdf->pageNo();
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
