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

class TestapiController extends AbstractActionController
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

    public function testapiAction()
    {
		$SysClass = new clsSystem;
		$SysClass->initialization();
		try{
			$str = "System: ".$_POST["name"]." say".$_POST["say"];
			$array = ["process"=> $str, "status"=> true];
			$pageContent = $SysClass->Data2Json($array);
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
