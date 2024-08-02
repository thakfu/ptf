<?php

include 'header.php';
$curTime =  date("Y-m-d H:i:s", time());


/*$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
} */


foreach($_POST as $header => $row) {
    echo $header . ' - ' . $row;
    echo '<br>';
}

$keys = array('CoachID','FirstName','LastName','Age','Experience','Job','Salary','Years','Background','Reputation','Intelligence','Discipline','Motivating','Focus','DefScheme','LBUse','DLUse','CoverPref',
'KeyDefPos','OffScheme','PassPref','PassTargetPref','RunPref','TERole','RBRole','WRPref','FBRole','QBPref','CoachingOff','CoachingDef','Prep','AssessAbility','AssessPotential','Scouting',
'Recruiting','Youngsters','Development','PhysicalTraining','AttentionToDetail','TrainDLine','TrainLBs','TrainSTS','TrainOL','TrainOffSkill','TrainQB','Loyalty','Leadership','Charisma','Flexibility');

foreach($keys as $key) {
    $update .= '`' . $key . '` = VALUES(`' . $key . '`),';
}
$upsert = substr($update, 0, -1);

$keys2 = array('ID','FirstName','LastName','TeamID','Age','Exp','Background','Attitude','Style','Salary','SalaryYears','Job','HOF','Team');

foreach($keys2 as $key2) {
    $update2 .= '`' . $key2 . '` = VALUES(`' . $key2 . '`),';
}
$upsert2 = substr($update2, 0, -1);
  
$update1 = $connection->query("INSERT INTO ptf_coaches 
(CoachID,FirstName,LastName,Age,Experience,Job,Salary,Years,Background,Reputation,Intelligence,Discipline,Motivating,Focus,DefScheme,LBUse,DLUse,CoverPref,
KeyDefPos,OffScheme,PassPref,PassTargetPref,RunPref,TERole,RBRole,WRPref,FBRole,QBPref,CoachingOff,CoachingDef,Prep,AssessAbility,AssessPotential,Scouting,
Recruiting,Youngsters,Development,PhysicalTraining,AttentionToDetail,TrainDLine,TrainLBs,TrainDBs,TrainSTS,TrainOL,TrainOffSkill,TrainQB,Loyalty,Leadership,Charisma,Flexibility) 
VALUES (" . $_POST['CoachID'] . ",'" . $_POST['firstname'] . "','" . $_POST['lastname'] . "','" . $_POST['age'] . "','" . $_POST['experience'] . "','" . $_POST['job'] . "','" . $_POST['salary'] . "','" . $_POST['years'] . "','" . $_POST['background'] . "',
'" . $_POST['reputation'] . "','" . $_POST['intelligence'] . "','" . $_POST['discipline'] . "','" . $_POST['motivating'] . "','" . $_POST['focus'] . "',
'" . $_POST['DefScheme'] . "','" . $_POST['LBUse'] . "','" . $_POST['DLUse'] . "','" . $_POST['CoverPref'] . "','" . $_POST['KeyDefPos'] . "','" . $_POST['OffScheme'] . "','" . $_POST['PassPref'] . "','" . $_POST['PassTargetPref'] . "',
'" . $_POST['RunPref'] . "','" . $_POST['TERole'] . "','" . $_POST['RBRole'] . "','" . $_POST['WRPref'] . "','" . $_POST['FBRole'] . "','" . $_POST['QBPref'] . "','" . $_POST['coachoff'] . "','" . $_POST['coachdef'] . "','" . $_POST['prep'] . "',
0,0,0,0,'" . $_POST['youngsters'] . "','" . $_POST['development'] . "','" . $_POST['physical'] . "','" . $_POST['detail'] . "','" . $_POST['traindl'] . "','" . $_POST['trainlb'] . "','" . $_POST['traindb'] . "','" . $_POST['trainst'] . "','" . $_POST['trainol'] . "','" . $_POST['trainskill'] . "',
'" . $_POST['trainqb'] . "','" . $_POST['loyalty'] . "','" . $_POST['leadership'] . "','" . $_POST['charisma'] . "','" . $_POST['flexibility'] . "') ON DUPLICATE KEY UPDATE " . $upsert);
if(!$update1) {
  echo 'Upsert to ptf_coaches failed! Tell ThakFu!';
}

$update2 = $connection->query("INSERT INTO ptf_coaches_export 
(ID,FirstName,LastName,TeamID,Age,Exp,Background,Attitude,Style,Salary,SalaryYears,Job,HOF,Team) 
VALUES (" . $_POST['CoachID'] . ",'" . $_POST['firstname'] . "','" . $_POST['lastname'] . "','" . $_POST['team'] . "','" . $_POST['age'] . "','" . $_POST['experience'] . "','" . $_POST['background'] . "','" . $_POST['attitude'] . "','" . $_POST['style'] . "',
'" . $_POST['salary'] . "','" . $_POST['years'] . "','" . $_POST['job'] . "',0,'" . idToAbbrev($_POST['team']) . "') ON DUPLICATE KEY UPDATE " . $upsert2);
if(!$update2) {
  echo 'Upsert to ptf_coaches_export failed! Tell ThakFu!';
}

//echo "UPDATE ptf_users SET coach_update = '".$curTime."', CoachID =  " . $_POST['CoachID'] . ", CoachSet = 1 WHERE user_id = " . $_POST['User'];
$update3 = $connection->query("UPDATE ptf_users SET coach_update = '".$curTime."', CoachID =  " . $_POST['CoachID'] . ", CoachSet = 1 WHERE user_id = " . $_POST['User']);
if(!$update3) {
  echo 'Upsert to ptf_users failed! Tell ThakFu!';
}
echo '<br>Your coach has been submitted! ';

?>
