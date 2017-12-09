<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF\Script;

/**
 * This extension adds bookmark support.
 */
class BookmarkDecorator extends FPDFFacade
{
    protected $outlines = [];

    protected $outlineRoot;

    /**
     * BookmarkDecorator constructor.
     *
     * @param FPDFFacade $fpdf
     */
    public function __construct(FPDFFacade $fpdf)
    {
        parent::__construct($fpdf->baseFpdf);
    }

    /**
     * @param string $txt    The bookmark title.
     * @param bool   $isUTF8 Tndicates if the title is encoded in ISO-8859-1 (false) or UTF-8 (true). Default value: false.
     * @param int    $level  The bookmark level (0 is top level, 1 is just below, and so on). Default value: 0.
     * @param int    $y      The y position of the bookmark destination in the current page. -1 means the current position. Default value: 0.
     */
    public function Bookmark($txt, $isUTF8 = false, $level = 0, $y = 0)
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
}
