<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF\Script;

use DavidGarciaCat\FPDF\FPDFInterface;

class SectorDecorator implements FPDFInterface
{
    /**
     * @var FPDFInterface
     */
    private $baseFpdf;

    /**
     * SectorDecorator constructor.
     *
     * @param FPDFInterface $fpdf
     */
    public function __construct(FPDFInterface $fpdf)
    {
        $this->baseFpdf = $fpdf;
    }

    // ----

    public function sector($xc, $yc, $r, $a, $b, $style = 'FD', $cw = true, $o = 90)
    {
        $d0 = $a - $b;
        if ($cw) {
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        } else {
            $b += $o;
            $a += $o;
        }
        while ($a < 0) {
            $a += 360;
        }
        while ($a > 360) {
            $a -= 360;
        }
        while ($b < 0) {
            $b += 360;
        }
        while ($b > 360) {
            $b -= 360;
        }
        if ($a > $b) {
            $b += 360;
        }
        $b = $b / 360 * 2 * M_PI;
        $a = $a / 360 * 2 * M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0) {
            $d = 2 * M_PI;
        }
        $k = $this->baseFpdf->k;
        $hp = $this->baseFpdf->h;
        if (sin($d / 2)) {
            $MyArc = 4 / 3 * (1 - cos($d / 2)) / sin($d / 2) * $r;
        } else {
            $MyArc = 0;
        }
        //first put the center
        $this->baseFpdf->out(sprintf('%.2F %.2F m', ($xc) * $k, ($hp - $yc) * $k));
        //put the first point
        $this->baseFpdf->out(sprintf('%.2F %.2F l', ($xc + $r * cos($a)) * $k, (($hp - ($yc - $r * sin($a))) * $k)));
        //draw the arc
        if ($d < M_PI / 2) {
            $this->arc(
                $xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
        } else {
            $b = $a + $d / 4;
            $MyArc = 4 / 3 * (1 - cos($d / 8)) / sin($d / 8) * $r;
            $this->arc(
                $xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->arc(
                $xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->arc(
                $xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
            $a = $b;
            $b = $a + $d / 4;
            $this->arc(
                $xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a),
                $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a),
                $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2),
                $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2),
                $xc + $r * cos($b),
                $yc - $r * sin($b)
            );
        }
        //terminate drawing
        if ($style == 'F') {
            $op = 'f';
        } elseif ($style == 'FD' || $style == 'DF') {
            $op = 'b';
        } else {
            $op = 's';
        }
        $this->baseFpdf->out($op);
    }

    public function arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->baseFpdf->h;

        $this->baseFpdf->out(
            sprintf(
                '%.2F %.2F %.2F %.2F %.2F %.2F c',
                $x1 * $this->baseFpdf->k,
                ($h - $y1) * $this->baseFpdf->k,
                $x2 * $this->baseFpdf->k,
                ($h - $y2) * $this->baseFpdf->k,
                $x3 * $this->baseFpdf->k,
                ($h - $y3) * $this->baseFpdf->k
            )
        );
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
