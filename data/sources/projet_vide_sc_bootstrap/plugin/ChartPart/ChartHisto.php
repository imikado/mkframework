<?php
/**
 * Created by PhpStorm.
 * User: l.guerrier
 * Date: 14/02/2018
 * Time: 10:18
 */

namespace Plugin\ChartPart;


class ChartHisto extends AbstractPluginChart
{

    public function show()
    {
        $this->loadCanvas();

        foreach ($this->tData as $tLine) {
            list($sLabel, $iValue) = $tLine;

            if ($iValue > $this->iMax) {
                $this->iMax = $iValue;
            }
        }
        $iWidthBar = ($this->iWidth - 200) / count($this->tData);
        $iWidthBar = $iWidthBar * 0.8;

        $this->startScript();


        $j = 0;
        foreach ($this->tData as $j => $tLine) {
            list($sLabel, $iValue) = $tLine;

            $iHeight = 1 - (($iValue / $this->iMax) * ($this->iHeight - 24));

            $this->rect($j * ($iWidthBar + 3), $this->iHeight, ($iWidthBar), $iHeight, $this->tColor[$j]);

            $j++;
        }

        //legend
        $i = 0;
        foreach ($this->tData as $j => $tDetail) {
            $sLabel = $tDetail[0];

            $x = $this->legendX;
            $y = $i * 20 + $this->legendY;

            $this->rect($x, $y - 8, 10, 10, $this->tColor[$j]);
            $this->text($x + 16, $y, $sLabel, '#000', $this->textsizeLegend);

            $i++;
        }

        $this->lineFromTo(0, 0, 0, $this->iHeight);
        $this->lineFromTo(0, $this->iHeight, $this->iWidth - 200, $this->iHeight);

        $this->endScript();

        return $this->sHtml;
    }
}