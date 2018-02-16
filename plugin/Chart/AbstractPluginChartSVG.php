<?php
/**
 * Created by PhpStorm.
 * User: l.guerrier
 * Date: 14/02/2018
 * Time: 10:24
 */

namespace Plugin\Chart;


class AbstractPluginChartSVG
{
    public static $uid = 0;
    protected $tData;
    protected $iWidth;
    protected $height;
    protected $id;
    protected $iMax = 0;

    protected $sHtml;
    protected $sSvg;

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

        $this->sSvg = null;
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

    }

    public function startScript()
    {

        $this->sSvg .= '<svg width="' . $this->iWidth . 'px" height="' . $this->iHeight . 'px">  ';

        $this->sSvg .= '<style>
		.chartRect{
		cursor:help;
		}
		</style>';

    }

    public function endScript()
    {
        $this->sSvg .= '</svg>';
    }

    protected function rect($x, $y, $iWidth, $iHeight, $sColor, $alt = null)
    {
        $this->sSvg .= '<rect class="chartRect" id="rect' . $x . $y . '" x="' . $x . '" y="' . $y . '" ';
        $this->sSvg .= 'width="' . $iWidth . '" height="' . $iHeight . '" style="fill:' . $sColor . '">';
        $this->sSvg .= '<title>' . $alt . '</title>';
        $this->sSvg .= '</rect>';


    }

    protected function partPie($x, $y, $diameter, $degStart, $degEnd, $sColor)
    {
        $this->sHtml .= 'context.fillStyle="' . $sColor . '";' . "\n";
        $this->sHtml .= 'context.beginPath(); ' . "\n";
        $this->sHtml .= 'context.arc(' . $x . ',' . $y . ',' . $diameter . ',' . $degStart . ',' . $degEnd . ');' . "\n";
        $this->sHtml .= 'context.lineTo(' . $x . ',' . $y . ');' . "\n";
        $this->sHtml .= 'context.fill();' . "\n";


        $this->sSvg .= '<path d="M' . $x . ',' . $y . ' L10,10 A' . $x + ($diameter / 2) . ',' . $y + ($diameter / 2) . ' 0 0,1  z" ';
        $this->sSvg .= 'fill="' . $sColor . '"  />';
    }

    protected function text($x, $y, $sText, $sColor = 'black', $font = '10px arial')
    {
        $this->sHtml .= 'context.font="' . $font . '";' . "\n";
        $this->sHtml .= 'context.fillStyle="' . $sColor . '";   ' . "\n";
        $this->sHtml .= 'context.fillText("' . $sText . '",' . $x . ',' . $y . ');' . "\n";

        $this->sSvg .= '<text x="' . $x . '" y="' . $y . '" fill="' . $sColor . '">' . $sText . '</text>';
    }

    protected function lineFromTo($x, $y, $x2, $y2, $sColor = 'black', $opacity = 1)
    {

        $this->sSvg .= '<line x1="' . $x . '" y1="' . $y . '" x2="' . $x2 . '" y2="' . $y2 . '" ';
        $this->sSvg .= 'style="stroke:' . $sColor . ';stroke-width:2" stroke-opacity="' . $opacity . '" />';
    }
}