<html>
	<head>
	  <script type="text/javascript">
      //get rid of dot.tk frame
       
		  if (parent.frames.length != 0) {
        window.top.location.href="http://skeduler.tk";
        break;
      }
        
      </script>
      <script src="http://code.jquery.com/jquery-2.0.3.min.js"> </script>
      <script type="text/javascript" src="script.js"></script>
      <link rel="icon" type="image/png" href="favicon.png">
      <link rel="stylesheet" type="text/css" href="style.css">
      <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
      <title>Skeduler</title>
      <meta name="description" content="Skeduler helps you schedule a student's schoolday.">
	</head>
	
	<body>
	
    	<form method="POST" action="process.php">
    	
    		<table>
    			<tr>
    				<td>Time you get home:</td>
    				<td>
    					<select name="starttime">
    						<option value="0400" selected>4:00 PM</option>
    						<option value="0430" >4:30 PM</option>
    						<option value="0500">5:00 PM</option>
    						<option value="0530">5:30 PM</option>
    					</select>
    				</td>
    				
    			</tr>
    			<tr>
    				<td>Time you have dinner:</td>
    				<td>
    					<select name="dinnertime">
    						<option value="0600">6:00 PM</option>
    						<option value="0630">6:30 PM</option>
    						<option value="0700">7:00 PM</option>
    						<option value="0730" selected >7:30 PM</option>
    						<option value="0800">8:00 PM</option>
    						<option value="0830">8:30 PM</option>
    					</select>
    				</td>
    			</tr>
    			<tr>
    				<td>Time you go to bed:</td>
    				<td>
    				<select name="endtime">
    					<option value="0800">8:00 PM</option>
    					<option value="0830">8:30 PM</option>
    					<option value="0900" selected >9:00 PM</option>
    					<option value="0930">9:30 PM</option>
    					<option value="1000">10:00 PM</option>
    				</select>
    				</td>
    			</tr>
    		</table>
    
    		<table id="itemRows" border="0">
    			<th>
    				<tr>
    					<td>Activity</td>
    					<td>Duration</td>
    					<td>Priority</td>
    					<td>Is Fixed?</td>
    					<td>Start Time (Only If Fixed)</td>
    				</tr>
    			</th>
    			<tr>
    				<td>
    					<input type="text" name="actname[]" placeholder="Activity" x-webkit-speech>
    				</td>
    				<td>
    					<select name="actdur[]">
    						<option value="null">-</option>
    						<option value="30">30</option>
    						<option value="60">60</option>
    						<option value="90">90</option>
    						<option value="120">120</option>
    					</select>
    				</td>
    				<td>
    					<select name="actprior[]">
    						<option value="none">None</option>
    						<option value="high">High</option>
    						<option value="medium">Medium</option>
    						<option value="low">Low</option>
    					</select>
    				</td>
    				<td>
    					<!--input type="checkbox" name="actfixed[]"-->
    					<select name="actfixed[]">
    						<option value="off">No</option>
    						<option value="on">Yes</option>
    					</select>
    				</td>
    				<td>
    					<select name="actstart[]">
    						<option value="0400">4:00 PM</option>
    						<option value="0430">4:30 PM</option>
    						<option value="0500">5:00 PM</option>
    						<option value="0530">5:30 PM</option>
    						<option value="0600">6:00 PM</option>
    						<option value="0630">6:30 PM</option>
    						<option value="0700">7:00 PM</option>
    						<option value="0730">7:30 PM</option>
    						<option value="0800">8:00 PM</option>
    						<option value="0830">8:30 PM</option>
    						<option value="0900">9:00 PM</option>
    						<option value="0930">9:30 PM</option>
    						<option value="1000">10:00 PM</option>
    					</select>
    					<!--input type="time" name="actstart[]" /-->
    				</td>
    				<td>
    					<input onclick="addRow(this.form);" type="button" value="Add row" />
    				</td>
    			</tr>
    		</table>
    		<input type="submit" value="Submit">
    	</form>
    <body>
 
</html>
