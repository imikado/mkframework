<?php class module_builderTools{
	
	
	
	
	public function getListTablesFromConfig($sConfig){
			
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'json' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		$oModelFactory=new model_mkfbuilderfactory();
		$oModelFactory->setConfig( $sConfig);
		return $oModelFactory->getListTable();
	}
	public function getListColumnFromConfigAndTable($sConfig,$sTable){
			
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'json' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		$oModelFactory=new model_mkfbuilderfactory();
		$oModelFactory->setConfig( $sConfig);
		$oModelFactory->setTable( $sTable);
		return $oModelFactory->getListColumn();
	}
	public function getListRowsFromConfigAndTable($sConfig,$sTable){
			
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'json' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		$oModelFactory=new model_mkfbuilderfactory();
		$oModelFactory->setConfig( $sConfig);
		$oModelFactory->setTable( $sTable);
		return $oModelFactory->findMany('SELECT * FROM '.$sTable);
	}
	
	public function getListColumnFromClass($sClass){
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php';
		require_once( $sPath );
		$oModel=new $sClass;
		
		$sConfig=$oModel->getConfig();
		
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'json' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		return $oModel->getListColumn();
	}
	
	public function loadConfig($sClass){
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php';
		require_once( $sPath );
		$oModel=new $sClass;
		
		$sConfig=$oModel->getConfig();
		
		if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'xml' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'csv' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		else if( _root::getConfigVar('db.'.$sConfig.'.sgbd') == 'json' ){
			if( !file_exists( _root::getConfigVar('db.'.$sConfig.'.database') ) ){
				$sBuilderDbPath=_root::getConfigVar('path.data').'genere/'._root::getParam('id').'/public/'._root::getConfigVar('db.'.$sConfig.'.database');
				if( file_exists($sBuilderDbPath) ){
					_root::setConfigVar('db.'.$sConfig.'.database',$sBuilderDbPath);
				}else{
					throw new Exception('Base inexistante '._root::getConfigVar('db.'.$sConfig.'.database').' ni '.$sBuilderDbPath );
				}
			}
		}
		
		return $oModel->getListColumn();
	}
	
	 
	
	public function getIdTabFromClass($sClass){
		$sPath=_root::getConfigVar('path.generation')._root::getParam('id').'/model/'.$sClass.'.php';
		require_once( $sPath );
		$oModel=new $sClass;
		
		return $oModel->getIdTab();
	}
	
	public function rootAddConf($sConf){
		_root::addConf(_root::getConfigVar('path.generation')._root::getParam('id').'/'.$sConf);
		_root::loadConf();
	}
	
	public function updateLayoutTitle($sProject){
		
		$sContent=$this->stringReplaceIn(array(
										'examplesite' => $sProject
									),
									_root::getConfigVar('path.generation').$sProject.'/layout/template1.php'
		);

		$oFile=new _file( _root::getConfigVar('path.generation').$sProject.'/layout/template1.php' );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
	}
	public function updateFile($sProject,$tMatch,$sFile){
		$sContent=$this->stringReplaceIn($tMatch,
									_root::getConfigVar('path.generation').$sProject.'/'.$sFile
		);

		$oFile=new _file( _root::getConfigVar('path.generation').$sProject.'/'.$sFile );
		$oFile->setContent($sContent);
		$oFile->save();
		$oFile->chmod(0666);
	}
	public function stringReplaceIn($tMatch,$sFile){
		$oFile=new _file($sFile);
		$sContent=$oFile->getContent();
		foreach($tMatch as $sPattern => $sReplace){
			$sContent=preg_replace('/'.$sPattern.'/s',$sReplace,$sContent);
		}
		return $sContent;
	}
	public function getListModule(){
	    $oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/module/');
	    $tDir= $oDir->getListDir();
	    $tNewDir=array();
	    foreach($tDir as $oModule){
			$sModuleName=$oModule->getName();
			if(in_array($sModuleName,array('menu','builder','example','exampleembedded'))){
				continue;
			}
			if(!file_exists(module_builder::getTools()->getRootWebsite().'module/'.$sModuleName.'/main.php')){			
				continue;
			}
			
			$tNewDir[]=$oModule;
			
		}
		return $tNewDir;
	    
	}
	public function getRootWebsite(){
	    return _root::getConfigVar('path.generation')._root::getParam('id').'/';
	}
	public function projetmkdir($sRep){
		$oDir=new _dir(_root::getConfigVar('path.generation')._root::getParam('id').'/'.$sRep);
		try{
			$oDir->save();
			$oDir->chmod(0777);
		}catch(Exception $e){
			//pas grave si repertoire existe deja, mais on avertir quand meme
			return false;
		}
		return true;
	}
	
}
