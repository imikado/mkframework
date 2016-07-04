<?php
require_once('bootstrap.php');
/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class #model_exampleTest# extends PHPUnit_Framework_TestCase
{

	 public function run(PHPUnit_Framework_TestResult $result = NULL)
    {
        $this->setPreserveGlobalState(false);
        return parent::run($result);
    }

    /*
    Exple avec un model author qui contiendrait les methodes suivantes:
    ---debut fichier model_author.php
    class model_author extends abstract_model{
		(...)
		public function findAll(){
			return $this->findMany('SELECT * FROM '.$this->sTable);
		}
		public function getSelect(){
			$tab=$this->findAll();
			$tSelect=array();
			if($tab){
			foreach($tab as $oRow){
				$tSelect[ $oRow->pkey ]=$oRow->firstname;
			}
			}
			return $tSelect;
		}
	}
	---fin du fichier model_author.php

	public function testMethod()
    {

    	// Arrange
    	$tData=array(
    		new row_author(array(
    			'pkey' => 44,
    			'firstname' => 'victor',
    		)),
    		new row_author(array(
    			'pkey' => 54,
    			'firstname' => 'isaac',
    		)),
    	);

    	model_author::getInstance()->setReturn($tData);

    	//act
    	$tReturn=model_author::getInstance()->getSelect();

    	//Assert
    	$tAssert=array(
    			44=>'victor',
    			54=>'isaac',
		);
    	$this->assertEquals($tAssert,$tReturn);

    }
    */

    #code#

    
}