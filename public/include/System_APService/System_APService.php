<?php
	//宣告命名空間
	namespace System_APService;
	
	//先載入各物件
	$systemApPath = glob( __DIR__ ."\\*\\*\\*.php");
	
	if(!empty($systemApPath)){
		foreach($systemApPath as $systemApContent){
			include_once($systemApContent);
			//print_r($systemApContent);
		}
    }else{
        //先載入各物件
        $systemApPath = glob( __DIR__ ."/*/*/*.php");
        if(!empty($systemApPath)){
            foreach($systemApPath as $systemApContent){
                include_once($systemApContent);
                //print_r($systemApContent);
            }
        }
    }
    
    
	//載入結束
	//引用物件命名空間
	// use SystemDBService\clsDB_MySQL;
	use SystemToolsService\clsTools;
	use SystemFrameService\clsFrame;
	//引用完畢
	
	class clsSystem{
		//資料庫連線參數
		public $SystemDBService;
		//相關工具
		public $SystemToolsService;
		//相關框架設定
		public $SystemFrameService;
		//ini相關設定
		public $iniSet;
		//使用者資訊
		public $userInfo;
		//log file setting
		public $logFileSetting;
		
		//供呼叫程式初始化設定
		public function initialization($DBSection = ''){
			@session_start();
			//相關工具設定
			//基礎的資安防護
			$VTs = new clsTools;
			if(!empty($_POST)){
				$_POST = $VTs->replacePackage($_POST);
			}
			
			if(!empty($_GET)){
				$_GET = $VTs->replacePackage($_GET);
			}
			//結束基礎的資安防護

			//取得資料庫設定值
			$strIniFile = __DIR__ . '\\..\\connDB.ini';
            if(!file_exists($strIniFile)){
                $strIniFile = __DIR__ . '/../connDB.ini';
            }
			$sSection = 'connDB';
            if(!$DBSection){
                $DBSection = 'defaultDB';
            }
			
			//取得連線資料庫資料
			// $sServer = $VTs->GetINIInfo($strIniFile,$sSection,'servername','');
			// $sUser = $VTs->GetINIInfo($strIniFile,$sSection,'user','');
			// $sPassWord = $VTs->GetINIInfo($strIniFile,$sSection,'password','');
            //取得資料庫
			// $sDatabase = $VTs->GetINIInfo($strIniFile,$DBSection,'defaultDB','');
			
			//放到共同變數中
			// $iniSet["DBSet"]["sServer"] = $sServer;
			// $iniSet["DBSet"]["sUser"] = $sUser;
			// $iniSet["DBSet"]["sPassWord"] = $sPassWord;
			// $iniSet["DBSet"]["sDatabase"] = $sDatabase;
			
			//載入LOG設定檔
			$strIniFile = __DIR__ . '\\..\\setlog.ini';
            if(!file_exists($strIniFile)){
                $strIniFile = __DIR__ . '/../setlog.ini';
            }
            $sSection = "log";
			$this->logFileSetting = $VTs->GetINIInfo($strIniFile,$sSection,'write','');

			//存到變數，以重複利用
			$this->SystemToolsService = $VTs;
			//釋放
			$VTs = null;
			//相關工具設定結束
			
			//建立資料庫連線
			// $VTc = new clsDB_MySQL;
			// $VTc->CreateDBConnection($sServer,$sDatabase,$sUser,$sPassWord);

			//存到變數，以重複利用
			// $this->SystemDBService = $VTc;
			//釋放
			// $VTs = null;
		}
	#檢查ＳＥＳＳＩＯＮ
	public function CheckLogin(){
		if(empty($_SESSION)){
			header("Location: ./login.html");
			exit();
		}
		return true;
	}
	#結束檢查ＳＥＳＳＩＯＮ
		
	#這裡是	SystemToolsService
	#modIO
		//讀取頁面Html檔案
		public function GetHtmlContent($fPath){
			return $this->SystemToolsService->GetHtmlContent($fPath);
		}
		
		//讀取INI檔資料 GetINIInfo(strIniFile, sSection, sKeyName, sDefaultValue = "") As String
		public function GetINIInfo($strIniFile,$sSection,$sKeyName,$sDefaultValue = "",$originDataArray = false, $process_sections = false){
			return $this->SystemToolsService->GetINIInfo($strIniFile,$sSection,$sKeyName,$sDefaultValue,$originDataArray,$process_sections);
		}
		
		//使用cmd執行指令
		public function cmdExecute($sCommand){
			return $this->SystemToolsService->cmdExecute($sCommand);
		}
		
		//建立資料夾 CreateDirectory(sPath)
		public function CreateDirectory($sPath){
			$this->SystemToolsService->CreateDirectory($sPath);
		}
		
		//建立檔案 CreateFile(sFileFullPath)
		public function CreateFile($sFileFullPath,$sFileContent, $writeType = "w"){
			return $this->SystemToolsService->CreateFile($sFileFullPath,$sFileContent,$writeType);
		}
		
		//複製檔案 CopyFile(sOrgFileFullPath, sOutFileFullPath)
		public function CopyFile($sOrgFileFullPath, $sOutFileFullPath){
			$this->SystemToolsService->CopyFile($sOrgFileFullPath, $sOutFileFullPath);
		}
		
		//複製資料夾 CopyField(sOrgFieldPath, sOutFieldPath)
		public function CopyField($sOrgFieldPath, $sOutFieldPath){
			$this->SystemToolsService->CopyField($sOrgFieldPath, $sOutFieldPath);
		}
		
		//刪除檔案 DelFile(sFilePath)
		public function DelFile($sFilePath){
			$this->SystemToolsService->DelFile($sFilePath);
		}
		
		//刪除資料夾 DelField(sFieldPath)
		public function DelField($sFieldPath){
			$this->SystemToolsService->DelField($sFieldPath);
		}
		
        //產生ＰＤＦ檔案
        public function Page2PDF($ChangePagePagth , $saveFileName, $zoom = 1){
            return $this->SystemToolsService->Page2PDF($ChangePagePagth , $saveFileName, $zoom);
        }
        
        
		//寫LOG檔 ThreadLog(clsName, funName, sDescribe = "", sEventDescribe = "", iErr = 0) 
		public function WriteLog($clsName, $funName, $sDescribe = "", $sEventDescribe = "", $iErr = 0){
			global $callFunction;
			//$SystemToolsService = $this->SystemToolsService;
			$callFunction = debug_backtrace();
			$callFunction = $callFunction[0];
			
			$this->SystemToolsService->ThreadLog($clsName, $funName, $sDescribe, $sEventDescribe, $iErr);
			
			//畫面操作事件
			if($sEventDescribe != ""){
				$this->SetAPPLog($sEventDescribe);
			}

			//釋放
			$SystemDBService = null;
			$SystemToolsService = null;
			$callFunction = null;
		}

		//寫入使用者APP Log
		public function SetAPPLog($sLogMsg, $sLogSource = "操作紀錄", $blCn2 = false, $iLogType = 1, $sPhyAddr = "(NULL)", $blFiahMarket = false){
			global $SystemToolsService;
			$SystemToolsService = $this->SystemToolsService;
			try{
				if(!empty($_SESSION)){
					$uuid = $_SESSION["uuid"];
					$sClerk = $_SESSION["ac"];
					$cHost = $_SESSION["userName"];
				}else{
					$uuid = 0;
					$sClerk = "system";
					$cHost = "系統動作";
				}
				//寫入
				$this->SystemDBService->SetAPPLog($uuid, $sClerk, $cHost, $sLogMsg, $blCn2, $sLogSource, $iLogType, $sPhyAddr);
			}catch(Exception $error){
				$this->WriteLog("clsTools", "SetAPPLog", $error->getMessage(), "", 1);
			}
		}

	#modIO結束
		
	#modDataFormate
		//日期轉換
		public function DateTime($changeType,$Date=null){
			$dateStr = $this->SystemToolsService->DateTime($changeType,$Date);
			if(!$dateStr){
				print_r("Error Date Type: ".$changeType."; or Date: ".$Date);
				return false;
			}
			return $dateStr;
		}
		
		//資料轉換成json(encode)
		public function Data2Json($Data){
			return $this->SystemToolsService->Data2Json($Data);
		}
		
		//json轉換成資料轉(decode)
		public function Json2Data($JsonData){
			return $this->SystemToolsService->Json2Data($JsonData);
		}
		//資料內容取代
		public function ContentReplace($processData,$replaceContent){
			$processContent = $this->SystemToolsService->ContentReplace($processData,$replaceContent);
			if(!$processContent){
				$callFunction = debug_backtrace();
				$callFunction = $callFunction[0];
				$this->WriteLog($callFunction["class"], $callFunction["function"], "內容取代錯誤\n");
			}else{
				return $processContent;
			}
		}
	#modDataFormate結束
		
	#DataInformationSecurity
		//資訊全重複檢查是否有遺漏的，並取代為HTML CODE
		public function replacePackage($arr){
			$tmpArr = $this->SystemToolsService->replacePackage($arr);
			return $tmpArr;
		}
	#DataInformationSecurity結束
		
	#modArrayDebug
		public function debug($DataArray){
			$this->SystemToolsService->debug($DataArray);
		}
	#modArrayDebug結束
	
	#modCurl相關
		//POST
		public function UrlDataPost($url, $SendArray) {
			//回傳結果是對象URL執行結果
			return $this->SystemToolsService->UrlDataPost($url, $SendArray);
		}
		//GET
		public function UrlDataGet($url,$obj) {
			//回傳結果是對象URL執行結果
			return $this->SystemToolsService->UrlDataGet($url,$obj);
		}
	#modCurl結束
	#modMail
		public function Tomail($sender,$recipient,$mailTitle,$msg){
			//回傳true/false
			return $this->SystemToolsService->Tomail($sender,$recipient,$mailTitle,$msg);
		}
	#modMail結束	
	
	
		public function creatINI($assoc_arr, $path, $has_sections=false, $append = false){
			$this->SystemToolsService->creatINI($assoc_arr, $path, $has_sections, $append);
		}
    #這裡是	SystemToolsService 結束
	}
	
	
?>