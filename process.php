<html>
	<head>
		<title>Your Skedule</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="html2canvas.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="https://raw.github.com/MrRio/jsPDF/master/jspdf.js"></script>
		<script type="text/javascript" src="https://raw.github.com/MrRio/jsPDF/master/jspdf.plugin.from_html.js"></script>
		<script type="text/javascript" src="https://raw.github.com/eligrey/Blob.js/master/Blob.js"></script>
		<script type="text/javascript" src="https://raw.github.com/eligrey/FileSaver.js/master/FileSaver.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {

				$('a[data-auto-download]').each(function(){
    				var $this = $(this);
				    setTimeout(function() {
				      window.location = $this.attr('href');
				    }, 2000);
				  });

				$("#screenshot").click(function() {
					//var testdiv = document.getElementById("testdiv");
    				html2canvas($("#skedule"), {
        				onrendered: function(canvas) {
            				// canvas is the final rendered <canvas> element
            				var myImage = canvas.toDataURL("image/png");
            				//var newImage = myImage.src.replace(/^data:image\/[^;]/, 'data:application/octet-stream');
            				image_win = window.open();
            				image_win.focus();
            				//image_win.location.href = myImage;
            				image_win.document.write("<head><link rel='stylesheet' type='text/css' href='style.css'><link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></head>");
            				image_win.document.write("<a href='" + myImage + "' download='skedule.png' id='temp_link'>Download</a>");
            				image_win.document.getElementById("temp_link").click();
            				//image_win.document.write("Right-click => Save Image</span>");
            				image_win.close();
        				}
    				});
				});

				$("#print").click(function() {
					var $skedule = $("#skedule").html();
					popup = window.open();
					popup.document.write("<head><link rel='stylesheet' type='text/css' href='style.css'><link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></head>");
					popup.document.write($skedule);
					popup.print();
					popup.close();

				});

				$("#pdf").click(function() {
                            var specialElementHandlers = function(element,renderer) {
                            	return true;
                            }

                            var doc = new jsPDF();

                            doc.fromHTML($('#skedule').html(), 15, 15, {
                            'width': 170,
                            'elementHandlers': specialElementHandlers
                            });

                            doc.output('datauri'); 
				});
			});
		</script>
	</head>
	<body>
	<form method="POST" enctype="multipart/form-data" action="" id="myForm">
    <input type="hidden" name="img_val" id="img_val" value="" />
	</form>
	<div id="skedule">
		<?php

			define("EMPTY", "Empty", True);

			$debug_log 		= False;	
			$debug_data		= False;

			function convert_to_mins($time_param)
			{

				$time_param = intval($time_param);
				$hours = (int)($time_param/100);
				$mins = $time_param % 100;

				$mins = ($hours * 60) + $mins;

				return $mins;
			}

			function add_mins_to_time ($time_param, $addmins)
			{
					$temp = convert_to_mins($time_param) + $addmins;
					$hours = (int)($temp/60);
					$mins = $temp % 60;

					return $hours. str_pad($mins,2,"0");
			}

			function init_schedule($start_param, $end_param)
			{
				global $schd_arr;
				global $debug_log;

				$diff_mins = (convert_to_mins($end_param) - convert_to_mins($start_param));

				if($debug_log)
						echo "Diff in Mins = " . $diff_mins . "<br>";
				
				
				$max_slots = $diff_mins / 30;

				if($debug_log)
						echo "Max Slots = " . $max_slots . "<br>";
						
				
				for($i=0; $i<$max_slots; $i++)
				{

					$last_slot = add_mins_to_time($start_param, $i*30);
					
					$schd_arr[$last_slot] = "";
					
				}
				return;
			}
			
			function assign_slot_inschedule($actname, $actdur, $actstart)
			{
				//function slota an activity into the schedule
				global $schd_arr;
				global $debug_log;

				// First find the number of slots needed for this activity
				$numslots = intval($actdur)/30;

				// Next, find the slot names that this activity will be assigned to
				$slot_arr = array();
				for($i=0; $i < $numslots; $i++)
				{
					$slot_arr[$i] = add_mins_to_time($actstart, ($i*30));

				}

				if ($debug_log)
				{
					echo "Identified the slots assigned for the task '" . $actname . "' : <br>";
					print_r($slot_arr);
					echo "<hr>";
				}
				
				// assign the identified slots to the Activity
				foreach ($slot_arr as $cnt => $slot_name) 
				{
					if ($debug_log) 
						echo "Slot name : " . $slot_name . "<br>";

					$schd_arr[$slot_name] = $actname;
				}

				//echo "<hr>";

				return 0;
			}

			function find_empty_slot($duration)
			{
				// This function finds the first available slot for the specified duration

				global $schd_arr;
				global $debug_log;

				$slot = "";
				$slots_found = 0;

				$slots_needed = intval($duration)/30;

				foreach ($schd_arr as $key => $actname) 
				{
					if ($debug_log) 
						echo "Slot name : " . $key . "<br>";

					if($schd_arr[$key] == "") 
					{
							//echo "could be an empty slot <br>";
							//echo "slot =  " . $slot . " <br>";
							if($slot == "") {
								//echo "saving slot" . $key . "<br>";
								$slot = $key;
							}
								

							$slots_found++;
					}
					else
					{
						$slots_found = 0;
						$slot = "";
					}

					if ($slots_found >= $slots_needed)
					{
						//echo "getting out of here <br>";
						break;
					}
				}
				

				return $slot;
			}

			function print_info()
			{
				global $debug_log;
				global $starttime;
				global $endtime;
				global $dinnertime;

				global $actname_arr;
				global $actdur_arr;
				global $actprior_arr;
				global $actfixed_arr;
				global $actstart_arr;

				if($debug_log)
				{
					echo "Print Info : ";
					echo '<BR/>';
					echo "Start Time : " . $starttime;
					echo '<BR/>';
				
					echo "End Time : " . $endtime;
					echo '<BR/>';

					echo "Dinner Time : " . $dinnertime;
					echo '<BR/>';
					

					print_r($actname_arr);
					print_r($actdur_arr);
					print_r($actprior_arr);
					print_r($actfixed_arr);
					print_r($actstart_arr);
					echo '<BR/>';

					echo '<hr>';
				}
			}

			function format_time($time) {
				$time = (string)$time;
				if (strlen($time) == 3) {
					$time1 = substr($time, 0, 1);
					$time2 = substr($time, 1, 2);
					return $time1 . ":" . $time2;
				} else {
					$time1 = substr($time, 0, 2);
					$time2 = substr($time, 2, 2);
					return $time1 . ":" . $time2;
				}
				
			}

			function print_schedule($comment)
			{
				global $schd_arr;

				echo "<div align='center'>$comment:</div><br> ";
				echo "<table border='1' cellpadding='5' align='center'>";
				echo "<tr>
					      <td>Time</td>
					      <td>Activity</td>
					  </tr>";

				foreach ($schd_arr as $key => $actname) 
				{
					echo "<tr>";
					echo "<td>";
					echo format_time($key);
					echo "</td>";
					echo "<td>";
					echo $actname;
					echo "</td>";
					echo "</tr>";	
				}
				echo "</table>";
				//print_r($schd_arr);
				//echo '<hr>';

			}

			$starttime 		= $_POST['starttime'];
			$endtime 		= $_POST['endtime'];
			$dinnertime     = $_POST['dinnertime'];
			
			//init the arrays that represent the data structures
			$actname_arr 	= array();		// name of activity
			$actdur_arr 	= array();		// duration of activity
			$actprior_arr 	= array();		// priority of activity
			$actfixed_arr	= array();		// is this activity have fixed timing
			$actstart_arr 	= array();		// what is the start time of this activity?
			
			// init data structure that represents the schedule
			$schd_arr		= array();

			//print_r($_POST);
			echo "<br>";
			//put everything in arrays
			foreach($_POST['actname'] as $cnt => $actname) 
			{
				//echo $cnt . "<br>";
				$actname_arr[$cnt] 		= $actname;
				$actdur_arr[$cnt] 		= $_POST['actdur'][$cnt];
				$actprior_arr[$cnt] 	= $_POST['actprior'][$cnt];
				$actfixed_arr[$cnt] 	= $_POST['actfixed'][$cnt];
				$actstart_arr[$cnt] 	= $_POST['actstart'][$cnt];

				//echo "fixed = ". $actfixed_arr[$cnt] . "<br>";
				//echo "priority = ". $actprior_arr[$cnt] . "<br>";
				
			}

			//start debug
			/*if($debug_data)
			{
				$starttime 		= "430";
				$endtime   		= "900";
				$dinnertime 	= "730";

				// Fixed activity
				$actname_arr[0]  = "Football";
				$actdur_arr[0]	 = 60;
				$actstart_arr[0] = "430";
				$actfixed_arr[0] = "on";
				$actprior_arr[0] = "";

				// High Priority
				$actname_arr[1]  = "School Homework";
				$actdur_arr[1]	 = 30;
				$actstart_arr[1] = "";
				$actfixed_arr[1] = "";
				$actprior_arr[1] = "High";

			}*/
			//end debug

			print_info();

			init_schedule($starttime, $endtime);
			
			//print_schedule("Initial Schedule");
			
			// First assign slots for dinner, as that is by default high priority and Fixed time
			assign_slot_inschedule("Dinner", 60, $dinnertime);
			
			//print_schedule("After Dinner Assignment");
			
			// Next, Assign slots for all Fixed activities
			for ($i=0; $i < count($actname_arr); $i++) 
			{ 
				//echo $i . "<br>";
				//echo $actfixed_arr[$i] . "<br>";
				if($actfixed_arr[$i] == "on")
				{
						//echo "found a fixed activity <br>";
						assign_slot_inschedule($actname_arr[$i], $actdur_arr[$i], $actstart_arr[$i]);
				}

			}
			//print_schedule("After Fixed Activity assignment");

			// Next, Assign slots for all High Priority activities
			for ($i=0; $i < count($actname_arr); $i++) 
			{ 
				//echo $i . "<br>";
				//echo $actfixed_arr[$i] . "<br>";
				if (($actfixed_arr[$i] != "on") && ($actprior_arr[$i] == "high"))
				{
					//echo "found a high priority activity <br>";

					// Find empty slot
					$empty_slot = find_empty_slot($actdur_arr[$i]);
					//echo "empty slot found at  :" . $empty_slot . "<br>";
					//
					assign_slot_inschedule($actname_arr[$i], $actdur_arr[$i], $empty_slot);
				}

			}
			//print_schedule("After High Priority assignments");

				// Next, Assign slots for all Medium Priority activities
			for ($i=0; $i < count($actname_arr); $i++) 
			{ 
				//echo $i . "<br>";
				//echo $actfixed_arr[$i] . "<br>";
				if (($actfixed_arr[$i] != "on") && ($actprior_arr[$i] == "medium"))
				{
					//echo "found a medium priority activity <br>";

					// Find empty slot
					$empty_slot = find_empty_slot($actdur_arr[$i]);
					//echo "empty slot found at  :" . $empty_slot . "<br>";
					//
					assign_slot_inschedule($actname_arr[$i], $actdur_arr[$i], $empty_slot);
				}

			}
			//print_schedule("After Medium Priority assignments");

			// Next, Assign slots for all Low activities
			for ($i=0; $i < count($actname_arr); $i++) 
			{ 
				//echo $i . "<br>";
				//echo $actfixed_arr[$i] . "<br>";
				if (($actfixed_arr[$i] != "on") && ($actprior_arr[$i] == "low"))
				{
					//echo "found a low priority activity <br>";

					// Find empty slot
					$empty_slot = find_empty_slot($actdur_arr[$i]);
					//echo "empty slot found at  :" . $empty_slot . "<br>";
					//
					assign_slot_inschedule($actname_arr[$i], $actdur_arr[$i], $empty_slot);
				}

			}
			//print_schedule("After Low Priority assignments");

			// Next, Assign slots for all other activities
			for ($i=0; $i < count($actname_arr); $i++) 
			{ 
				//echo $i . "<br>";
				//echo $actfixed_arr[$i] . "<br>";
				if (($actfixed_arr[$i] != "on") && ($actprior_arr[$i] == "none"))
				{
					//echo "found a activity <br>";

					// Find empty slot
					$empty_slot = find_empty_slot($actdur_arr[$i]);
					//echo "empty slot found at  :" . $empty_slot . "<br>";
					//
					assign_slot_inschedule($actname_arr[$i], $actdur_arr[$i], $empty_slot);
				}

			}
			print_schedule("Here's your Skedule");

		?>
	</div>
	<hr>
	<input type="button" id="screenshot" value="Download as Photo">
	<input type="button" id="print" value="Print">
	<!-- input type="button" id="pdf" value="PDF" -->
	</body>
</html>
