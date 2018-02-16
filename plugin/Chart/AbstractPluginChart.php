<?php

namespace Plugin\Chart;

class AbstractPluginChart
{
    public static $uid = 0;
    protected $tData;
    protected $iWidth;
    protected $height;
    protected $id;
    protected $iMax = 0;

    protected $sHtml;

    protected $tColor;

    protected $iMarginLeft;
    protected $iMinX;
    protected $iMaxX;
    protected $iMinY;
    protected $iMaxY;

    protected $tMarkerY = array();

    protected $paddingX;
    protected $paddingY;

    protected $gridY;

    protected $textsizeLegend;

    protected $legendX = 200;
    protected $legendY = 50;

    protected $stepX = null;
    protected $stepY = null;

    public function __construct($iWidth = null, $iHeight = null)
    {
        $this->iWidth = $iWidth;
        $this->iHeight = $iHeight;

        $this->tColor = array(
            'green',
            'blue',
            'red',
        );

        self::$uid += 1;

        $this->id = 'canvasPluginChart' . self::$uid;

        $this->iMarginLeft = 0;
        $this->textsizeLegend = 12;
    }

    public function setData($tData)
    {
        $this->tData = $tData;
    }

    public function setColorTab($tColor)
    {
        $this->tColor = $tColor;
    }

    public function setMarginLeft($iMarginLeft)
    {
        $this->iMarginLeft = $iMarginLeft;
    }

    public function setMaxX($iMaxX)
    {
        $this->iMaxX = $iMaxX;
    }

    public function setMinX($iMinX)
    {
        $this->iMinX = $iMinX;
    }

    public function setMaxY($iMaxX)
    {
        $this->iMaxY = $iMaxX;
    }

    public function setMinY($iMinX)
    {
        $this->iMinY = $iMinX;
    }

    public function addMarkerY($y, $color = '#444')
    {
        $this->tMarkerY[] = array($y, $color);
    }

    public function setPaddingX($padding)
    {
        $this->paddingX = $padding;
    }

    public function setPaddingY($padding)
    {
        $this->paddingY = $padding;
    }

    public function setGridY($y, $color)
    {
        $this->gridY = array($y, $color);
    }

    public function setTextSizeLegend($size)
    {
        $this->textsizeLegend = $size;
    }

    public function setCoordLegend($x, $y)
    {
        $this->legendX = $x;
        $this->legendY = $y;
    }

    public function setStepX($stepX)
    {
        $this->stepX = $stepX;
    }

    public function setStepY($stepY)
    {
        $this->stepY = $stepY;
    }


    public function loadCanvas()
    {
        $this->sHtml .= '<canvas id="' . $this->id . '" width="' . $this->iWidth . 'px" height="' . $this->iHeight . 'px" ></canvas>';

        $this->startScript();

        $this->sHtml .= 'var canvas = document.getElementById("' . $this->id . '"); ';
        $this->sHtml .= 'var context = canvas.getContext("2d")';

        $this->endScript();
    }

    public function startScript()
    {
        $this->sHtml .= '<script>';
    }

    public function endScript()
    {
        $this->sHtml .= '</script>';
    }

    protected function rect($x, $y, $iWidth, $iHeight, $sColor)
    {
        $this->sHtml .= 'context.beginPath();' . "\n";
        $this->sHtml .= 'context.fillStyle="' . $sColor . '";   ' . "\n";
        $this->sHtml .= 'context.rect(' . $x . ',' . $y . ',' . $iWidth . ',' . $iHeight . ');' . "\n";
        $this->sHtml .= 'context.fill();' . "\n";
    }

    protected function partPie($x, $y, $diameter, $degStart, $degEnd, $sColor)
    {
        $this->sHtml .= 'context.fillStyle="' . $sColor . '";' . "\n";
        $this->sHtml .= 'context.beginPath(); ' . "\n";
        $this->sHtml .= 'context.arc(' . $x . ',' . $y . ',' . $diameter . ',' . $degStart . ',' . $degEnd . ');' . "\n";
        $this->sHtml .= 'context.lineTo(' . $x . ',' . $y . ');' . "\n";
        $this->sHtml .= 'context.fill();' . "\n";
    }

    protected function text($x, $y, $sText, $sColor = 'black', $font = '10px arial')
    {
        $this->sHtml .= 'context.font="' . $font . '";' . "\n";
        $this->sHtml .= 'context.fillStyle="' . $sColor . '";   ' . "\n";
        $this->sHtml .= 'context.fillText("' . $sText . '",' . $x . ',' . $y . ');' . "\n";
    }

    protected function lineFromTo($x, $y, $x2, $y2, $sColor = 'black', $opacity = 1)
    {

        $this->sHtml .= 'context.globalAlpha=' . $opacity . ';' . "\n";

        $this->sHtml .= 'context.strokeStyle="' . $sColor . '";' . "\n";
        $this->sHtml .= 'context.beginPath(); ' . "\n";
        $this->sHtml .= 'context.moveTo(' . $x . ',' . $y . '); ' . "\n";
        $this->sHtml .= 'context.lineTo(' . $x2 . ',' . $y2 . ');' . "\n";
        $this->sHtml .= 'context.stroke();' . "\n";

        $this->sHtml .= 'context.globalAlpha=1;' . "\n";
    }
}