<?php

require_once '../init.php';
require APP_ROOT_DIR . '/dao/DbConnection.php';
require APP_ROOT_DIR . '/dao/CommonDao.php';
require APP_ROOT_DIR . '/dao/TQuestionDetailDao.php';

//質問データ取得
$conn = new DbConnection();
$dbh = $conn->getConnection();
$tQuestionDetailDao = new TQuestionDetailDao($dbh);
$tQuestionList = $tQuestionDetailDao->getQuestions();
$tAnswerKindDao = new CommonDao($dbh,'T_ANSWER_KIND');
$tAnswerKindList = $tAnswerKindDao->getAllData();

$questionDataList = array();
for ($i = 0; $i < count($tQuestionList); $i++){
    $questionData = $tQuestionList[$i];
    $questionDataArray    = array(
        'Q_ID'    => $questionData['Q_ID'],
        'Q_NO'    => $questionData['Q_NO'],
        'Q_TITLE'    => $questionData['Q_TITLE'],
        'Q_TEXT'    => $questionData['Q_TEXT'],
        'ANSWER_KIND_ID'    => $questionData['ANSWER_KIND_ID'],
        'REQUIRED_FLG'    => $questionData['REQUIRED_FLG']
    );
    array_push($questionDataList,$questionDataArray);
}
$questionDataList    = array('QUESTIONS'=> $questionDataList);

$answerKindDataList = array();
for ($i = 0; $i < count($tAnswerKindList); $i++){
    $answerKindData = $tAnswerKindList[$i];
    $answerKindDataArray    = array(
        'ANSWER_KIND_ID'    => $answerKindData['ANSWER_KIND_ID'],
        'ANSWER_KIND_NO'    => $answerKindData['ANSWER_KIND_NO'],
        'ANSWER_NAME'    => $answerKindData['ANSWER_NAME']
    );
    array_push($answerKindDataList,$answerKindDataArray);
}
$answerKindDataList    = array('ANSWER_KINDS'=> $answerKindDataList);

//$questionList = array_merge($questionDataList, $answerKindDataList);
$questionList = $questionDataList + $answerKindDataList;
$json    = json_encode( $questionList );

//header( 'Content-Type: text/javascript; charset=utf-8' );
//header( 'Content-Type: application/json; charset=utf-8' );
header( 'Content-Type: text/plain; charset=utf-8' );
//header( 'Content-Type: text/plain; charset=Windows-31J' );
echo $json;


