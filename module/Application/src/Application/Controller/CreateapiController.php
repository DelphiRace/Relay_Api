<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use System_APService\clsSystem;

class CreateapiController extends AbstractActionController
{
    public function indexAction()
    {
		$SysClass = new clsSystem;
		$SysClass->initialization();
		try{
			$pageContent = "test";
			//----BI結束----
		}catch(Exception $error){
			//依據Controller, Action補上對應位置, $error->getMessage()為固定部份
			$SysClass->WriteLog("IndexController", "indexAction", $error->getMessage());
		}
		$SysClass = null;
		$this->viewContnet['pageContent'] = $pageContent;
        return new ViewModel($this->viewContnet);
    }

    // 創建API相關
    public function buildapiAction()
    {
		$SysClass = new clsSystem;
		$SysClass->initialization();
		try{
			// API URL & API Code - Array
			$apiType = $_POST["apiType"];
			$apiCode = $_POST["apiCode"];
			//$apiOptionObject = $_POST["apiOption"];
			$apiName = $_POST["apiName"];
			$apiUrl = $_POST["apiUrl"];

			// $apiExecute = $_POST["apiExecute"];

			if(!empty($apiName) and !empty($apiUrl)){
				$path = dirname(__DIR__) . "\\..\\..\\..\\..\\public\\include\\apiSet\\".$apiType."_".$apiCode.".ini";
				$apiOptionObject = [];
				foreach ($apiName as $key => $value) {
					$apiOptionObject[$apiCode][$value] = $apiUrl[0];
				}
				// echo $SysClass->Data2Json($apiOptionObject);
				// 創建INI設定
				$SysClass->creatINI($apiOptionObject, $path, true);
				$fileName = $apiType."_".$apiCode;
				// 創建檔案
				$this->creatPHP($SysClass, $fileName,$_POST["executeFunctionVariable"]);
			}

			
			$pageCaontent = $SysClass->Data2Json($apiOptionObject);
			//----BI結束----
		}catch(Exception $error){
			//依據Controller, Action補上對應位置, $error->getMessage()為固定部份
			$SysClass->WriteLog("IndexController", "indexAction", $error->getMessage());
		}
		$SysClass = null;
		$this->viewContnet['pageContent'] = $pageContent;
        return new ViewModel($this->viewContnet);
    }

    private function creatPHP($SysClass, $fileName, $executeFunctionVariable){
    	if(empty($executeFunctionVariable)){
    		return;
    	}
    	$eV = explode(",", $executeFunctionVariable);
    	$sFileContent = '<?php'."\n";
    	$sFileContent .= '$url=$'.$eV[0].';'."\n";
    	$sFileContent .= '$SendArray=$_POST["'.$eV[1].'"];'."\n";
    	$sFileContent .= '?>';

    	$sFileFullPath = dirname(__DIR__) . "\\..\\..\\..\\..\\public\\include\\api\\".$fileName.".php";
    	//創建
    	$SysClass->CreateFile($sFileFullPath,$sFileContent);


    }
}
