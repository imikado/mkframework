<?php
/*
This file is part of Mkframework.

Mkframework is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License.

Mkframework is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with Mkframework.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 * plugin_chart classe pour generer des graphiques
 * @author Mika
 * @link http://mkf.mkdevs.com/
 */
class Chart
{

    const PIE = 1;
    const HISTO = 2;
    const LINES = 3;
    const BAR = 4;

    private $iWidth;

    private $oChart;

    public function __construct($sType, $iWidth = null, $iHeight = null)
    {
        $this->iWidth = $iWidth;
        $this->iHeight = $iHeight;

        if ($sType == self::PIE) {
            $this->oChart = new Plugin\Chart\ChartPie($this->iWidth, $this->iHeight);
        } else if ($sType == self::HISTO) {
            $this->oChart = new Plugin\Chart\ChartHisto($this->iWidth, $this->iHeight);
        } else if ($sType == self::LINES) {
            $this->oChart = new Plugin\Chart\ChartLine($this->iWidth, $this->iHeight);
        } else if ($sType == self::BAR) {
            $this->oChart = new Plugin\Chart\ChartBar($this->iWidth, $this->iHeight);
        } else {
            throw new Exception('sType non reconnu, attendu: (PIE,HISTO,LINES,BAR)');
        }
    }

    /**
     * charge les donnees du graphique
     * @access public
     * @param array $tData : tableau de donnees
     */
    public function setData($tData)
    {
        $this->oChart->setData($tData);
    }

    /**
     * retourne le code du graphique a afficher
     * @access public
     * @return string retourne le code du graphique
     */
    public function show()
    {
        return $this->oChart->show();
    }

    /**
     * ajoute un groupe au graphique
     * @access public
     * @param string $sLabel libelle du groupe
     * @param string $sColor couleur utilise
     */
    public function addGroup($sLabel, $sColor)
    {
        $this->oChart->addGroup($sLabel, $sColor);
    }

    /**
     * ajoute un point au graphique
     * @access public
     * @param number $x coordonnee x du point
     * @param number $y coordonnee y du point
     */
    public function addPoint($x, $y)
    {
        $this->oChart->addPoint($x, $y);
    }

    public function setMarginLeft($x)
    {
        $this->oChart->setMarginLeft($x);
    }

    public function setMaxX($x)
    {
        $this->oChart->setMaxX($x);
    }

    public function setMinX($x)
    {
        $this->oChart->setMinX($x);
    }

    public function setMaxY($x)
    {
        $this->oChart->setMaxY($x);
    }

    public function setMinY($x)
    {
        $this->oChart->setMinY($x);
    }

    public function addMarkerY($y, $color = '#ccc')
    {
        $this->oChart->addMarkerY($y, $color);
    }

    public function setPaddingX($padding)
    {
        $this->oChart->setPaddingX($padding);
    }

    public function setPaddingY($padding)
    {
        $this->oChart->setPaddingY($padding);
    }

    public function setGridY($y, $color)
    {
        $this->oChart->setGridY($y, $color);
    }

    public function setTextSizeLegend($size)
    {
        $this->oChart->setTextSizeLegend($size);
    }

    public function setCoordLegend($x, $y)
    {
        $this->oChart->setCoordLegend($x, $y);
    }

    public function setStepX($stepX)
    {
        $this->oChart->setStepX($stepX);
    }

    public function setStepY($stepY)
    {
        $this->oChart->setStepY($stepY);
    }

}
