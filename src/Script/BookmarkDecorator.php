<?php

/*
 * FPDF
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace DavidGarciaCat\FPDF\Script;

use DavidGarciaCat\FPDF\FPDF;

class BookmarkDecorator extends FPDF
{
    private $fpdf;

    public function __construct(FPDF $fpdf)
    {
        $this->fpdf = $fpdf;
    }

    protected $outlines = [];
    protected $outlineRoot;

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
            $y = $this->fpdf->getY();
        }

        $this->outlines[] = ['t'=>$txt, 'l'=>$level, 'y'=>($this->fpdf->h - $y) * $this->fpdf->k, 'p'=>$this->fpdf->pageNo()];
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
        $n = $this->fpdf->n + 1;

        foreach ($this->outlines as $i=>$o) {
            $this->fpdf->newObject();
            $this->fpdf->put('<</Title '.$this->fpdf->textString($o['t']));
            $this->fpdf->put('/Parent '.($n + $o['parent']).' 0 R');

            if (isset($o['prev'])) {
                $this->fpdf->put('/Prev '.($n + $o['prev']).' 0 R');
            }

            if (isset($o['next'])) {
                $this->fpdf->put('/Next '.($n + $o['next']).' 0 R');
            }

            if (isset($o['first'])) {
                $this->fpdf->put('/First '.($n + $o['first']).' 0 R');
            }

            if (isset($o['last'])) {
                $this->fpdf->put('/Last '.($n + $o['last']).' 0 R');
            }

            $this->fpdf->put(sprintf('/Dest [%d 0 R /XYZ 0 %.2F null]', $this->fpdf->PageInfo[$o['p']]['n'], $o['y']));
            $this->fpdf->put('/Count 0>>');
            $this->fpdf->put('endobj');
        }

        // Outline root
        $this->fpdf->newObject();
        $this->outlineRoot = $this->fpdf->n;
        $this->fpdf->put('<</Type /Outlines /First '.$n.' 0 R');
        $this->fpdf->put('/Last '.($n + $lru[0]).' 0 R>>');
        $this->fpdf->put('endobj');
    }

    public function putResources()
    {
        parent::putResources();

        $this->fpdf->putbookmarks();
    }

    public function putCatalog()
    {
        parent::putCatalog();

        if (count($this->outlines) > 0) {
            $this->fpdf->put('/Outlines '.$this->outlineRoot.' 0 R');
            $this->fpdf->put('/PageMode /UseOutlines');
        }
    }
}
