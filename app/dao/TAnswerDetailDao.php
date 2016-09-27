<?php
class TAnswerDetailDao extends AbstractDao {

	public function __construct($dbh) {
		$this->tableName = 'T_ANSWER_DETAIL';
		parent::__construct($dbh);
		$this->columnList = self::getTableInfo();
	}

	public function getAnswers() {
		try{

			$stmt = $this->dbh->prepare("select QH.*,QD.* from T_ANSWER_HEAD QH INNER JOIN T_ANSWER_DETAIL QD ON QH.Q_ID = QD.Q_ID AND QD.DELETE_FLG = FALSE WHERE QH.DELETE_FLG = FALSE AND QH.ENABLE_FLG = TRUE ORDER BY QD.Q_ID,QD.Q_NO;");
			$stmt->setFetchMode(PDO::FETCH_ASSOC);

			$stmt->execute();
		 
			$dataList= array();
			while ($row = $stmt->fetch()) {
				$data = self::getRowData($row);
				$dataList[] = $data;
			}
			return $dataList;
		 
		} catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	public function getAnswers() {
		try{

			$stmt = $this->dbh->prepare("select QH.*,QD.* from T_ANSWER_HEAD QH INNER JOIN T_ANSWER_DETAIL QD ON QH.Q_ID = QD.Q_ID AND QD.DELETE_FLG = FALSE WHERE QH.DELETE_FLG = FALSE AND QH.ENABLE_FLG = TRUE ORDER BY QD.Q_ID,QD.Q_NO;");
			$stmt->setFetchMode(PDO::FETCH_ASSOC);

			$stmt->execute();
		 
			$dataList= array();
			while ($row = $stmt->fetch()) {
				$data = self::getRowData($row);
				$dataList[] = $data;
			}
			return $dataList;
		 
		} catch(PDOException $e){
			echo $e->getMessage();
		}
	}

	protected function getTableInfo() {

		try{
			$columnList = parent::getTableInfo();
			$dataList = array();
			$dataList += array('Field'=> 'Q_TITLE');
			array_push($columnList,$dataList);
			$dataList = array();
			$dataList += array('Field'=> 'Q_INFO');
			array_push($columnList,$dataList);
			$dataList = array();
			$dataList += array('Field'=> 'ENABLE_FLG');
			array_push($columnList,$dataList);
		} catch(Exception $e){
			echo $e->getMessage();
		}
		return $columnList;
	}
		 

}
