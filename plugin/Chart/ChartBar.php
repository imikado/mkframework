<?php
/**
 * Created by PhpStorm.
 * User: l.guerrier
 * Date: 14/02/2018
 * Time: 10:19
 */

namespace Plugin\Chart;

class ChartBar extends AbstractPluginChart
{

    private $tmpGroup;


    public function show()
    {
        $this->loadCanvas();

        $iMaxX = 0;
        $iMaxY = 0;

        $iMinX = '';
        $iMinY = '';


        foreach ($this->tData as $sGroup => $tDetail) {
            foreach ($tDetail['tPoint'] as $tPoint) {

                list($x, $y) = $tPoint;

                if ($iMaxX < $x) {
                    $iMaxX = $x;
                }
                if ($iMaxY < $y) {
                    $iMaxY = $y;
                }

                if ($iMinX == '' or $iMinX > $x) {
                    $iMinX = $x;
                }
                if ($iMinY == '' or $iMinY > $y) {
                    $iMinY = $y;
                }

            }
        }


        if ($this->iMaxX) {
            $iMaxX = $this->iMaxX;
        }
        if ($this->iMinX) {
            $iMinX = $this->iMinX;
        }
        if ($this->iMaxY) {
            $iMaxY = $this->iMaxY;
        }
        if ($this->iMinY != null) {
            $iMinY = $this->iMinY;
        }

        if ($this->paddingX) {
            $iMinX -= $this->paddingX;
            $iMaxX += $this->paddingX;
        }
        if ($this->paddingY) {
            $iMinY -= $this->paddingY;
            $iMaxY += $this->paddingY;
        }

        $this->startScript();

        $iHeight = $this->iHeight - 10;
        $iWidth = $this->iWidth - 200 - $this->iMarginLeft - (4);

        if ($this->gridY) {
            $step = $this->gridY[0];
            $color = $this->gridY[1];

            for ($y = $iMinY; $y < $iMaxY; $y += $step) {

                $y2 = (1 - ($y - $iMinY) / ($iMaxY - $iMinY)) * $iHeight;
                $this->lineFromTo($this->iMarginLeft, $y2, $this->iWidth - 200, $y2, $color, 0.5);
            }

        }

        if ($this->tMarkerY) {
            foreach ($this->tMarkerY as $tLineY) {

                list($y, $color) = $tLineY;
                $y = (1 - ($y - $iMinY) / ($iMaxY - $iMinY)) * $iHeight;

                $this->lineFromTo($this->iMarginLeft, $y, $this->iWidth - 200, $y, $color, 0.5);
            }
        }

        $k = 0;
        if ($this->tData) {
            foreach ($this->tData as $sGroup => $tDetail) {
                $lastX = null;
                $lastY = null;
                foreach ($tDetail['tPoint'] as $tPoint) {

                    list($x, $y) = $tPoint;

                    $x2 = (($x - $iMinX) / ($iMaxX - $iMinX)) * $iWidth + $this->iMarginLeft;
                    $y2 = (1 - ($y - $iMinY) / ($iMaxY - $iMinY)) * $iHeight;

                    $x3 = $x2;
                    $y3 = $y2 - 3;

                    if ($x3 <= 0) {
                        $x3 = 0;
                    }
                    if ($y3 <= 0) {
                        $y3 = 0;
                    }

                    $this->rect($x3 + ($k * 8), $y3, 6, $iHeight - $y3, $tDetail['color']);


                    $lastX = $x2;
                    $lastY = $y2;

                }
                $k++;
            }
        }

        //legend
        $i = 0;
        foreach ($this->tData as $sGroup => $tDetail) {
            $sLabel = $sGroup;

            $x = $this->legendX;
            $y = $i * 20 + $this->legendY;

            $this->rect($x, $y - 8, 10, 10, $tDetail['color']);
            $this->text($x + 16, $y, $sLabel, '#000', $this->textsizeLegend);

            $i++;
        }

        $this->lineFromTo($this->iMarginLeft, 0, $this->iMarginLeft, $this->iHeight - 10);
        $this->lineFromTo($this->iMarginLeft, $this->iHeight - 10, $this->iWidth - 200, $this->iHeight - 10);


        //step
        if ($this->stepX !== null) {
            for ($x = ($iMinX); $x < $iMaxX; $x += $this->stepX) {
                $x2 = (($x - $iMinX) / ($iMaxX - $iMinX)) * $iWidth + $this->iMarginLeft;

                $this->lineFromTo($x2, ($this->iHeight - 10), $x2, ($this->iHeight - 5));

                $this->text($x2 + 2, ($this->iHeight), $x);
            }
        } else {
            $this->text(0, $this->iHeight, $iMinX);

            $this->text($this->iWidth - 200, $this->iHeight, $iMaxX);
        }

        //step
        if ($this->stepY !== null) {
            for ($y = ($iMinY); $y < $iMaxY; $y += $this->stepY) {
                $y2 = (1 - ($y - $iMinY) / ($iMaxY - $iMinY)) * $iHeight;

                $this->lineFromTo($this->iMarginLeft - 5, $y2, $this->iMarginLeft, $y2);

                $this->text(0, $y2, $y);
            }
        } else {
            $this->text(0, 10, $iMaxY);
            $this->text(0, $this->iHeight - 10, $iMinY);
        }


        $this->endScript();

        return $this->sHtml;
    }


    public function addGroup($sLabel, $sColor)
    {
        $this->tmpGroup = $sLabel;

        $this->tData[$this->tmpGroup]['label'] = $sLabel;
        $this->tData[$this->tmpGroup]['color'] = $sColor;
    }

    public function addPoint($x, $y)
    {
        $this->tData[$this->tmpGroup]['tPoint'][] = array($x, $y);
    }

}