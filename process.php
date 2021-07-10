<?php

	require_once('PHPExcel/PHPExcel/IOFactory.php');

	$action = isset($_POST["action"]) ? $_POST["action"] : null;
	
	if($action == "add_forms" ){
		$no_of_forms = $_POST["no_of_forms"];
		require_once("forms.php");
		exit;
	}
	
	if($action == "monitor_attendance"){
		$file = $_FILES['file']['tmp_name'];
		$cell_no = $_POST["cell_no"];
		$highest_row = $_POST["highest_row"];
		
		$objPHPExcel_ORIG = PHPExcel_IOFactory::load($file);
		
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel_DUP = $objReader->load($file);
		
		$objPHPExcel_ORIG->setActiveSheetIndex(2);
		$objPHPExcel_DUP->setActiveSheetIndex(2);
		$highest_column_index = PHPExcel_Cell::columnIndexFromString($cell_no);
		//echo $highest_column_index."\n";
		
		$val = array();
		for($row = 3; $row <= $highest_row + 2; ++$row) {	
			$cell = $objPHPExcel_ORIG->getActiveSheet()->getCellByColumnAndRow($highest_column_index+1, $row);
			$status = $cell->getValue();
			echo $highest_column_index.$row."=>".$status."\n";
			/* 
			if($status == 1){
				$objPHPExcel_DUP->getActiveSheet()->setCellValue($cell_no.$row, "P");
			}else if($status == ""){
				$objPHPExcel_DUP->getActiveSheet()->setCellValue($cell_no.$row, "A");
			} */
		}	
		
		
		exit;
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
		$path = 'C:/xampp/htdocs/grade_computer/files/'.$_FILES['file']['name'].'-'.rand(1,10000).'.xlsx';
		$objWriter->save($path);
		echo "success";
		exit;
	}
	
	if($action == "compute_grade"){
	
		$file = $_FILES['file']['tmp_name'];
	
		$activities = explode(",",$_POST["activity_items"]);
		$no_of_activities = count($activities);
	
		$least_percentage_score_for_students_who_efforts = 60;
		$max_percentage_score_for_students_who_efforts = 100;
		
		$least_percentage_score_for_students_who_sometimes_efforts = 60;
		$max_percentage_score_for_students_who_sometimes_efforts = 75;
		
		$least_percentage_posibility_of_failed_activities = 10;
		$max_percentage_posibility_of_failed_activities = 50;
		
		$least_percentage_score_for_student_who_does_not_efforts = 20;
		$max_percentage_score_for_student_who_does_not_efforts = 49;
		
		$least_percentage_score_for_student_who_does_not_efforts_but_not_failed = 50;
		$max_percentage_score_for_student_who_does_not_efforts_but_not_failed = 90;
		
		$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objExcel = $objReader->load("template/template.xlsx");
		
		$objExcel->setActiveSheetIndex(1);
		
		for($i =0; $i < $no_of_activities; $i++){
			$objExcel->getActiveSheet()->setCellValue(PHPExcel_Cell::stringFromColumnIndex($i + 1)."1", $activities[$i]);	
		}
		
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			
			for($row = 1; $row <= $highestRow; $row++) {
				$val = array();
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				   $cell = $worksheet->getCellByColumnAndRow($col, $row);
				   $val[] = $cell->getValue();
				}
			
				$student = $val[0];
				$remark = $val[1];
			
				if($no_of_activities > 0){
					$score = array();
					
					if($remark == 1){
						for($i = 0; $i < $no_of_activities; $i++){
							$score[$i] = ceil($activities[$i] * (rand($least_percentage_score_for_students_who_efforts, $max_percentage_score_for_students_who_efforts)/100));						
						}		
					}elseif($remark == 2){
						for($i = 0; $i < $no_of_activities; $i++){
							$score[$i] = ceil($activities[$i] * (rand($least_percentage_score_for_students_who_sometimes_efforts, $max_percentage_score_for_students_who_sometimes_efforts)/100));						
						}	
					}elseif($remark == 0){
						$max_no_of_fail_activities = ceil($no_of_activities * (rand($least_percentage_posibility_of_failed_activities,$max_percentage_posibility_of_failed_activities)/100));
						$counter_no_of_fail_activities = 0;
						
						for($i = 0; $i < $no_of_activities; $i++){
							$will_fail = rand(0,1) == 1? true : false;	
							if($will_fail){
								if($counter_no_of_fail_activities <= $max_no_of_fail_activities){
									$score[$i] = floor($activities[$i] * (rand($least_percentage_score_for_student_who_does_not_efforts,$max_percentage_score_for_student_who_does_not_efforts)/100));
									$counter_no_of_fail_activities+=1;	
								}else{
									$score[$i] = ceil($activities[$i] * (rand($least_percentage_score_for_student_who_does_not_efforts_but_not_failed,$max_percentage_score_for_student_who_does_not_efforts_but_not_failed)/100));
								}	
							}else{
								$score[$i] = ceil($activities[$i] * (rand($least_percentage_score_for_student_who_does_not_efforts_but_not_failed,$max_percentage_score_for_student_who_does_not_efforts_but_not_failed)/100));
							}
						}
					}
					
					$objExcel->getActiveSheet()->setCellValue("A".($row + 1), $student);	
					
					for($i = 0; $i < count($score); $i++){
						$objExcel->getActiveSheet()->setCellValue(PHPExcel_Cell::stringFromColumnIndex($i + 1).($row + 1), $score[$i]);
					}
				}
			
			}	
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
		$path = 'C:/xampp/htdocs/grade_computer/files/'.$_FILES['file']['name'].'-'.rand(1,10000).'.xlsx';
		$objWriter->save($path);
		echo "success";
		exit;
	}
	