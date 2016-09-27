<?php
//require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/CommonDao.php';
require APP_ROOT_DIR . '/controller/AbstractController.php';

class QuestionsController extends AbstractController {

	// protected $className = "";

   	public function __construct() {
   		$this->className = str_replace(CONTROLLER_BASE_NAME,'' ,basename(__FILE__));
   	}

	public function view($postData){

//		//検索条件の取得
//	    $searchDate = (isset($postData["search_date"])) ? $postData["search_date"] : date("Ymd");
//		$today = date("Ymd");
//	    $searchSelSiteGroupName = (isset($postData["search_sel_site_group"])) ? $postData["search_sel_site_group"] : '';
//	    //$searchSelSiteCateId = (isset($postData["search_sel_site_cate"])) ? $postData["search_sel_site_cate"] : '';
//	    $searchSelSiteCateId = (isset($postData["search_sel_site_cate"])) ? $postData["search_sel_site_cate"] : '';
//	    $searchFreeWord = (isset($postData["search_word"])) ? $postData["search_word"] : "システム エンジニア プログラマ";
//	    $chkDisplay = (isset($postData["chk_display"])) ? "checked" : "";
//	    $chkDisplay2 = (isset($postData["chk_display2"])) ? "checked" : "";
//	    $chkSort = (isset($postData["chk_sort"])) ? "checked" : "";
//
//		//プルダウンリスト用データ取得
//		$conn = new DbConnection();
//		$dbh = $conn->getConnection();
//		$mSiteDao = new MSiteDao($dbh);
//		$mSiteList = $mSiteDao->getSiteCateList();
//		$mSiteGroupDao = new CommonDao($dbh, "M_SITE_GROUP");
//		$mSiteGroupList = $mSiteGroupDao->getAllData();
//		//結果TABLE用データ取得
//		$wJobOfferDao = new WJobOfferDao($dbh);
//		$tCountList = $wJobOfferDao->getMonthlyDataByCond($searchSelSiteGroupName, $searchSelSiteCateId, $searchFreeWord);
//		$monthList = $wJobOfferDao->getMonthList();
		//画面表示
		require APP_ROOT_DIR . DS . 'view' . DS . $this->className . '.tmpl';

	}

	public function entry($postData){
//echo $postData;
		//$fp = fopen(LOG_DIR . '/answer.log', 'a');
		$jsonList = json_decode($postData);
		$answerList = array();
		for ($i = 0 ; $i < count($jsonList) ; $i++) {
			$json = $jsonList[$i];
			$answer = array();
			foreach ($json as $key => $value){
				$answer[$key] = $value;
			}
			$answerList[$i] = $answer;
		}
		//fwrite($fp, var_dump($answerList) . "\n");

		$conn = new DbConnection();
		$dbh = $conn->getConnection();
		$tAnswerHead = new CommonDao($dbh, "T_ANSWER_HEAD");
		$tAnswerDetail = new CommonDao($dbh, "T_ANSWER_DETAIL");

		$aId = $tAnswerHead->getMaxId('A_ID') + 1;
		$qIdWork = 0;
		$date = date("Y/m/d H:i:s");
		//echo var_dump("A_ID:" . $aId . "\n") >> LOG_DIR . '/answer.log';
		try {
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$dbh->beginTransaction();
			$isSuccess = true;
			for ($i = 0 ; $i < count($answerList) ; $i++) {
				$answerData = $answerList[$i];
				if($qIdWork == 0){
					$answerData = $answerList[$i];
				    $dataList = array();
				    $dataList += array('A_ID'=>$aId);
				    $dataList += array('DATE'=> $date);
				    $dataList += array('USER_ID'=>1);
				    $dataList += array('DELETE_FLG'=>0);
					if($tAnswerHead->insert($dataList)){
				        //echo "ヘッダの登録に成功しました\n" >> LOG_DIR . '/answer.log';
					}else{
				        //echo "ヘッダの登録に失敗しました\n" >> LOG_DIR . '/answer.log';
				        $isSuccess = false;
				        break;
					}
				}
			    $dataList = array();
			    $dataList += array('A_ID'=>$aId);
			    $dataList += array('Q_ID'=>$answerData['headNo']);
			    $dataList += array('Q_NO'=>$answerData['questionNo']);
			    $dataList += array('ANSWER'=>$answerData['answer']);
			    $dataList += array('DELETE_FLG'=>0);
				if($tAnswerDetail->insert($dataList)){
			        //echo "詳細の登録に成功しました\n" >> LOG_DIR . '/answer.log';
				}else{
			        //echo "詳細の登録に失敗しました\n" >> LOG_DIR . '/answer.log';
			        $isSuccess = false;
			        break;
				}
				$qIdWork = $answerData['headNo'];
			}
			if($isSuccess && count($answerList) > 0){
				$dbh->commit();
				//fwrite($fp, date("Y/m/d H:i:s") + " 回答の登録に成功しました。");
			}else{
				$isSuccess = false;
			}

		} catch (Exception $e) {
			$dbh->rollBack();
			$isSuccess = false;
			//fwrite($fp, date("Y/m/d H:i:s") + " 回答の登録に失敗しました。" . $e->getMessage());
		}
		//fclose($fp);
		echo $isSuccess;

	}
}


