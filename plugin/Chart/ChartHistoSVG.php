<?php
/**
 * Created by PhpStorm.
 * User: l.guerrier
 * Date: 14/02/2018
 * Time: 10:25
 */

namespace Plugin\Chart;


class ChartHistoSVG extends AbstractPluginChartSVG
{

    public function show()
    {

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

            $iHeight = (($iValue / $this->iMax) * ($this->iHeight - 24));

            $this->rect($j * ($iWidthBar + 3), $this->iHeight - $iHeight, ($iWidthBar), $iHeight, $this->tColor[$j], $iValue);

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

        return $this->sSvg;
    }
}