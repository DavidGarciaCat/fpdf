<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF\Script;

use DavidGarciaCat\FPDF\Exception\FPDFException;
use DavidGarciaCat\FPDF\FPDFInterface;

class DiagramDecorator implements FPDFInterface
{
    /**
     * @var FPDFInterface
     */
    private $baseFpdf;

    /**
     * SectorDecorator constructor.
     *
     * @param FPDFInterface $fpdf
     *
     * @throws FPDFException
     */
    public function __construct(FPDFInterface $fpdf)
    {
        if (!$fpdf instanceof SectorDecorator) {
            throw new FPDFException('Diagram Decorator requires a FPDF Sector Decorator');
        }

        $this->baseFpdf = $fpdf;
    }

    // ----

    private $legends;
    private $wLegend;
    private $sum;
    private $NbVal;

    public function pieChart($w, $h, $data, $format, $colors = null)
    {
        $this->setFont('Courier', '', 10);
        $this->setLegends($data, $format);

        $XPage = $this->baseFpdf->getX();
        $YPage = $this->baseFpdf->getY();

        $margin = 2;
        $hLegend = 5;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
        $radius = floor($radius / 2);
        $XDiag = $XPage + $margin + $radius;
        $YDiag = $YPage + $margin + $radius;

        if ($colors == null) {
            for ($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = [$gray, $gray, $gray];
            }
        }

        //Sectors
        $this->setLineWidth(0.2);
        $angleStart = 0;
        $angleEnd = 0;
        $i = 0;

        foreach ($data as $val) {
            $angle = ($val * 360) / floatval($this->sum);

            if ($angle != 0) {
                $angleEnd = $angleStart + $angle;
                $this->setFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
                $this->baseFpdf->sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                $angleStart += $angle;
            }

            $i++;
        }

        //Legends
        $this->setFont('Courier', '', 10);

        $x1 = $XPage + 2 * $radius + 4 * $margin;
        $x2 = $x1 + $hLegend + $margin;
        $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal * ($hLegend + $margin)) / 2;

        for ($i = 0; $i < $this->NbVal; $i++) {
            $this->setFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
            $this->rect($x1, $y1, $hLegend, $hLegend, 'DF');
            $this->setXY($x2, $y1);
            $this->cell(0, $hLegend, $this->legends[$i]);
            $y1 += $hLegend + $margin;
        }
    }

    public function barDiagram($w, $h, $data, $format, $color = null, $maxVal = 0, $nbDiv = 4)
    {
        $this->setFont('Courier', '', 10);
        $this->setLegends($data, $format);

        $XPage = $this->getX();
        $YPage = $this->getY();

        $margin = 2;
        $YDiag = $YPage + $margin;
        $hDiag = floor($h - $margin * 2);
        $XDiag = $XPage + $margin * 2 + $this->wLegend;
        $lDiag = floor($w - $margin * 3 - $this->wLegend);

        if ($color == null) {
            $color = [155, 155, 155];
        }

        if ($maxVal == 0) {
            $maxVal = max($data);
        }

        $valIndRepere = ceil($maxVal / $nbDiv);
        $maxVal = $valIndRepere * $nbDiv;
        $lRepere = floor($lDiag / $nbDiv);
        $lDiag = $lRepere * $nbDiv;
        $unit = $lDiag / $maxVal;
        $hBar = floor($hDiag / ($this->NbVal + 1));
        $hDiag = $hBar * ($this->NbVal + 1);
        $eBaton = floor($hBar * 80 / 100);

        $this->setLineWidth(0.2);
        $this->rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->setFont('Courier', '', 10);
        $this->setFillColor($color[0], $color[1], $color[2]);

        $i = 0;

        foreach ($data as $val) {
            //Bar
            $xval = $XDiag;
            $lval = (int) ($val * $unit);
            $yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
            $hval = $eBaton;
            $this->rect($xval, $yval, $lval, $hval, 'DF');
            //Legend
            $this->setXY(0, $yval);
            $this->cell($xval - $margin, $hval, $this->legends[$i], 0, 0, 'R');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) {
            $xpos = $XDiag + $lRepere * $i;
            $this->line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
            $val = $i * $valIndRepere;
            $xpos = $XDiag + $lRepere * $i - $this->getStringWidth($val) / 2;
            $ypos = $YDiag + $hDiag - $margin;
            $this->text($xpos, $ypos, $val);
        }
    }

    public function setLegends($data, $format)
    {
        $this->legends = [];
        $this->wLegend = 0;
        $this->sum = array_sum($data);
        $this->NbVal = count($data);

        foreach ($data as $l=>$val) {
            $p = sprintf('%.2f', $val / $this->sum * 100).'%';
            $legend = str_replace(['%l', '%v', '%p'], [$l, $val, $p], $format);
            $this->legends[] = $legend;
            $this->wLegend = max($this->getStringWidth($legend), $this->wLegend);
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
