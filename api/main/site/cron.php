<?php

function cron($db){

	$result = mysqli_query('SELECT * FROM cronjob');

	while($row = mysqli_fetch_row($result)){

		if($row['function'] == 'backupDB' && ($row['interval'] + $row['last_job_time']) < time()){

			mysqli_query("UPDATE cronjob SET last_job_time = '".time()."' WHERE function = '".$row['function']."'");
			backupDB($db);

		}
	}
}

?>