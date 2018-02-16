<?php
/**
 * Created by PhpStorm.
 * User: l.guerrier
 * Date: 14/02/2018
 * Time: 10:17
 */

namespace Plugin\ChartPart;

class ChartPie extends AbstractPluginChart
{

    public function show()
    {

        $this->loadCanvas();

        $iTotal = 0;
        foreach ($this->tData as $tLine) {
            list($sLabel, $iValue) = $tLine;

            $iTotal += $iValue;
        }

        $this->startScript();

        $diameter = ($this->iWidth / 4) - 10;

        $x = $diameter + 2;
        $y = $diameter + 2;


        $degTotal = 6.3;

        $degStart = 0;

        $this->sHtml .= 'context.beginPath(); ' . "\n";
        $this->sHtml .= 'context.arc(' . $x . ',' . $y . ',' . $diameter . ',0,Math.PI*2);' . "\n";

        $tPct = array();

        foreach ($this->tData as $j => $tLine) {
            list($sLabel, $iValue) = $tLine;

            $pct = ($iValue / $iTotal);
            $degEnd = $pct * $degTotal;
            $degEnd += $degStart;

            $tPct[$j] = $pct * 100;

            $this->partPie($x, $y, $diameter, $degStart, $degEnd, $this->tColor[$j]);

            $degStart = $degEnd;

        }

        foreach ($this->tData as $i => $tLine) {
            list($sLabel, $iValue) = $tLine;

            $x = $this->legendX;
            $y = $i * 20 + $this->legendY;

            $this->rect($x, $y - 8, 10, 10, $this->tColor[$i]);
            $this->text($x + 16, $y, $sLabel . ': ' . $tPct[$i] . '%', '#000', $this->textsizeLegend);

        }

        $this->endScript();

        return $this->sHtml;
    }


}