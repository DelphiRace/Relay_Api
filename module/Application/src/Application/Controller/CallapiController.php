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

class CallapiController extends AbstractActionController
{
    public function indexAction()
    {
		$SysClass = new clsSystem;
		$SysClass->initialization();
		try{
			$apiType = $_POST["apiType"];
			$apiCode = $_POST["apiCode"];
			$sSection = $apiCode;
			$strIniFile = dirname(__DIR__) . "\\..\\..\\..\\..\\public\\include\\apiSet\\".$apiType."_".$apiCode.".ini";
			$iniArr = $SysClass->GetINIInfo($strIniFile,$sSection,"",true,true);
			foreach($iniArr as $i => $content){
				${$i} = $content;
				// echo $i.":".${$i};
			}
			$phpFile = dirname(__DIR__) . "\\..\\..\\..\\..\\public\\include\\api\\".$apiType."_".$apiCode.".php";
			include($phpFile);
			$pageContent = $SysClass->UrlDataPost( $url, $SendArray);
			//----BI結束----
		}catch(Exception $error){
			//依據Controller, Action補上對應位置, $error->getMessage()為固定部份
			$SysClass->WriteLog("IndexController", "indexAction", $error->getMessage());
		}
		$SysClass = null;
		$this->viewContnet['pageContent'] = $pageContent;
        return new ViewModel($this->viewContnet);
    }
}
