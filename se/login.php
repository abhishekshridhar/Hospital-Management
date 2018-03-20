<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

    <?php
require("include/dbinfo.php");
?>

<?php
require("include/dbinfo.php");
if(!isset($_GET['pid']))
{
        require("header.php");
	echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
	$username=$_COOKIE['username'];
	$sessionid=$_COOKIE['PHPSESSID'];
	$row=mysqli_query($con,"select * from session where username='$username' and id='$sessionid'");
	if(!empty($row)&&(mysqli_num_rows($row)))
	{
		$result=mysqli_query($con,"select * from employee where Employee_ID='$username'");
		if($row=mysqli_fetch_array($result))
		{
			$name=$row['Name'];
			$eid=$row['Employee_ID'];
			$dept=$row['Dept_No'];
			$gender=$row['Gender'];
			$contact=$row['Contact'];
			$dob=$row['DOB'];
			$add=$row['Address'];
			echo "<h3>Personal Details</h3>";
			echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
			echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
			echo "<tr><td>Employee ID: </td><td>$eid</td></tr>";
			echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
			echo "<tr><td>Department: </td><td>$dept</td></tr>";
			echo "<tr><td>Contact: </td><td>$contact</td></tr>";
			echo "<tr><td>Gender: </td><td>$gender</td></tr>";
			echo "<tr><td>Address: </td><td>$add</td></tr>";
			echo "</table>";
		}
	}
	echo "</div>";
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_doctors")==0)))
{
        require("header.php");
	$result=mysqli_query($con,"select * from employee where category=\"Doctor\" ");
	$count=mysqli_num_rows($result);
	echo "<div style=\"width:990px; float:left\">";
	echo "<h2 align=\"left\">Doctors</h2>";
	$result=mysqli_query($con,"select * from employee where category=\"Doctor\" ");
	while($count)
	{
		$row=mysqli_fetch_array($result);
		$name=$row['Name'];
		$id=$row['Employee_ID'];
		$contact=$row['Contact'];
		$dept=$row['Dept_No'];
		$add=$row['Address'];
		$result1=mysqli_query($con,"select * from doctors where Employee_ID=\"$id\" ");
		
		$row=mysqli_fetch_array($result1);
		$batch=$row['Batch_No'];
		echo "<div style=\"width:450px; float:left\"><h3 align=\"left\">Dr. $name</h3><p align=\"left\">Employee-ID: $id<br/>Batch No.: $batch<br/>Dept-No.: $dept<br/>Address: $add<br/>Contact: $contact</p></div>";
		$count=$count-1;
	}
	echo "</div>";
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_admin")==0)))
{
        require("header.php");
	$result=mysqli_query($con,"select * from employee where category=\"Adminstration\" ");
	$count=mysqli_num_rows($result);
	echo "<div style=\"width:990px; float:left\">";
	echo "<h2 align=\"left\">Adminstration</h2>";
	while($count)
	{
		$row=mysqli_fetch_array($result);
		$name=$row['Name'];
		$id=$row['Employee_ID'];
		$contact=$row['Contact'];
		$dept=$row['Dept_No'];
		$add=$row['Address'];
		echo "<div style=\"width:450px; float:left\"><h3 align=\"left\">$name</h3><p align=\"left\">Employee-ID: $id<br/>Dept-No.: $dept<br/>Address: $add<br/>Contact: $contact</p></div>";
		$count=$count-1;
	}
	echo "</div>";
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_staff")==0)))
{
        require("header.php");
	$result=mysqli_query($con,"select * from employee where category=\"Medical Staff\" ");
	$count=mysqli_num_rows($result);
	echo "<div style=\"width:990px; float:left\">";
	echo "<h2 align=\"left\">Medical Staff</h2>";
	while($count)
	{
		$row=mysqli_fetch_array($result);
		$name=$row['Name'];
		$id=$row['Employee_ID'];
		$contact=$row['Contact'];
		$dept=$row['Dept_No'];
		$add=$row['Address'];
		echo "<div style=\"width:450px; float:left\"><h3 align=\"left\">$name</h3><p align=\"left\">Employee-ID: $id<br/>Dept-No.: $dept<br/>Address: $add<br/>Contact: $contact</p></div>";
		$count=$count-1;
	}
	echo "</div>";
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"add_emp")==0)))
{
        
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"insert")==0))
	{
		$dept=$_POST['dept_no'];
		$result=mysqli_query($con,"select max(Employee_ID) count from employee where Dept_No=\"$dept\";");
		$row=mysqli_fetch_array($result);
		$count=$row['count'];
		if($count>=0)
		{
			$ptr=stripos($dept,"-");
			$Dept=substr($dept,0,$ptr);
			$ptr=stripos($count,"-");
			$count=substr($count,$ptr+1);
			echo "$count";
			$Emp_ID=$Dept.'-'.("$count"+1);
			
		}
		$name=$_POST['name'];
		$address=$_POST['address'];
		$dob=$_POST['dob'];
		if(isset($_POST['contact']))
		$contact=$_POST['contact'];
		else $contact="NULL";
		$type=$_POST['category'];
		$gender=$_POST['gender'];
		//$bg=$_POST['BG'];
		$salary=$_POST['salary'];
		if(strcmp($type,"Doctor")==0)
		{
			if(isset($_POST['batch']))
			$batch=$_POST['batch'];
			else 
			{
				echo "<script language=\"text/javascript\">Batch number found !!</script>";
				header('Location: ?pid=add_emp');
			}
		}
		mysqli_query($con,"insert into employee values (\"$Emp_ID\", \"$name\", \"$address\", \"$dob\", \"$contact\", \"$gender\", \"$salary\",\"$type\",\"$dept\" )");
		
		if(strcmp($type,"Doctor")==0)
		mysqli_query($con,"insert into doctors values (\"$batch\",\"$Emp_ID\")");
		if(strcmp($type,"Doctor")==0)
		header('Location: ?pid=view_doctors');
		else if(strcmp($type,"Adminstration")==0)
		header('Location: ?pid=view_admin');
		else header('Location: ?pid=view_staff');
	}
	else
	{
                require("header.php");
		echo "<div style=\"width:900px; float:left;\"><h1 align=\"left\">Registeration</h1>";
		echo "<table border=0 cellpadding=0 cellspacing=2 align=\"left\"><form action=\"login.php?pid=add_emp&option=insert\" method=\"post\">";
		echo "<tr><td><h4>Name: </h4></td><td><input name=\"name\" type=\"text\" size=50 placeholder=\"Employee's Name\"></td></tr>";
		echo "<tr><td><h4>Address: </h4></td><td><input name=\"address\" type=\"text\" size=50 placeholder=\"Employee's Address\"></td></tr>";
		echo "<tr><td><h4>Date of Brith: </h4></td><td><input name=\"dob\" type=\"text\" size=50 placeholder=\"YYYY-MM-DD\"></td></tr>";
		echo "<tr><td><h4>Contact: </h4></td><td><input name=\"contact\" type=\"text\" size=50 placeholder=\"Phone Number\"></td></tr>";
		echo "<tr><td><h4>Gender: </h4></td><td><select name=\"gender\"><option>Male</option><option>Female</option></select></td></tr>";
		echo "<tr><td><h4>Category: </h4></td><td><select name=\"category\"><option>Doctor</option><option>Medical Staff</option><option>Adminstration</option></select></td></tr>";
		echo "<tr><td><h4>Batch Number: </h4></td><td><input name=\"batch\" type=\"text\" size=50 placeholder=\"Batch Number of Doctor\"></td></tr>";
		echo "<tr><td><h4>Salary: </h4></td><td><input name=\"salary\" type=\"text\" size=50 placeholder=\"Salary\"></td></tr>";
		echo "<tr><td><h4>Dept. No: </h4></td><td><select name=\"dept_no\">";
		$result=mysqli_query($con,"select count(*) count from departments");
		$row=mysqli_fetch_array($result);
		$count=$row['count'];		
		$result=mysqli_query($con,"select Dept_No from departments");
		for($i=0;$i<$count;$i=$i+1)
		{
				$row=mysqli_fetch_array($result);
				$dept_no=$row['Dept_No'];
				echo "<option>$dept_no</option>";
		}
		echo "</select></td></tr>";
		/*echo "<tr><td><h4>Room Type: </h4></td><td><select name=\"room\"><option>General</option><option>Pivate</option><option>ICU</option></select></td></tr>";*/
		echo "<tr><td><input type=\"submit\" value=\"Submit\" id=\"wdth2\"></td></tr>";
		echo "</form></table>";
		echo "</div>";
	}
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"del_emp")==0)))
{
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"delete")==0))
	{
		$id=$_POST['id'];
		if(isset($id))
		mysqli_query($con,"delete from employee where Employee_ID='$id'; ");
		header('Location: ?pid=del_emp');
	}
	else
	{
                require("header.php");
		echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=del_emp&option=delete\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"id\" type=\"text\" size=50 placeholder=\"Delete using Employee-ID.\" required/></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/del.gif\" style=\"height:30px\" title=\"Click here to delete\"></td></tr>";
	echo "</form></table>";
	}
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"add_patient")==0)))
{
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"insert")==0))
	{
		$result=mysqli_query($con,"select max(Patient_ID) count from patients;");
		$name=$_POST['name'];
		$address=$_POST['address'];
		$dob=$_POST['dob'];
		if(isset($_POST['contact']))
		$contact=$_POST['contact'];
		else $contact="NULL";
		$gender=$_POST['gender'];
		$bg=$_POST['BG'];
		$row=mysqli_fetch_array($result);
		$id=$row['count'];
		
		$id="P".(substr($id,1)+1);
		mysqli_query($con,"insert into patients values (\"$id\", \"$name\", \"$address\", \"$dob\", \"$contact\", \"$gender\", \"$bg\")");
		header('Location: ?pid=view_patient');
	}
	else
	{
                require("header.php");
		echo "<div style=\"width:900px; float:left;\"><h1 align=\"left\">Registeration</h1>";
		echo "<table border=0 cellpadding=0 cellspacing=2 align=\"left\"><form action=\"login.php?pid=add_patient&option=insert\" method=\"post\">";
		echo "<tr><td><h4>Name: </h4></td><td><input name=\"name\" type=\"text\" size=50 placeholder=\"Patient's Name\"></td></tr>";
		echo "<tr><td><h4>Address: </h4></td><td><input name=\"address\" type=\"text\" size=50 placeholder=\"Patient's Address\"></td></tr>";
		echo "<tr><td><h4>Date of Brith: </h4></td><td><input name=\"dob\" type=\"text\" size=50 placeholder=\"YYYY-MM-DD\"></td></tr>";
		echo "<tr><td><h4>Contact: </h4></td><td><input name=\"contact\" type=\"text\" size=50 placeholder=\"Phone Number\"></td></tr>";
		echo "<tr><td><h4>Blood Group: </h4></td><td><select name=\"BG\"><option>BG</option><option>A+</option><option>A-</option><option>AB+</option><option>AB-</option><option>B+</option><option>B-</option><option>O+</option><option>O-</option></select></td></tr>";
		echo "<tr><td><h4>Gender: </h4></td><td><select name=\"gender\"><option>Male</option><option>Female</option></select></td></tr>";
		/*echo "<tr><td><h4>Room Type: </h4></td><td><select name=\"room\"><option>General</option><option>Pivate</option><option>ICU</option></select></td></tr>";*/
		echo "<tr><td><input type=\"submit\" value=\"Submit\" id=\"wdth2\"></td></tr>";
		echo "</form></table>";
		echo "</div>";
	}
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"del_patient")==0)))
{
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"delete")==0))
	{
		$id=$_POST['id'];
		if(isset($id))
		mysqli_query($con,"delete from patients where Patient_ID='$id'; ");
		header('Location: ?pid=view_patient');
	}
	else
	{
                require("header.php");
		echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=del_patient&option=delete\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"id\" type=\"text\" size=50 placeholder=\"Delete using Patient-ID.\" required/></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/del.gif\" style=\"height:30px\" title=\"Click here to delete\"></td></tr>";
	echo "</form></table>";
	}
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"mod_emp")==0)))
{
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"mod_insert")==0))
	{
		$id=$_GET['id'];
		$name=$_POST['name'];
		$address=$_POST['address'];
		$dob=$_POST['dob'];
		if(isset($contact))
		$contact=$_POST['contact'];
		else $contact="NULL";
		$gender=$_POST['gender'];
		$salary=$_POST['salary'];
                
                
                
		mysqli_query($con,"update Employee SET Name=\"$name\", Address=\"$address\", DOB=\"$dob\", Contact=\"$contact\", Gender=\"$gender\", Salary=\"$salary\" where Employee_ID=\"$id\" ");
		header('Location: ?pid=mod_emp');
	
        
        }
	else if(isset($_GET['option'])&&(strcmp($_GET['option'],"mod_form")==0))
	{
                require("header.php");
		$id=$_POST['id'];
		if(isset($id))
		{
			$result=mysqli_query($con,"select * from employee where Employee_ID='$id'; ");
			if(mysqli_num_rows($result)>0)
			{
				$row=mysqli_fetch_array($result);
				$name=$row['Name'];
				$address=$row['Address'];
				$dob=$row['DOB'];
				$contact=$row['Contact'];
				$gender=$row['Gender'];
				$salary=$row['Salary'];
				echo "<div style=\"width:900px; float:left;\"><h1 align=\"left\">Modify</h1>";
				echo "<table border=0 cellpadding=0 cellspacing=2 align=\"left\"><form action=\"login.php?pid=mod_emp&id=$id&option=mod_insert\" method=\"post\">";
				echo "<name=\"id\" value=\"$name\">";
				echo "<tr><td><h4>Name: </h4></td><td><input name=\"name\" type=\"text\" size=50 value=\"$name\"></td></tr>";
				echo "<tr><td><h4>Address: </h4></td><td><input name=\"address\" type=\"text\" size=50 value=\"$address\"></td></tr>";
				echo "<tr><td><h4>Date of Brith: </h4></td><td><input name=\"dob\" type=\"text\" size=50 value=\"$dob\"></td></tr>";
				echo "<tr><td><h4>Contact: </h4></td><td><input name=\"contact\" type=\"text\" size=50 value=\"$contact\"></td></tr>";
				echo "<tr><td><h4>Salary: </h4></td><td><input name=\"salary\" type=\"text\" size=50 value=\"$salary\"></td></tr>";
				if(strcmp($gender,"Male")==0)
				echo "<tr><td><h4>Gender: </h4></td><td><select name=\"gender\"><option>Male</option><option>Female</option></select></td></tr>";
				else echo "<tr><td><h4>Gender: </h4></td><td><select name=\"gender\"><option>Female</option><option>Male</option></select></td></tr>";
				/*echo "<tr><td><h4>Room Type: </h4></td><td><select name=\"room\"><option>General</option><option>Pivate</option><option>ICU</option></select></td></tr>";*/
				echo "<tr><td><input type=\"submit\" value=\"Modify\" id=\"wdth2\"></td></tr>";
				echo "</form></table>";
				echo "</div>";
			}
                        else {
                            echo "<script type=\"text/javascript\">alert(\"Invalid Employee ID.\")</script>";
                        }
		}
	}
	else
	{
            require("header.php");
		echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=mod_emp&option=mod_form\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"id\" type=\"text\" size=50 placeholder=\"Modify using Employee-ID.\" required/></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/mod.gif\" style=\"height:30px\" title=\"Click here to modify\"></td></tr>";
	echo "</form></table>";
	}
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"mod_patient")==0)))
{
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"mod_insert")==0))
	{
		$id=$_GET['id'];
		$name=$_POST['name'];
		$address=$_POST['address'];
		$dob=$_POST['dob'];
		if(isset($contact))
		$contact=$_POST['contact'];
		else $contact="NULL";
		$gender=$_POST['gender'];
		$bg=$_POST['BG'];
		//echo "update Patients SET Name=\"$name\", Address=\"$address\", DOB=\"$dob\", Contact=\"$contact\", Gender=\"$gender\", Blood_Group=\"$bg\" where Patient_ID=$id;";
		mysqli_query($con,"update Patients SET Name=\"$name\", Address=\"$address\", DOB=\"$dob\", Contact=\"$contact\", Gender=\"$gender\", Blood_Group=\"$bg\" where Patient_ID=\"$id\" ");
		header('Location: ?pid=view_patient');
                require("header.php");
	}
	else if(isset($_GET['option'])&&(strcmp($_GET['option'],"mod_form")==0))
	{
                require("header.php");
		$id=$_POST['id'];
		if(isset($id))
		{
			$result=mysqli_query($con,"select * from patients where Patient_ID='$id'; ");
			if(isset($result))
			{
				$row=mysqli_fetch_array($result);
				$name=$row['Name'];
				$address=$row['Address'];
				$dob=$row['DOB'];
				$contact=$row['Contact'];
				$gender=$row['Gender'];
				$bg=$row['Blood_Group'];
				echo "<div style=\"width:900px; float:left;\"><h1 align=\"left\">Modify</h1>";
				echo "<table border=0 cellpadding=0 cellspacing=2 align=\"left\"><form action=\"login.php?pid=mod_patient&id=$id&option=mod_insert\" method=\"post\">";
				echo "<name=\"id\" value=\"$name\">";
				echo "<tr><td><h4>Name: </h4></td><td><input name=\"name\" type=\"text\" size=50 value=\"$name\"></td></tr>";
				echo "<tr><td><h4>Address: </h4></td><td><input name=\"address\" type=\"text\" size=50 value=\"$address\"></td></tr>";
				echo "<tr><td><h4>Date of Brith: </h4></td><td><input name=\"dob\" type=\"text\" size=50 value=\"$dob\"></td></tr>";
				echo "<tr><td><h4>Contact: </h4></td><td><input name=\"contact\" type=\"text\" size=50 value=\"$contact\"></td></tr>";
				echo "<tr><td><h4>Blood Group: </h4></td><td><select name=\"BG\"><option >$bg</option><option>A+</option><option>A-</option><option>AB+</option><option>AB-</option><option>B+</option><option>B-</option><option>O+</option><option>O-</option></select></td></tr>";
				if(strcmp($gender,"Male")==0)
				echo "<tr><td><h4>Gender: </h4></td><td><select name=\"gender\"><option>Male</option><option>Female</option></select></td></tr>";
				else echo "<tr><td><h4>Gender: </h4></td><td><select name=\"gender\"><option>Female</option><option>Male</option></select></td></tr>";
				/*echo "<tr><td><h4>Room Type: </h4></td><td><select name=\"room\"><option>General</option><option>Pivate</option><option>ICU</option></select></td></tr>";*/
				echo "<tr><td><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/mod.gif\" style=\"height:30px\" title=\"Click here to modify\"></td></tr>";
				echo "</form></table>";
				echo "</div>";
			}
		}
	}
	else
	{
                require("header.php");
		echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=mod_patient&option=mod_form\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"id\" type=\"text\" size=50 placeholder=\"Modify using Patient-ID.\" required/></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Submit\" src=\"images/mod.gif\" style=\"height:30px\" title=\"Click here to modify\"></td></tr>";
	echo "</form></table>";
	}
}
else if(isset($_GET['option'])&&isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_patient")==0))&&((strcmp($_GET['option'],"search_tools")==0)))
{
        require("header.php");
	echo "<table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=view_patient&option=search_val\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_patient\" type=\"text\" size=50 placeholder=\"Search by Name, Patient-ID.\"></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\" title=\"Click here to start searching\"></td><td style=\"border-bottom: #FFFFFF\"><a class=\"button\" style=\"text-decoration: none;\" href=\"?pid=view_patient&option=search_tools\" title=\"Click here for advanced search options\">&nbsp;Advanced Search</a></td></tr>";
	echo "<div style=\"width:900px;\">";
	echo "<br/><table border=0 cellpadding=4 cellspacing=1 height=5>";
	echo "<tr><td><b>Filters: </b></td><td style=\"border-bottom: #FFFFFF\"><input name=\"age\" type=\"text\" size=3 placeholder=\"Age\"></td><td style=\"border-bottom: #FFFFFF\"><select name=\"BG\"><option>BG</option><option>A+</option><option>A-</option><option>AB+</option><option>AB-</option><option>B+</option><option>B-</option><option>O+</option><option>O-</option></select></td><td style=\"border-bottom: #FFFFFF\"><select name=\"gender\"><option>Male</option><option>Female</option><option>All</option></select></td><td style=\"border-bottom: #FFFFFF\"><input name=\"aname\" type=\"text\" size=25 placeholder=\"Accompany's Name\"></td><!--<td style=\"border-bottom: #FFFFFF\"><input name=\"room_no\" type=\"text\" size=27 placeholder=\"Room Number (Eg. ICU-001)\">--></td></tr>";
	echo "</div>";
	echo "</form></table>";
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_patient")==0)||(strcmp($_GET['pid'],"patient_details")==0)))
{
        require("header.php");
	echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=view_patient&option=search_val\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_patient\" type=\"text\" size=50 placeholder=\"Search by Name, Patient-ID.\"></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\" title=\"Click here to start searching\"></td><td style=\"border-bottom: #FFFFFF\"><a class=\"button\" style=\"text-decoration: none;\" href=\"login.php?pid=view_patient&option=search_tools\" title=\"Click here for advanced search options\">&nbsp;Advanced Search</a></td></tr>";
	echo "</form></table>";
	if((strcmp($_GET['pid'],"patient_details")==0))
	{
		$searchby=$_GET['id'];
		$result=mysqli_query($con,"select * from patients where Patient_ID='$searchby'");
		echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
		$row=mysqli_fetch_array($result);
		$name=$row['Name'];
		$eid=$row['Patient_ID'];
		//$dept=$row['Dept_No'];
		$gender=$row['Gender'];
		$contact=$row['Contact'];
		$dob=$row['DOB'];
		$add=$row['Address'];
		$bg=$row['Blood_Group'];
		echo "<h3>Patient Details</h3>";
		echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
		echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
		echo "<tr><td>Patient ID: </td><td>$eid</td></tr>";
		echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
		echo "<tr><td>Department: </td><td></td></tr>";
		echo "<tr><td>Contact: </td><td>$contact</td></tr>";
		echo "<tr><td>Gender: </td><td>$gender</td></tr>";
		echo "<tr><td>Address: </td><td>$add</td></tr>";
		echo "<tr><td>Blood Group: </td><td>$bg</td></tr>";
		echo "</table>";
		$flag=1;
	}
	else if(isset($_GET['option'])&&strcmp($_GET['option'],"search_val")==0)
	{
		if(!empty($_POST['search_patient']))
		$searchby=$_POST['search_patient'];
		else $searchby='%';
		if(!empty($_POST['age']))
		$searchbyage=$_POST['age'];
		if(!empty($_POST['gender'])&&(strcmp($_POST['gender'],"All")))
		$searchbysex=$_POST['gender'];
		if(!empty($_POST['BG'])&&(strcmp($_POST['BG'],"BG")))
		$searchbybg=$_POST['BG'];
		if(!empty($_POST['aname']))
		$searchbyaname=$_POST['aname'];
		$flag=0;
		if(!empty($searchby)&&(($searchby[0]=='P')||($searchby[0]=='p')))
		{
			if(empty($searchbyaname))
			$result=mysqli_query($con,"select * from patients where Patient_ID='$searchby'");
			else $result=mysqli_query($con,"select * from patients p, accompanies a where p.Patient_ID='$searchby' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%'");
			if(!empty($result) && mysqli_num_rows($result)==1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				$row=mysqli_fetch_array($result);
				$name=$row['Name'];
				$eid=$row['Patient_ID'];
				//$dept=$row['Dept_No'];
				$gender=$row['Gender'];
				$contact=$row['Contact'];
				$dob=$row['DOB'];
				$add=$row['Address'];
				$bg=$row['Blood_Group'];
				echo "<h3>Patient Details</h3>";
				echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
				echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
				echo "<tr><td>Patient ID: </td><td>$eid</td></tr>";
				echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
				echo "<tr><td>Department: </td><td></td></tr>";
				echo "<tr><td>Contact: </td><td>$contact</td></tr>";
				echo "<tr><td>Gender: </td><td>$gender</td></tr>";
				echo "<tr><td>Address: </td><td>$add</td></tr>";
				echo "<tr><td>Blood Group: </td><td>$bg</td></tr>";
				echo "</table>";
				$flag=1;
			}
		}
		if(!empty($searchby)&&($flag==0))
		{
			if(!empty($searchbysex)&&!empty($searchbyage)&&!empty($searchbyaname)&&!empty($searchbybg))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%' and p.Gender LIKE '$searchbysex' and floor(DATEDIFF(CURDATE(),p.DOB)/365)=$searchbyage and p.Blood_Group LIKE '$searchbybg'");
			else if(!empty($searchbysex)&&!empty($searchbyage)&&!empty($searchbyaname))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%' and p.Gender LIKE '$searchbysex' and floor(DATEDIFF(CURDATE(),p.DOB)/365)=$searchbyage");
			else if(!empty($searchbysex)&&!empty($searchbybg)&&!empty($searchbyaname))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%' and p.Gender LIKE '$searchbysex' and p.Blood_Group LIKE '$searchbybg'");
			else if(!empty($searchbysex)&&!empty($searchbyage)&&!empty($searchbybg))
			$result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%' and Gender LIKE '$searchbysex' and floor(DATEDIFF(CURDATE(),p.DOB)/365)=$searchbyage and Blood_Group LIKE '$searchbybg'");
			else if(!empty($searchbyaname)&&!empty($searchbyage)&&!empty($searchbybg))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%' and floor(DATEDIFF(CURDATE(),p.DOB)/365)=$searchbyage and p.Blood_Group LIKE '$searchbybg'");
			else if(!empty($searchbysex)&&!empty($searchbybg))
			$result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%' and Gender LIKE '$searchbysex' and Blood_Group LIKE '$searchbybg' ");
			else if(!empty($searchbysex)&&!empty($searchbyage))
			$result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%' and Gender LIKE '$searchbysex' and floor(DATEDIFF(CURDATE(),DOB)/365)=$searchbyage");
			else if(!empty($searchbybg)&&!empty($searchbyage))
			$result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%' and Blood_Group LIKE '$searchbybg' and floor(DATEDIFF(CURDATE(),DOB)/365)=$searchbyage");
			else if(!empty($searchbysex)&&!empty($searchbyaname))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%' and p.Gender LIKE '$searchbysex' ");
			else if(!empty($searchbyage)&&!empty($searchbyaname))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%' and floor(DATEDIFF(CURDATE(),p.DOB)/365)=$searchbyage");
			else if(!empty($searchbyaname)&&!empty($searchbybg))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%' and p.Blood_Group LIKE '$searchbybg'");
			else if(!empty($searchbysex))
			$result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%' and Gender LIKE '$searchbysex' ");
			else if(!empty($searchbybg))
			$result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%' and Blood_Group LIKE '$searchbybg' ");
			else if(!empty($searchbyage))
			$result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%' and floor(DATEDIFF(CURDATE(),DOB)/365)=$searchbyage");
			else if(!empty($searchbyaname))
			$result=mysqli_query($con,"select p.Name,p.Patient_ID,p.Gender,p.Contact,p.DOB,p.Address,p.Blood_Group from patients p, accompanies a where p.Name LIKE '%$searchby%' and p.Patient_ID=a.Patient_ID and a.Name LIKE '%$searchbyaname%'");
			else $result=mysqli_query($con,"select * from patients where Name LIKE '%$searchby%'");
			if(!empty($result) && mysqli_num_rows($result)==1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				$row=mysqli_fetch_array($result);
				$name=$row['Name'];
				$eid=$row['Patient_ID'];
				//$dept=$row['Dept_No'];
				$gender=$row['Gender'];
				$contact=$row['Contact'];
				$dob=$row['DOB'];
				$add=$row['Address'];
				$bg=$row['Blood_Group'];
				echo "<h3>Patient Details</h3>";
				echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
				echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
				echo "<tr><td>Patient ID: </td><td>$eid</td></tr>";
				echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
				//echo "<tr><td>Department: </td><td></td></tr>";
				echo "<tr><td>Contact: </td><td>$contact</td></tr>";
				echo "<tr><td>Gender: </td><td>$gender</td></tr>";
				echo "<tr><td>Address: </td><td>$add</td></tr>";
				echo "<tr><td>Blood Group: </td><td>$bg</td></tr>";
				echo "</table>";
				$flag=1;
			}
			else if(!empty($result) && mysqli_num_rows($result)>1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				echo "<div style=\"width:900px; float:left;\">";
				for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
				{
					$row=mysqli_fetch_array($result);
					$name=$row['Name'];
					$eid=$row['Patient_ID'];
					$dob=$row['DOB'];
					echo "<div style=\"width:900px; float:left;\">";
					echo "<h3 align=\"left\"><a href=\"?pid=patient_details&id=$eid\">$name</a></h3>";
					echo "<p align=\"left\"><b>Patient ID:</b> $eid<br/><b>Date of Birth:</b> $dob</p>";
					echo "</div>";
				}
				echo "</div>";
				$flag=1;
			}
		}
		if($flag==0)
		echo "<i><b>No match found.</b></i>";
	}
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_accompanies")==0)||(strcmp($_GET['pid'],"accompany_details")==0)))
{
        require("header.php");
	echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=view_accompanies&option=search_val\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_accompany\" type=\"text\" size=50 placeholder=\"Search by Patient's Name, Patient-ID.\"></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\" title=\"Click here to start searching\"></td></tr>";
	echo "</form></table>";
	if((strcmp($_GET['pid'],"accompany_details")==0))
	{
		$id=$_GET['id'];
		$name=$_GET['name'];
		$result=mysqli_query($con,"select * from accompanies where Patient_ID='$id' && Name='$name' ");
		if(!empty($result) && mysqli_num_rows($result)==1)
		{
			echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
			$row=mysqli_fetch_array($result);
			$name=$row['Name'];
			$eid=$row['Patient_ID'];
			//$dept=$row['Dept_No'];
			$gender=$row['Gender'];
			$contact=$row['Contact'];
			$dob=$row['DOB'];
			$add=$row['Address'];
			$rel=$row['Relationship'];
			echo "<h3>Accompany's Details</h3>";
			echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
			echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
			echo "<tr><td>Patient ID: </td><td>$eid</td></tr>";
			echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
			echo "<tr><td>Contact: </td><td>$contact</td></tr>";
			echo "<tr><td>Gender: </td><td>$gender</td></tr>";
			echo "<tr><td>Address: </td><td>$add</td></tr>";
			echo "<tr><td>Relation: </td><td>$rel</td></tr>";
			echo "</table>";
			$flag=1;
		}
	}
	if((isset($_GET['option']))&&(strcmp($_GET['option'],"search_val")==0))
	{
		if(!empty($_POST['search_accompany']))
		$searchby=$_POST['search_accompany'];
		else $searchby='%';
		$flag=0;
		if(!empty($searchby)&&(($searchby[0]=='P')||($searchby[0]=='p')))
		{
			$result=mysqli_query($con,"select * from accompanies where Patient_ID='$searchby'");
			if(!empty($result) && mysqli_num_rows($result)==1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				$row=mysqli_fetch_array($result);
				$name=$row['Name'];
				$eid=$row['Patient_ID'];
				//$dept=$row['Dept_No'];
				$gender=$row['Gender'];
				$contact=$row['Contact'];
				$dob=$row['DOB'];
				$add=$row['Address'];
				$rel=$row['Relationship'];
				echo "<h3>Accompany's Details</h3>";
				echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
				echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
				echo "<tr><td>Patient ID: </td><td>$eid</td></tr>";
				echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
				echo "<tr><td>Contact: </td><td>$contact</td></tr>";
				echo "<tr><td>Gender: </td><td>$gender</td></tr>";
				echo "<tr><td>Address: </td><td>$add</td></tr>";
				echo "<tr><td>Relation: </td><td>$rel</td></tr>";
				echo "</table>";
				$flag=1;
			}
			else if(!empty($result) && mysqli_num_rows($result)>1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				echo "<div style=\"width:900px; float:left;\">";
				for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
				{
					$row=mysqli_fetch_array($result);
					$name=$row['Name'];
					$eid=$row['Patient_ID'];
					$rel=$row['Relationship'];
					echo "<div style=\"width:900px; float:left;\">";
					echo "<h3 align=\"left\"><a href=\"?pid=accompany_details&id=$eid&name=$name\">$name</a></h3>";
					echo "<p align=\"left\"><b>Patient ID:</b> $eid<br/><b>Realtion:</b> $rel</p>";
					echo "</div>";
				}
				echo "</div>";
				$flag=1;
			}
		}
		if(!empty($searchby)&&($flag==0))
		{
			$result=mysqli_query($con,"select * from patients p,accompanies a where p.Patient_ID=a.Patient_ID and p.Name LIKE '%$searchby%'");
			if(!empty($result) && mysqli_num_rows($result)==1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				$row=mysqli_fetch_array($result);
				$name=$row['Name'];
				$eid=$row['Patient_ID'];
				//$dept=$row['Dept_No'];
				$gender=$row['Gender'];
				$contact=$row['Contact'];
				$dob=$row['DOB'];
				$add=$row['Address'];
				$rel=$row['Relationship'];
				echo "<h3>Accompany's Details</h3>";
				echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
				echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
				echo "<tr><td>Patient ID: </td><td>$eid</td></tr>";
				echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
				echo "<tr><td>Contact: </td><td>$contact</td></tr>";
				echo "<tr><td>Gender: </td><td>$gender</td></tr>";
				echo "<tr><td>Address: </td><td>$add</td></tr>";
				echo "<tr><td>Relation: </td><td>$rel</td></tr>";
				echo "</table>";
				$flag=1;
			}
			else if(!empty($result) && mysqli_num_rows($result)>1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				echo "<div style=\"width:900px; float:left;\">";
				for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
				{
					$row=mysqli_fetch_array($result);
					$name=$row['Name'];
					$eid=$row['Patient_ID'];
					$rel=$row['Relationship'];
					echo "<div style=\"width:900px; float:left;\">";
					echo "<h3 align=\"left\"><a href=\"?pid=accompany_details&id=$eid&name=$name\">$name</a></h3>";
					echo "<p align=\"left\"><b>Patient ID:</b> $eid<br/><b>Realtion:</b> $rel</p>";
					echo "</div>";
				}
				echo "</div>";
				$flag=1;
			}
			if($flag==0)
			echo "<i><b>No match found.</b></i>";
		}
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"logout")==0))
{
	$username=$_COOKIE['username'];
	$sessionid=$_COOKIE['PHPSESSID'];
	if(mysqli_query($con,"select * from session where username='$username' and id='$sessionid'"))
	{
		$result=mysqli_query($con,"delete from session where username='$username'and id='$sessionid'");
		setcookie("username",$username,time()-3600);
                //setcookie("username",$_POST['username'],time()-3600);
		unset($_COOKIE['PHPSESSID']);
                header('Location: index.php');
                
	}
        require("header.php");
	echo "</div>";
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_dept")==0)||(strcmp($_GET['pid'],"dept_details")==0)||(strcmp($_GET['pid'],"emp_details")==0)))
{
        require("header.php");
	if((strcmp($_GET['pid'],"view_dept")==0))
	{
		$result=mysqli_query($con,"select * from departments");
		echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
		echo "<div style=\"width:900px; float:left;\">";
		for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
		{
			$row=mysqli_fetch_array($result);
			$name=$row['Name'];
			$d_id=$row['Dept_No'];
			echo "<div style=\"width:900px; float:left;\">";
			echo "<h3 align=\"left\"><a href=\"?pid=dept_details&id=$d_id\">$name</a></h3>";
			echo "<p align=\"left\"><b>Department No.:</b> $d_id</p>";
			echo "</div>";
		}
		echo "</div>";			
	}
	else if((strcmp($_GET['pid'],"dept_details")==0))
	{
		$searchby=$_GET['id'];
		$result=mysqli_query($con,"select * from departments where Dept_No='$searchby'");
		echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
		$row=mysqli_fetch_array($result);
		$name=$row['Name'];
		$d_id=$row['Dept_No'];
		$loc=$row['Location'];
		//$nem=$row['NOE'];
		echo "<h3>Department Details</h3>";
		echo "<table border=5 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
		echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
		echo "<tr><td>Deparment No.: </td><td>$d_id</td></tr>";
		echo "<tr><td>Location: </td><td>$loc</td></tr>";
		//echo "<tr><td>Number of employees: </td><td>$nem</td></tr>";
		echo "</table>";
		echo "<h4 align=\"left\"><u>Employee Names</u>:</h4>";
		$result2=mysqli_query($con,"select * from employee where Dept_No='$d_id'");
		echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
		echo "<div style=\"width:900px; float:left;\">";
		if(!empty($result2))
		for($i=0;$i<mysqli_num_rows($result2);$i=$i+1)
		{
			$j=$i+1;
			$row=mysqli_fetch_array($result2);
			$name=$row['Name'];
			$eid=$row['Employee_ID'];
			echo "<div style=\"width:900px; float:left;\">";
			echo "<p align=\"left\">$j.)<a href=\"?pid=emp_details&id=$eid\">$name</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEmployee ID: </b> $eid</p>";
			echo "</div>";
		}
		echo "</div>";
	}
	else if((strcmp($_GET['pid'],"emp_details")==0))
	{
		$searchby=$_GET['id'];
		$result=mysqli_query($con,"select * from employee where Employee_ID='$searchby'");
		echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
		$row=mysqli_fetch_array($result);
		$name=$row['Name'];
		$eid=$row['Employee_ID'];
		$add=$row['Address'];
		$dob=$row['DOB'];
		$con1=$row['Contact'];
		$sex=$row['Gender'];
		$dept=$row['Dept_No'];
		$dept1=mysqli_query($con,"select * from Departments where Dept_No='$dept'");
		$row1=mysqli_fetch_array($dept1);
		$dept_n=$row1['Name'];
		echo "<h3>Employee Details</h3>";
		echo "<table border=5 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
		echo "<tr><td width=5%>Name: </td><td width=50%>$name</td></tr>";
		echo "<tr><td>Employee ID: </td><td>$eid</td></tr>";
		echo "<tr><td>Address: </td><td>$add</td></tr>";
		echo "<tr><td>DOB: </td><td>$dob</td></tr>";
		echo "<tr><td>Contact: </td><td>$con1</td></tr>";
		echo "<tr><td>Gender: </td><td>$sex</td></tr>";
		echo "<tr><td>Department Name: </td><td>$dept_n  &nbsp&nbsp&nbsp&nbsp $dept</td></tr>";
		echo "</table>";
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"del_dept")==0))
{
	if(isset($_GET['option'])&&strcmp($_GET['option'],"del_id")==0)
	{
		if(!empty($_POST['dept_id']))
		$d_id=$_POST['dept_id'];
		$res=mysqli_query($con,"select * from departments where Dept_No='$d_id';");
		if(mysqli_num_rows($res)!=0)
		{
		mysqli_query($con,"delete from departments where Dept_No='$d_id';");
		header('Location: login.php?pid=view_dept');
		}
		else
		echo "<script type=\"text/javascript\">alert(\"Invalid Department No.\")</script>";
	}
        require("header.php");
	echo "<br/><table border=1 cellpadding=0 cellspacing=0 height=0 style=\"\"><form action=\"login.php?pid=del_dept&option=del_id\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"dept_id\" type=\"text\" size=50 placeholder=\"Enter Department No. to delete\" required></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Submit\" style=\"height:30px\" title=\"Click here to delete\"></td></tr>";
	echo "</form></table>";
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"add_dept")==0))
{
	$flag=0;
	if((!empty($_POST["nm"]))&&(!empty($_POST["id"]))&&(!empty($_POST["loc"])))
	{
		$n=$_POST["nm"];
		$i=$_POST["id"];
		$l=$_POST["loc"];
		$result=mysqli_query($con,"select * from departments where Dept_No='$i';");
		if(mysqli_num_rows($result)==0)
		{
			mysqli_query($con,"insert into departments values (\"$n\",\"$l\",\"$i\");");
			header('Location: login.php?pid=view_dept');
		}
		else
		echo ("<b><font color=\"RED\" SIZE=\"4\">*Department No. already exists*</font></b>");
	}
        
        require("header.php");
        echo("<h2 align=\"centre\"><u> New Department </u></h1>");
	echo("<form align=\"centre\" #action=\"#\" method =\"post\">");
	echo("<table><tr><td><br />Name &nbsp &nbsp &nbsp </td><td><br /><input type=\"text\" name=\"nm\"/ required ></td></tr>");
	echo("<tr><td><br />Department Number &nbsp &nbsp &nbsp </td><td><br /><input type=\"text\" name=\"id\"/ required ></td></tr>");
	echo("<tr><td><br />Location &nbsp &nbsp &nbsp </td><td><br /><input type=\"text\" name=\"loc\"/required ></td></tr>");
	echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Submit\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
	echo("</table></form>");
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"mod_dept")==0)))
{
        require("header.php");
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"mod_insert")==0))
	{
		$id=$_GET['id'];
		$name=$_POST['name'];
		$loc=$_POST['loc'];
		mysqli_query($con,"update Departments SET Name=\"$name\", Location=\"$loc\" where Dept_No=\"$id\";");
		header('Location: ?pid=view_dept');
	}
	else if(isset($_GET['option'])&&(strcmp($_GET['option'],"mod_form")==0))
	{
		$id=$_POST['id'];
		if(isset($id))
		{
			$result=mysqli_query($con,"select * from departments where Dept_No='$id'; ");
			if(isset($result)&&(mysqli_num_rows($result)!=0	))
			{
				$row=mysqli_fetch_array($result);
				$name=$row['Name'];
				$loc=$row['Location'];
				echo("<h2 align=\"centre\"><u> Modify Department</u></h1>");
				echo $id;
				echo("<form align=\"centre\" action=\"login.php?pid=mod_dept&option=mod_insert&id=$id\" method =\"post\">");
				//echo("<table><tr><td><br />Name &nbsp &nbsp &nbsp </td><td><br /><input type=\"text\" size=40 name=\"name\" value=\"$name\"/ required ></td></tr>");
				echo ("<table><tr><td>Name:</td><td><input name=\"name\" type=\"text\" size=40 value=\"$name\"></td></tr>");
				echo("<tr><td><br />Location &nbsp &nbsp &nbsp </td><td><br /><input type=\"text\" size=40 name=\"loc\"value=\"$loc\"/required ></td></tr>");
				echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Modify\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
				echo("</table></form>");
			}
			else
			{
			echo ("<h4><font color=\"RED\" SIZE=\"4\">*Invalid Department No.*</font></h4>");
			echo ("<h5><a href=\"login.php?pid=mod_dept\">Go Back</h5>");
			}
		}
	}
	else
	{
	echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=mod_dept&option=mod_form\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"id\" type=\"text\" size=50 placeholder=\"Enter Department No.\" required/></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"submit\"title=\"Click here to modify\"></td></tr>";
	echo "</form></table>";
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"allot_room")==0))
{	
        require("header.php");
	echo("<h2 align=\"centre\"><u> Room Allotment </u></h1>");
	echo("<form align=\"centre\" #action=\"#\" method =\"post\">");
	echo("<table><tr><td><h4>Room Type:</h4></td><td><select name=\"type\"><option value=\"GEN\">General</option><option value=\"PRI\">Private</option><option value=\"ICU\">ICU</option></select></td></tr>");
	echo("<tr><td><h4>Patient ID:</h4></td><td><input type=\"text\" name=\"id\"/required ></td></tr>");
	echo("<tr><td><h4>Allotment(Today's)Date:</h4></td><td><input type=\"date\" name=\"date\"/ placeholder=\"yyyy-mm-dd\"required ></td></tr>");
	echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Submit\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
	echo("</table></form>");
	if((!empty($_POST["type"]))&&(!empty($_POST["id"]))&&(!empty($_POST["date"])))
	{
	$type=$_POST["type"];
	$id=$_POST["id"];
	$date=$_POST["date"];
	$res1=mysqli_query($con,"select * from patients where Patient_ID='$id';");
	if(mysqli_num_rows($res1)==0)
	{
		echo "<script type=\"text/javascript\">alert(\"Invalid Patient ID\")</script>";
	}
	else
	{
	$res2=mysqli_query($con,"select * from room where ((type='$type') and (patient_id is null));");
	if(mysqli_num_rows($res2)!=0)
	{
		$row=mysqli_fetch_array($res2);
		$r_no=$row['Room_No'];
		mysqli_query($con,"insert into room_given values (\"$r_no\",\"$id\",'$date',null);");
		mysqli_query($con,"update room SET Patient_ID=\"$id\" where Room_No=\"$r_no\";");
		//if(mysqli_affected_rows()!=0)
		echo("Alloted Succesfully <br/> Room No.= ");
		echo($r_no);
	}
	else
		echo "<script type=\"text/javascript\">alert(\"No Vacancy\")</script>";
	}
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"allot_vehicle")==0))
{	
        require("header.php");
	echo("<h2 align=\"centre\"><u> Vehicle Allotment to Patients </u></h1>");
	echo("<form align=\"centre\" #action=\"#\" method =\"post\">");
	echo("<table><tr><td><h4>Vehicle Type:</h4></td><td><select name=\"type\"><option value=\"Ambulance\">Ambulance</option><option value=\"Taxi\">Taxi</option></select></td></tr>");
	echo("<tr><td><h4>Patient ID:</h4></td><td><input type=\"text\" name=\"id\"/required ></td></tr>");
	echo("<tr><td><h4>Allotment(Today's)Date:</h4></td><td><input type=\"date\" name=\"date\"/ placeholder=\"YYYY-MM-DD\"required ></td></tr>");
	echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Submit\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
	echo("</table></form>");
	if((!empty($_POST["type"]))&&(!empty($_POST["id"]))&&(!empty($_POST["date"])))
	{
	$type=$_POST["type"];
	$id=$_POST["id"];
	$date=$_POST["date"];
	$res1=mysqli_query($con,"select * from patients where Patient_ID='$id';");
	if(mysqli_num_rows($res1)==0)
	echo "<script type=\"text/javascript\">alert(\"Invalid Patient ID\")</script>";
	else
	{
	$res3=mysqli_query($con,"select * from vehicle_given where id='$id' and return_date is null;");
	if(mysqli_num_rows($res3)!=0)
	echo "<script type=\"text/javascript\">alert(\"Patient already has a vehicle issued !!! Return former to reissue.\")</script>";		
	else
	{
	$res2=mysqli_query($con,"select * from vehicles where ((type='$type') and id is null);");
	if(mysqli_num_rows($res2)!=0)
	{
		$row=mysqli_fetch_array($res2);
		$reg_no=$row['Reg_No'];
		mysqli_query($con,"insert into vehicle_given values (\"$reg_no\",\"$id\",'$date',null);");
		mysqli_query($con,"update vehicles SET id=$id where Reg_No=\"$reg_no\";");
		echo("Alloted Succesfully <br/> Vehicle Reg No.= ");
		echo($reg_no);
	}
	else
		echo "<script type=\"text/javascript\">alert(\"No Free Vehicle.\")</script>";
	}
	}
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"allot_ms")==0))
{
        require("header.php");
	echo("<h2 align=\"centre\"><u> Assign Medical Duty for a Room </u></h1>");
	echo("<form align=\"centre\" #action=\"#\" method =\"post\">");
	echo("<table><tr><td><h4>Room_No: </h4></td><td><input type=\"text\" size=60 name=\"room\"/ required ></td></tr>");
	echo("<tr><td><h4>Medical-Staff ID:</h4></td><td><input type=\"text\" size=60 name=\"id\"/ required ></td></tr>");
	echo("<tr><td><h4>Date: </h4></td><td><input type=\"text\" name=\"date\"/ size=60 placeholder=\"YYYY-MM-DD\"required ></td></tr>");
	echo("<tr><td><h4>Joining Time: </h4></td><td><input type=\"text\" name=\"jtime\" size=60 placeholder=\"HH:MM:SS\"required ></td></tr>");
	echo("<tr><td><h4>Leave Time: </h4></td><td><input type=\"text\" name=\"ltime\" size=60 placeholder=\"HH:MM:SS\"required ></td></tr>");
	echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Submit\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
	echo("</table></form>");
	if((!empty($_POST["room"]))&&(!empty($_POST["id"]))&&(!empty($_POST["date"]))&&(!empty($_POST["jtime"]))&&(!empty($_POST["ltime"])))
	{
		$room=$_POST["room"];
		$id=$_POST["id"];
		$date=$_POST["date"];
		$jtime=$_POST['jtime'];
		$ltime=$_POST['ltime'];
		$res1=mysqli_query($con,"select * from employee where Employee_ID='$id';");
		$res2=mysqli_query($con,"select * from room where Room_No='$room';");
		if((mysqli_num_rows($res1)==0)||(mysqli_num_rows($res2)==0))
		{
			if(mysqli_num_rows($res1)==0)
			echo "<script type=\"text/javascript\">alert(\"Invalid Employee ID\")</script>";
			else
			echo "<script type=\"text/javascript\">alert(\"Invalid Room Number\")</script>";
		}
		else if((strcmp($id[0],'M')!=0)&&(strcmp($id[1],'S')==0))
		echo "<script type=\"text/javascript\">alert(\"Employee should be a Medical Staff NOT Doctor\")</script>";
		else if(strcmp($jtime,$ltime)>0)
		echo "<script type=\"text/javascript\">alert(\"Leaving Time should be after joining Time\")</script>";
		else
		{
			if((strcmp($id[0],'M')!=0)&&(strcmp($id[1],'S')!=0))
			{
				$res1=mysqli_query($con,"select * from room where Room_No='$room' and Patient_ID is null;");
				if(mysqli_num_rows($res1)!=0)
					echo "<script type=\"text/javascript\">alert(\"No Patient In Room !!! Doctor can't be assigned\")</script>";
				else
				{
					//if(isset($res1))
					$res2=mysqli_query($con,"select * from room where Room_No='$room';");
					$row1=mysqli_fetch_array($res2);
					$patient=$row1['Patient_ID'];
					$res=mysqli_query($con,"select * from doctors where employee_id='$id';");
					$row=mysqli_fetch_array($res);
					$batch=$row['Batch_No'];
					
					mysqli_query($con,"insert into attended_by values('$patient','$batch','$date');");
					mysqli_query($con,"insert into governed_by values(\"$id\",\"$room\",'$date','$jtime','$ltime');");
					echo "<script type=\"text/javascript\">alert(\"Entry added\")</script>";
				}
			}
			else
			{
			mysqli_query($con,"insert into governed_by values(\"$id\",\"$room\",'$date','$jtime','$ltime');");
			echo "<script type=\"text/javascript\">alert(\"Entry added\")</script>";
			}
		}
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"view_ms")==0))
{
        require("header.php");
	echo "<br/><table border=2cellpadding=0 cellspacing=0 height=10 style=\"\"><form action=\"login.php?pid=view_ms&option=room_id\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"room\" type=\"text\" size=50 placeholder=\"Enter Room No. \" required></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Enter\" style=\"height:30px\" title=\"Click here to delete\"></td></tr>";
	echo "</form></table>";
	if(isset($_GET['option'])&&strcmp($_GET['option'],"room_id")==0)
	{
		//echo "coming";
		if(!empty($_POST['room']))
		$room=$_POST['room'];
		//echo "room=".$room;
		$res=mysqli_query($con,"select * from governed_by where Room_No='$room';");
		if(mysqli_num_rows($res)!=0)
		{
		echo("<br/><table border=1 cellpadding=0 cellspacing=0 height=10 align='centre'><tr><th>&nbsp&nbspEmployee ID&nbsp&nbsp</th><th>&nbsp&nbspRoom No.&nbsp&nbsp</th><th>&nbsp&nbspDate&nbsp&nbsp</th><th>&nbsp&nbspJoining Time&nbsp&nbsp</th><th>&nbsp&nbspLeaving Time&nbsp&nbsp</th></tr>");
		for($i=0;$i<mysqli_num_rows($res);$i=$i+1)
		{
		$row=mysqli_fetch_array($res);
		$emp=$row['Employee_ID'];
		$room=$row['Room_No'];
		$date=$row['Date'];
		$jtime=$row['Join_Time'];
		$ltime=$row['Leave_Time'];
		echo("<tr><td>$emp</td><td>$room</td><td>$date</td><td>$jtime</td><td>$ltime</td></tr>");
		}
		echo("</table>");
		}
		else
		echo "<script type=\"text/javascript\">alert(\"No Allotments to. $room\")</script>";
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"allot_doc")==0))
{
        require("header.php");
	echo("<h2 align=\"centre\"><u> Assign Doctor to Patient </u></h1>");
	echo("<form align=\"centre\" #action=\"#\" method =\"post\">");
	echo("<table><tr><td><h4>Patient ID: </h4></td><td><input type=\"text\" size=60 name=\"pid\"/ required ></td></tr>");
	echo("<tr><td><h4>Doctor-ID:</h4></td><td><input type=\"text\" size=60 name=\"eid\"/ required ></td></tr>");
	echo("<tr><td><h4>Date: </h4></td><td><input type=\"text\" name=\"date\"/ size=60 placeholder=\"YYYY-MM-DD\"required ></td></tr>");
	echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Assign\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
	echo("</table></form>");
	if((!empty($_POST["pid"]))&&(!empty($_POST["eid"]))&&(!empty($_POST["date"])))
	{
		$pid=$_POST["pid"];
		$eid=$_POST["eid"];
		$date=$_POST["date"];
		$pres=mysqli_query($con,"select * from patients where Patient_ID='$pid';");
		$dres=mysqli_query($con,"select * from doctors where Employee_ID='$eid';");
		$row=mysqli_fetch_array($dres);
		if(mysqli_num_rows($pres)==0)
		echo "<script type=\"text/javascript\">alert(\"Invalid Patient ID\")</script>";
		else if(mysqli_num_rows($dres)==0)
		echo "<script type=\"text/javascript\">alert(\"Invalid Doctor ID\")</script>";
		else
		{
		$batch=$row['Batch_No'];
		mysqli_query($con,"insert into attended_by values('$pid','$batch','$date');");
		if(mysqli_affected_rows($con)==1)
		echo "<script type=\"text/javascript\">alert(\"Successfully Assigned\")</script>";
		}
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"unallot_room")==0))
{
        require("header.php");
	echo("<h2 align=\"centre\"><u> Room Un-Allotment </u></h1>");
	echo("<form align=\"centre\" #action=\"#\" method =\"post\">");
	echo("<table><tr><td><h4>Room Number:</h4></td><td><input type=\"text\" name=\"room\"/required ></td></tr>");
	echo("<table><tr><td><h4>Discharge Date:</h4></td><td><input type=\"text\" name=\"date\" placeholder=\"YYYY-MM-DD\" required ></td></tr>");
	echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Unallot\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
	echo("</table></form>");
	if((!empty($_POST["room"]))&&(!empty($_POST["date"])))
	{
	$room=$_POST["room"];
	//echo $room;
	$date=$_POST["date"];
	$res=mysqli_query($con,"select * from room_given where Room_No='$room' and Discharge_Date is null;");
	$row=mysqli_fetch_array($res);
	$allot_date=$row['Allot_date'];
	if(mysqli_num_rows($res)==0)
	echo "<script type=\"text/javascript\">alert(\"Can't Unallot !! \\nEntered room is invalid or currently vacant\")</script>";
	else if(strcmp($allot_date,$date)>0)
	echo "<script type=\"text/javascript\">alert(\"Entered Return Date is invalid !! It must be after allotment date\")</script>";
	else
	{
	mysqli_query($con,"update room set patient_id=null where Room_No='$room';");
	mysqli_query($con,"update room_given set discharge_date='$date' where Room_No='$room' and discharge_date is null;");
	if(mysqli_affected_rows($con)==1)
	echo "<script type=\"text/javascript\">alert(\"Patient Discharged from room\")</script>";
	}
	}
}
else if(isset($_GET['pid'])&&(strcmp($_GET['pid'],"unallot_vehicle")==0))
{
        require("header.php");
	echo("<h2 align=\"centre\"><u> Vehicle Un-Allotment </u></h1>");
	echo("<form align=\"centre\" #action=\"#\" method =\"post\">");
	echo("<table><tr><td><h4>Registration Number:</h4></td><td><input type=\"text\" name=\"reg\"/required ></td></tr>");
	echo("<table><tr><td><h4>Returning Date:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</h4></td><td><input type=\"text\" name=\"date\" placeholder=\"YYYY-MM-DD\" required ></td></tr>");
	echo("<tr><td style=\"border-bottom: #FFFFFF\"><input type=\"submit\" name=\"commit\" value=\"Unallot\" style=\"height:25px\" title=\"Click here to submit\"></td></tr>");
	echo("</table></form>");
	if((!empty($_POST["reg"]))&&(!empty($_POST["date"])))
	{
	$reg=$_POST["reg"];
	//echo $room;
	$date=$_POST["date"];
	$res=mysqli_query($con,"select * from vehicle_given where Reg_No='$reg' and Return_Date is null;");
	$row=mysqli_fetch_array($res);
	$allot_date=$row['Allot_date'];
	if(mysqli_num_rows($res)==0)
	echo "<script type=\"text/javascript\">alert(\"Can't Unallot !! \\n Entered vehicle is invalid or currently unalloted to anyone\")</script>";
	else if(strcmp($allot_date,$date)>0)
	echo "<script type=\"text/javascript\">alert(\"Entered Return Date is invalid !! It must be after allotment date\")</script>";
	else
	{
	mysqli_query($con,"update vehicles set ID=null where Reg_No='$reg';");
	mysqli_query($con,"update vehicle_given set return_date='$date' where Reg_No='$reg' and return_date is null;");
	if(mysqli_affected_rows($con)==1)
	echo "<script type=\"text/javascript\">alert(\"vehicle returned !! \")</script>";
	}
	}
}
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"create_report") == 0)
{
        require("header.php");
	echo "<h1>Add Report Details</h1>";
	echo "<form name=\"form\" method=\"post\" action=\"login.php?pid=create_report\">";
	echo "<table>";
	
	echo "<tr>";
	echo "<td><p>Patient ID: <input type=\"text\" name=\"PID\" size=\"15\" maxlength=\"15\" required/></p>";
	echo "</tr>";
	echo "<tr><td>Dept No.</td><td><select name=\"dept_no\">";
	$result=mysqli_query($con,"select * from departments");
	for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
	{
		$row=mysqli_fetch_array($result);
		$dept=$row['Dept_No'];
		echo "<option>$dept</option>";
	}
	echo "</select></td></tr><tr>";
	echo "<td><p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Enter\" /></p>";
	echo "</tr>";
	echo "</form>";
	if(isset($_POST['PID'])&&isset($_POST['dept_no']))
	{
		//$date=$_POST['date'];
		$sel=mysqli_query($con,"SELECT curdate() date");
		$date=mysqli_fetch_array($sel);
		$date=$date['date'];
		$cal=mysqli_query($con,"SELECT max(Report_No) R FROM medical_report");
		$row=mysqli_fetch_array($cal);
		$val=$row['R'];
		$r_num="R".(substr($val,1)+1);
		$pid=$_POST['PID'];
		$dept_no=$_POST['dept_no'];
		$check = mysqli_query($con,"SELECT * FROM patients where Patient_ID='$pid'");
		//$check1 = mysqli_query("SELECT * FROM medical_report where Patient_ID='$pid'");
		$row=mysqli_num_rows($check);
		//$row1=mysqli_num_rows($check1);
		if($row>0)
		{
			$query = "INSERT INTO medical_report SET Patient_ID='$pid', Report_No='$r_num', R_date='$date'";
			
			$add=mysqli_query($con,$query);
			$query1 = "INSERT INTO give_details SET Department_No='$dept_no', Report_No='$r_num'";
			$add1=mysqli_query($con,$query1);
			echo "<script type=\"text/javascript\">alert(\"Report No. $r_num has been added.\")</script>";
			
		}
		else
			echo "<script type=\"text/javascript\">alert(\"Invalid Patient ID.\")</script>";
	}	
}
/*else if(isset($_GET['pid']) && strcmp($_GET['pid'],"mod_report") == 0)
{   
        require("header.php");
	echo "<h1>Add Report Details</h1>";
	echo "<form name=\"form\" method=\"post\" action=\"login.php?pid=mod_report\">";
	echo "<table>";
	echo "<tr>";
	echo "<td><p>Report No.: <input type=\"text\" name=\"R_num\" size=\"15\" maxlength=\"15\" required/></p>";
	echo "</tr>";
	echo "<tr><td><p>Dept number: <select name=\"dept\">";
	$result=mysqli_query($con,"select * from departments");
	for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
	{
		$row=mysqli_fetch_array($result);
		$dept=$row['Dept_No'];
		echo "<option>$dept</option>";
	}
	echo "</p></td></tr>";
	echo "<tr>";
	echo "<td><p>Room No.: <input type=\"text\" name=\"Room_num\" size=\"15\" maxlength=\"15\" /></p>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Diet:&nbsp &nbsp &nbsp &nbsp &nbsp <textarea type=\"text\" name=\"Diet\" rows=\"3\" cols=\"30\" maxlength=\"200\"></textarea></p>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Enter\" /></p>";
	echo "</tr>";
	echo "</form>";
	if(isset($_POST['R_num']))
	{
		$rnum=$_POST['R_num'];
		$dept=$_POST['dept'];
		$roomnum=$_POST['Room_num'];
		$diet=$_POST['Diet'];
		//echo $rnum;
		$check=mysqli_query($con,"SELECT * FROM give_details where Report_No='$rnum'");
		if(mysqli_num_rows($check)!=0)
		{
			mysqli_query($con,"UPDATE give_details SET Department_No='$dept' where Report_No='$rnum'");
			if(isset($roomnum)||isset($diet))
			{
				if(strcmp($roomnum,"")!=0&&strcmp($diet,"")!=0)
				$query = "UPDATE medical_report SET Room_No='$roomnum',Diet='$diet' where Report_No='$rnum'";
				else if(strcmp($diet,"")!=0)
				$query = "UPDATE medical_report SET Room_No='$roomnum' where Report_No='$rnum'";
				else if(strcmp($roomnum,"")!=0)
				{
					$query = "UPDATE medical_report SET Diet='$diet' where Report_No='$rnum'";
					//echo $query;
				}
				mysqli_query($con,$query);
				//echo mysqli_affected_rows();
				echo "<script type=\"text/javascript\">alert(\"Data Updated.\")</script>";
			}
		}
		else
			echo "<script type=\"text/javascript\">alert(\"Report Number Not Found.\")</script>";
	}	
}*/
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"add_date") == 0)
{
        require("header.php");
	echo "<h1>Add Closing Date</h1>";
	echo "<form name=\"form\" method=\"post\" action=\"login.php?pid=add_date\">";
	echo "<table>";
	echo "<tr>";
	echo "<td><p>Report No. :&nbsp &nbsp &nbsp &nbsp<input type=\"text\" name=\"num\" size=\"15\" maxlength=\"10\" required/></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Closing Date :&nbsp &nbsp &nbsp<input placeholder=\"yyyy-mm-dd\" type=\"text\" name=\"date\" size=\"15\" maxlength=\"15\" required/></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p align=\"center\" ><input type=\"submit\" name=\"submit\" value=\"Enter\" /></p>";
	echo "</tr>";
	echo "</form>";
	if(isset($_POST['date'])&&isset($_POST['num']))
	{
		$date=$_POST['date'];
		$rnum=$_POST['num'];
		$result=mysqli_query($con,"SELECT * FROM medical_report where Report_No='$rnum'");
		$row=mysqli_fetch_array($result);
		$rdate=$row['R_date'];
		if(strcmp($rdate,$date)>0)
			echo "<script type=\"text/javascript\">alert(\"Closing date should be greater then report date.\")</script>";
		else
		{
			$query = "UPDATE medical_report SET C_date='$date' where Report_No='$rnum'";
			$add=mysqli_query($con,$query);
			if(mysqli_affected_rows($con)==0)
				echo "<script type=\"text/javascript\">alert(\"Report Not Found.\")</script>";
			else
				echo "<script type=\"text/javascript\">alert(\"Data Updated.\")</script>";
		}
	}	
}	
else if(isset($_GET['option'])&&isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_report")==0))&&((strcmp($_GET['option'],"search_tools")==0)))
{
        require("header.php");
	echo "<table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=view_report&option=search_val\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_patient\" type=\"text\" size=50 placeholder=\"Search by Report-ID.\"></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\" title=\"Click here to start searching\"></td></tr>";	
	echo "<div style=\"width:900px;\">";
	echo "<br/><table border=0 cellpadding=4 cellspacing=1 height=5>";
	echo "<tr><td><b>Filters: </b></td><td style=\"border-bottom: #FFFFFF\"><input name=\"id\" type=\"text\" size=10 placeholder=\"Patient-ID\"></td><td style=\"border-bottom: #FFFFFF\"><input name=\"date\" type=\"text\" size=10 placeholder=\"Report Date\"></td><td style=\"border-bottom: #FFFFFF\"><input name=\"allot_date\" type=\"text\" size=13 placeholder=\"Allotment Date\"></td><td style=\"border-bottom: #FFFFFF\"><input name=\"dis_date\" type=\"text\" size=13 placeholder=\"Discharge Date\"></td><td style=\"border-bottom: #FFFFFF\">";
	echo "</div>";
	echo "</form></table>";
	
}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"view_report")==0)||(strcmp($_GET['pid'],"patient_report")==0)))
{   
        require("header.php");
	echo "<br/><table border=0 cellpadding=2 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=view_report&option=search_val\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_report\" type=\"text\" size=50 placeholder=\"Search by Report-ID.\"></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\" title=\"Click here to start searching\"></td></tr>";	
	echo "</form></table>";
	if((strcmp($_GET['pid'],"patient_report")==0))
	{
		$searchby=$_GET['id'];
		$result=mysqli_query($con,"select * from medical_report where Report_No='$searchby'");
		echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
		$row=mysqli_fetch_array($result);
		$num=$row['Report_No'];
		$id=$row['Patient_ID'];
		$result2=mysqli_query($con,"select * from patients where Patient_ID='$id'");
		$result4=mysqli_query($con,"select * from diagnosis where Report_No='$num'");
		$dept=mysqli_query($con,"select * from departments d,give_details g where g.Report_No='$num' and g.Department_No=d.Dept_No");
		$deptrow=mysqli_fetch_array($dept);
		$deptname=$deptrow['Name'];
		$row2=mysqli_fetch_array($result2);
		//$dept=$row['Dept_No'];
		$name=$row2['Name'];
		$dob=$row2['DOB'];
		$gender=$row2['Gender'];
		$bg=$row2['Blood_Group'];
		$date=$row['R_date'];
		$cdate=$row['C_date'];
		$room=$row['Room_No'];
		$result3=mysqli_query($con,"select * from room where Room_No='$room'");
		$row3=mysqli_fetch_array($result3);
		$result5=mysqli_query($con,"select * from Room_Given where Patient_ID='$id'");
		$row5=mysqli_fetch_array($result5);
		$allotdate=$row5['Allot_date'];
		$disdate=$row5['Discharge_date'];
		$roomtype=$row3['Type'];
		$diet=$row['Diet'];
		//$pay=$row['Payment'];
		
		$cost1=mysqli_query($con,"select b.Rent from medical_report a, room b where a.Patient_ID=b.Patient_ID and Report_No='$num'");
		$costrow1=mysqli_fetch_array($cost1);
		$days=mysqli_query($con,"select DATEDIFF('$disdate','$allotdate') as day;");
		$dayrow=mysqli_fetch_array($days);
		$days=$dayrow['day'];
		if($days==0)
		{
			$days=1;
		}
		$room_cost=$costrow1['Rent']*$days;
		
		$cost1=mysqli_query($con,"select b.Rent from medical_report a, vehicles b where a.Patient_ID=b.ID and Report_No='$num'");
		$costrow1=mysqli_fetch_array($cost1);
		$days1=mysqli_query($con,"select b.Allot_date,b.Return_date from medical_report a,vehicle_given b where a.Patient_ID=b.ID and Report_No='$num'");
		$days=mysqli_fetch_array($days1);
		$days=mysqli_query($con,"select datediff('$days[Return_date]','$days[Allot_date]') as day;");
		$dayrow=mysqli_fetch_array($days);
		$days=$dayrow['day'];
		if($days==0)
		{
			$days=1;
		}
		$veh_cost=$costrow1['Rent']*$days;
		
				
		
		
		
		echo "<h3>Medical Report</h3>";
		echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
		echo "<tr><td width=5%>Report No: </td><td width=50%>$num</td></tr>";
		echo "<tr><td>Date: </td><td>$date</td></tr>";
		echo "<tr><td>Patient ID: </td><td>$id</td></tr>";
		echo "<tr><td>Patient Name: </td><td>$name</td></tr>";
		echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
		echo "<tr><td>Gender: </td><td>$gender</td></tr>";
		echo "<tr><td>Blood Group: </td><td>$bg</td></tr>";
		echo "<tr><td>Department: </td><td>$deptname</td></tr>";
		echo "<tr><td>Room No.: </td><td>$room</td></tr>";
		echo "<tr><td>Room Type: </td><td>$roomtype</td></tr>";
		echo "<tr><td>Allotment Date: </td><td>$allotdate</td></tr>";
		echo "<tr><td>Discharge Date: </td><td>$disdate</td></tr>";
		echo "<tr><td>Diagnosis: </td>";
		echo "<td><table border=\"2\">";
		echo "<tr><th>Test Date</th><th>Test Name</th><th>Result</th><th>Cost</th></tr>";
		$total=$room_cost+$veh_cost;
		for($i=0;$i<mysqli_num_rows($result4);$i=$i+1)
		{
			$row4=mysqli_fetch_array($result4);
			$date=$row4['Test_date'];
			$name=$row4['Tests'];
			$res=$row4['T_result'];
			$cost=$row4['Cost'];
			$total=$total+$cost;
			echo "<tr><td>$date</td><td>$name</td><td>$res</td><td>$cost</td></tr>";
		}
		echo "</table>";
		echo "<tr><td>Diet: </td><td>$diet</td></tr>";
		echo "<tr><td>Payment: </td><td>$total</td></tr>";
		echo "<tr><td>Report Closing Date: </td><td>$cdate</td></tr>";
		echo "</table></td></tr>";
		$flag=1;
	}
	else if(isset($_GET['option'])&&strcmp($_GET['option'],"search_val")==0)
	{
		if(!empty($_POST['search_report']))
		$searchby=$_POST['search_report'];
		else $searchby='%';
		if(!empty($_POST['id']))
		$searchbyid=$_POST['id'];
		if(!empty($_POST['date']))
		$searchbydate=$_POST['date'];
		if(!empty($_POST['allot_date']))
		$searchbyallot=$_POST['allot_date'];
		if(!empty($_POST['dis_date']))
		$searchbydis=$_POST['dis_date'];
		$flag=0;
		if(!empty($searchby)&&(($searchby[0]=='R')||($searchby[0]=='r')))
		{
			$result=mysqli_query($con,"select * from medical_report where Report_No='$searchby'");
			if(!empty($result) && mysqli_num_rows($result)==1)
			{
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				$row=mysqli_fetch_array($result);
				$num=$row['Report_No'];
				$id=$row['Patient_ID'];
				$result2=mysqli_query($con,"select * from patients where Patient_ID='$id'");
				$result4=mysqli_query($con,"select * from diagnosis where Report_No='$num'");
				$dept=mysqli_query($con,"select * from departments d,give_details g where g.Report_No='$num' and g.Department_No=d.Dept_No");
				$deptrow=mysqli_fetch_array($dept);
				$deptname=$deptrow['Name'];
				$row2=mysqli_fetch_array($result2);
				//$dept=$row['Dept_No'];
				$name=$row2['Name'];
				$dob=$row2['DOB'];
				$gender=$row2['Gender'];
				$bg=$row2['Blood_Group'];
				$date=$row['R_date'];
				$cdate=$row['C_date'];
				$room=$row['Room_No'];
				$result3=mysqli_query($con,"select * from room where Room_No='$room'");
				$row3=mysqli_fetch_array($result3);
				$result5=mysqli_query($con,"select * from Room_Given where Patient_ID='$id'");
				$row5=mysqli_fetch_array($result5);
				$allotdate=$row5['Allot_date'];
				$disdate=$row5['Discharge_date'];
				$roomtype=$row3['Type'];
				$diet=$row['Diet'];
				//$pay=$row['Payment'];
				$cost1=mysqli_query($con,"select b.Rent from medical_report a, room b where a.Patient_ID=b.Patient_ID and Report_No='$num'");
				$costrow1=mysqli_fetch_array($cost1);
				$days=mysqli_query($con,"select DATEDIFF('$disdate','$allotdate') as day;");
				$dayrow=mysqli_fetch_array($days);
				$days=$dayrow['day'];
				if($days==0)
				{
					$days=1;
				}
				$room_cost=$costrow1['Rent']*$days;
				
				$cost1=mysqli_query($con,"select b.Rent from medical_report a, vehicles b where a.Patient_ID=b.ID and Report_No='$num'");
				$costrow1=mysqli_fetch_array($cost1);
				$days1=mysqli_query($con,"select b.Allot_date,b.Return_date from medical_report a,vehicle_given b where a.Patient_ID=b.ID and Report_No='$num'");
				$days=mysqli_fetch_array($days1);
				$days=mysqli_query($con,"select datediff('$days[Return_date]','$days[Allot_date]') as day;");
				$dayrow=mysqli_fetch_array($days);
				$days=$dayrow['day'];
				if($days==0)
				{
					$days=1;
				}
				$veh_cost=$costrow1['Rent']*$days;
				
				
				echo "<h3>Medical Report</h3>";
				echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
				echo "<tr><td width=5%>Report No: </td><td width=50%>$num</td></tr>";
				echo "<tr><td>Date: </td><td>$date</td></tr>";
				echo "<tr><td>Patient ID: </td><td>$id</td></tr>";
				echo "<tr><td>Patient Name: </td><td>$name</td></tr>";
				echo "<tr><td>Date of Birth: </td><td>$dob</td></tr>";
				echo "<tr><td>Gender: </td><td>$gender</td></tr>";
				echo "<tr><td>Blood Group: </td><td>$bg</td></tr>";
				echo "<tr><td>Department: </td><td>$deptname</td></tr>";
				echo "<tr><td>Room No.: </td><td>$room</td></tr>";
				echo "<tr><td>Room Type: </td><td>$roomtype</td></tr>";
				echo "<tr><td>Allotment Date: </td><td>$allotdate</td></tr>";
				echo "<tr><td>Discharge Date: </td><td>$disdate</td></tr>";
				echo "<tr><td>Diagnosis: </td>";
				echo "<td><table border=\"2\">";
				echo "<tr><th>Test Date</th><th>Test Name</th><th>Result</th><th>Cost</th></tr>";
				$total=$room_cost+$veh_cost;
				for($i=0;$i<mysqli_num_rows($result4);$i=$i+1)
				{
					$row4=mysqli_fetch_array($result4);
					$date=$row4['Test_date'];
					$name=$row4['Tests'];
					$res=$row4['T_result'];
					$cost=$row4['Cost'];
					$total=$total+$cost;
					echo "<tr><td>$date</td><td>$name</td><td>$res</td><td>$cost</td></tr>";
				}
				echo "</table>";
				echo "<tr><td>Diet: </td><td>$diet</td></tr>";
				echo "<tr><td>Payment: </td><td>$total</td></tr>";
				echo "<tr><td>Report Closing Date: </td><td>$cdate</td></tr>";
				echo "</table></td></tr>";
				$flag=1;
			}
			else
				echo "<script type=\"text/javascript\">alert(\"Data not found.\")</script>";
			
		}
		else
			echo "<script type=\"text/javascript\">alert(\"Invalid Entry.\")</script>";
		
		
	}
		
}
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"del_report") == 0)
	{
        require("header.php");
		echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=del_report&option=search_val\" method=\"post\">";
		echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Enter Report-No or Patient name, Type to delete\"></td>";
		echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
		echo "</form></table>"; 		
		if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
		{
			$search=$_POST['search_val'];
			if(isset($search[0])&&($search[0]=='R' || $search[0]=='r'))
			{					
				$result=mysqli_query($con,"select * from medical_report where Report_No='$search'");						
				if(mysqli_num_rows($result)==1)
				{
					$query=mysqli_query($con,"delete from medical_report where Report_No='$search'");
					echo "<script type=\"text/javascript\">alert(\"Medical report deleted.\")</script>";
				}					
				else
				{
					echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
				}
			}					
			else if(isset($search[0]))
			{
				$result=mysqli_query($con,"select * from patients where Name='$search'");						
				if(mysqli_num_rows($result)==1)
				{
					$row=mysqli_fetch_array($result);
					$id=$row['Patient_ID'];
					$query=mysqli_query($con,"delete from medical_report where Patient_ID='$id'");
					if(mysqli_affected_rows($con)==0)
						echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
					else
						echo "<script type=\"text/javascript\">alert(\"Medical Report Deleted.\")</script>";
				}					
				else if(mysqli_num_rows($result)>=1)
				{
					echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
					echo "<div style=\"width:900px; float:left;\">";
					echo "<h2 align=\"left\">Click the Patient-ID to delete !!!</h2>";
					for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
					{
						$val=mysqli_fetch_array($result);
						$id=$val['Patient_ID'];
						echo "<div style=\"width:900px; float:left;\">";
						echo "<h3 align=\"left\"><a href=\"?pid=del_report&id=$id\">$id</a></h3>";
						echo "</div>";
					}
				}
						
				else
				{
					echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
				}
					
			}
			else
				echo "<script type=\"text/javascript\">alert(\"Invalid Entry.\")</script>";
		}
		if(isset($_GET['id']))
		{
			$search=$_GET['id'];
			$query=mysqli_query($con,"delete from medical_report where Patient_ID='$search'");
			if(mysqli_affected_rows($con)==0)
				echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
			else
				echo "<script type=\"text/javascript\">alert(\"Medical Report Deleted.\")</script>";
		}	
	}
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"view_test") == 0)
{
        require("header.php");
	echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=view_test&option=search_val\" method=\"post\">";
	echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Report Id.\"></td>";
	echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
	echo "</form></table>";				
	if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
	{
		$searchby=$_POST['search_val'];	
		
			if((isset($searchby[0]))&&($searchby[0]=='R' || $searchby[0]=='r'))
			{				
				$result=mysqli_query($con,"select * from diagnosis where Report_No='$searchby'");
				if(!empty($result) && mysqli_num_rows($result)==1)
				{
					
					echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">"; 
					$row=mysqli_fetch_array($result);
					$date=$row['Test_date'];
					$test=$row['Tests'];
					$tresult=$row['T_result'];
					$cost=$row['Cost'];
					$result1=mysqli_query($con,"select * from medical_report where Report_No='$searchby'");
					$row1=mysqli_fetch_array($result1);
					$id=$row1['Patient_ID'];
					$result2=mysqli_query($con,"select * from Patients where Patient_ID='$id'");
					$row2=mysqli_fetch_array($result2);
					$name=$row2['Name'];
					echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
					echo "<h3>Diagnosis Details</h3>";
					echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
					echo "<tr><td width=5%>Test Date: </td><td width=50%>$date</td></tr>";
					echo "<tr><td>Patient ID : </td><td>$id</td></tr>";
					echo "<tr><td>Patient Name : </td><td>$name</td></tr>";
					echo "<tr><td>Test : </td><td>$test</td></tr>";
					echo "<tr><td>Test Result: </td><td>$tresult</td></tr>";
					echo "<tr><td>Cost: </td><td>$cost</td></tr>";
					echo "</table>";
				}
				else if(!empty($result) && mysqli_num_rows($result) > 1)
				{
					echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
					echo "<div style=\"width:900px; float:left;\">";
					for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
					{
						$row=mysqli_fetch_array($result);
						$name=$row['Tests'];
						$date=$row['Test_date'];
						$rnum=$row['Report_No'];
						echo "<div style=\"width:900px; float:left;\">";
						echo "<h3 align=\"left\"><a href=\"?pid=tests_details&test=$name&date=$date&rnum=$rnum\">$name</a></h3>";
						echo "<p align=\"left\"><b>Test Date:</b> $date<br/><b>Report No.:</b> $rnum</p>";
						echo "</div>";
					}
					echo "</div>";
				}
					
				else
				{
					echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
				}
			}
		
		else
			echo "<script type=\"text/javascript\">alert(\"Invalid Entry.\")</script>";
	}
}
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"tests_details") == 0)
{
        require("header.php");
	$name=$_GET['test'];
	$date=$_GET['date'];
	$rnum=$_GET['rnum'];
	$result=mysqli_query($con,"select * from diagnosis where Report_No='$rnum' and Test_date='$date' and Tests='$name'");
	$row=mysqli_fetch_array($result);
	$tresult=$row['T_result'];
	$cost=$row['Cost'];
	$result1=mysqli_query($con,"select * from medical_report where Report_No='$rnum'");
	$row1=mysqli_fetch_array($result1);
	$id=$row1['Patient_ID'];
	$result2=mysqli_query($con,"select * from Patients where Patient_ID='$id'");
	$row2=mysqli_fetch_array($result2);
	$pname=$row2['Name'];
	echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
	echo "<h3>Diagnosis Details</h3>";
	echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;border-collapse: collapse;\">";
	echo "<tr><td width=5%>Test Date: </td><td width=50%>$date</td></tr>";
	echo "<tr><td>Patient ID : </td><td>$id</td></tr>";
	echo "<tr><td>Patient Name : </td><td>$pname</td></tr>";
	echo "<tr><td>Test : </td><td>$name</td></tr>";
	echo "<tr><td>Test Result: </td><td>$tresult</td></tr>";
	echo "<tr><td>Cost: </td><td>$cost</td></tr>";
	echo "</table>";	
}	
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"add_test") == 0)
{
	require("header.php");
	echo "<h1>Add Test Details</h1>";
	echo "<form name=\"form\" method=\"post\" action=\"login.php?pid=add_test\">";
	echo "<table>";
	echo "<tr>";
	echo "<td><p>Date :&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<input placeholder=\"yyyy-mm-dd\" type=\"text\" name=\"date\" size=\"15\" maxlength=\"15\" required/></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Test Name : <select name=\"test\"><option>CT Scan</option><option>Blood Test</option><option>Sugar Test</option><option>Dope Test</option><option>S6ZDMT</option></SELECT></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Result : &nbsp &nbsp &nbsp <input type=\"text\" name=\"result\" size=\"25\" maxlength=\50\" /></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Report No.: <input type=\"text\" name=\"R_num\" size=\"15\" maxlength=\"15\" required/></p>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Cost: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<input type=\"text\" name=\"cost\" size=\"15\" maxlength=\"15\" required/></p>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Enter\" /></p>";
	echo "</tr>";
	echo "</form>";
	if(isset($_POST['date'])&&isset($_POST['test'])&&isset($_POST['R_num'])&&isset($_POST['cost']))
	{
		$date=$_POST['date'];
		$name=$_POST['test'];
		$result=$_POST['result'];
		$r_num=$_POST['R_num'];
		$cost=$_POST['cost'];
		$check = mysqli_query($con,"SELECT * FROM medical_report where Report_No='$r_num'");
		$row=mysqli_fetch_array($check);
		$rdate=$row['R_date'];
		if(strcmp($rdate,$date)>0)
		{
			echo "<script type=\"text/javascript\">alert(\"Invalid Date.\")</script>";
		}
		else
		{
			if($row>0)
			{
				$query = "INSERT INTO diagnosis SET Test_date='$date', Tests='$name', T_result='$result', Report_No='$r_num', Cost='$cost'";
				$add=mysqli_query($con,$query);
				echo "<script type=\"text/javascript\">alert(\"Data Added.\")</script>";
				
			}
			else
				echo "<script type=\"text/javascript\">alert(\"Report ID not found in medical report.\")</script>";
		}
	}
}
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"add_result") == 0)
{
	require("header.php");
	echo "<h1>Add Test Result</h1>";
	echo "<form name=\"form\" method=\"post\" action=\"login.php?pid=add_result\">";
	echo "<table>";
	echo "<tr>";
	echo "<td><p>Date :&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<input placeholder=\"yyyy-mm-dd\" type=\"text\" name=\"date\" size=\"15\" maxlength=\"15\" required/></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Test Name : <select name=\"test\"><option>CT Scan</option><option>Blood Test</option><option>Sugar Test</option><option>Dope Test</option><option>S6ZDMT</option></SELECT></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Result : &nbsp &nbsp &nbsp <input type=\"text\" name=\"result\" size=\"25\" maxlength=\50\" required/></p></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p>Report No.: <input type=\"text\" name=\"R_num\" size=\"15\" maxlength=\"15\" required/></p>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><p align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Enter\" /></p>";
	echo "</tr>";
	echo "</form>";
	if(isset($_POST['date'])&&isset($_POST['test'])&&isset($_POST['R_num'])&&isset($_POST['result']))
	{
		$date=$_POST['date'];
		$name=$_POST['test'];
		$result=$_POST['result'];
		$r_num=$_POST['R_num'];
		$query = "UPDATE diagnosis SET T_result='$result' where Test_date='$date' and Tests='$name' and Report_No='$r_num'";
		$add=mysqli_query($con,$query);
		if(mysqli_affected_rows($con)==0)
			echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
		else
			echo "<script type=\"text/javascript\">alert(\"Data Updated.\")</script>";
	}	
}	
else if(isset($_GET['pid']) && strcmp($_GET['pid'],"del_test") == 0)
	{
        require("header.php");
		echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=del_test&option=search_val\" method=\"post\">";
		echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Enter Report-No, Type to delete\"></td>";
		echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
		echo "</form></table>"; 		
		if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
		{
			$search=$_POST['search_val'];			
				$result=mysqli_query($con,"select * from diagnosis where Report_No='$search'");						
				if(mysqli_num_rows($result)==1)
				{
					$query=mysqli_query($con,"delete from medical_report where Report_No='$search'");
					if(mysqli_affected_rows($con)==0)
						echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
					else
						echo "<script type=\"text/javascript\">alert(\"Medical Test Deleted.\")</script>";
				}					
				else if(mysqli_num_rows($result)>1)
				{
					echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
					echo "<div style=\"width:900px; float:left;\">";
					echo "<h2 align=\"left\">Click the test to delete !!!</h2>";
					for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
					{
						$val=mysqli_fetch_array($result);
						$id=$val['Report_No'];
						$name=$val['Tests'];
						$date=$val['Test_date'];
						$rnum=$val['Report_No'];
						echo "<div style=\"width:900px; float:left;\">";
						echo "<h3 align=\"left\"><a href=\"?pid=del_test&id=$name&date=$date&rnum=$rnum\">$name</a></h3>";
						echo "<p align=\"left\"><b>Test Date:</b> $date</p>";
						echo "</div>";
					}
				}
						
				else
				{
					echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
				}
					
		}
		if(isset($_GET['id'])&&isset($_GET['date'])&&isset($_GET['rnum']))
		{
			$search=$_GET['id'];
			$search1=$_GET['date'];
			$search2=$_GET['rnum'];
			$query=mysqli_query($con,"delete from diagnosis where Tests='$search' and Test_date='$search1' and Report_No='$search2'");
			if(mysqli_affected_rows($con)==0)
				echo "<script type=\"text/javascript\">alert(\"Data Not Found.\")</script>";
			else
				echo "<script type=\"text/javascript\">alert(\"Medical Test Deleted.\")</script>";
		}	
	}
else if(isset($_GET['pid'])&&((strcmp($_GET['pid'],"add_accompanies")==0)))
{
        //require("header.php");
	if(isset($_GET['option'])&&(strcmp($_GET['option'],"insert")==0))
	{
		$name=$_POST['name'];
		$address=$_POST['address'];
		$dob=$_POST['dob'];
		if(isset($_POST['contact']))
		$contact=$_POST['contact'];
		else $contact="NULL";
		$gender=$_POST['gender'];
		$rel=$_POST['rel'];
		$id=$_POST['id'];
                $select_query="SELECT Patient_ID FROM patients WHERE Patient_ID='$id' " ;
                $select_query_result= mysqli_query($con, $select_query);

                $mysqli_num_rows= mysqli_num_rows($select_query_result);

            if($mysqli_num_rows==0)
            {
                echo "<script type=\"text/javascript\">alert(\"Invalid Patient ID.\")</script>";            

            }
                else{
		mysqli_query($con,"insert into accompanies values (\"$id\", \"$name\", \"$address\", \"$dob\", \"$contact\", \"$gender\", \"$rel\")");
		header('Location: ?pid=view_accompanies');
                }
        
             }
	else
	{
                    require("header.php");
		echo "<div style=\"width:900px; float:left;\"><h1 align=\"left\">Registeration</h1>";
		echo "<table border=0 cellpadding=0 cellspacing=2 align=\"left\"><form action=\"login.php?pid=add_accompanies&option=insert\" method=\"post\">";
		echo "<tr><td><h4>Patient ID: </h4></td><td><input name=\"id\" type=\"text\" size=50 placeholder=\"Patient-ID\"></td></tr>";
		echo "<tr><td><h4>Name: </h4></td><td><input name=\"name\" type=\"text\" size=50 placeholder=\"Accompanies's Name\"></td></tr>";
		echo "<tr><td><h4>Address: </h4></td><td><input name=\"address\" type=\"text\" size=50 placeholder=\"Accompanies's Address\"></td></tr>";
		echo "<tr><td><h4>Date of Brith: </h4></td><td><input name=\"dob\" type=\"text\" size=50 placeholder=\"YYYY-MM-DD\"></td></tr>";
		echo "<tr><td><h4>Contact: </h4></td><td><input name=\"contact\" type=\"text\" size=50 placeholder=\"Phone Number\"></td></tr>";
		echo "<tr><td><h4>Gender: </h4></td><td><select name=\"gender\"><option>Male</option><option>Female</option></select></td></tr>";
		echo "<tr><td><h4>Relationship: </h4></td><td><input name=\"rel\" type=\"text\" size=50 placeholder=\"Relationship\"></td></tr>";
		echo "<tr><td><input type=\"submit\" value=\"Submit\" id=\"wdth2\"></td></tr>";
		echo "</form></table>";
		echo "</div>";
	}
}
//KNS starts
/*if(isset($_GET['pid']))
		{*/
			else if(isset($_GET['pid']) && strcmp($_GET['pid'],"search_vehicle") == 0)
			{
                                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=search_vehicle&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Type, Reg Id.\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>";
				
				if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
				{
					$search=$_POST['search_val'];
					
					if(!empty($search) && ($search[0]=='V' || $search[0]=='v'))
					{
						
						$result=mysqli_query($con,"select * from vehicles where Reg_No='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							$val=mysqli_fetch_array($result);
							$reg=$val['Reg_No'];
							$type=$val['Type'];
							$dop=$val['DOP'];
							$model=$val['MODEL'];
							$cost=$val['Rent'];
							
							echo " <h3> Vehicle Details </h3> ";
							echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
							echo "<tr><td width=5%>Reg No: </td><td width=50%>$reg</td></tr>";
							echo "<tr><td>Type: </td><td>$type</td></tr>";
							echo "<p align=\"left\"><tr><td>Date of Purchase: </td><td>$dop</td></tr>";
							echo "<tr><td>Model: </td><td>$model</td></tr>";
							echo "<tr><td>Rent: </td><td>$cost</td></tr>";
							echo "</table>";
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Value not found\") </script>";
						}
						
					}
					
					else if(!empty($search) && ($search[0]!='V' || $search[0]!='v'))
					{
						$result=mysqli_query($con,"select * from vehicles where Type='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							$val=mysqli_fetch_array($result);
							$reg=$val['Reg_No'];
							$type=$val['Type'];
							$dop=$val['DOP'];
							$model=$val['MODEL'];
							$cost=$val['Rent'];
							
							echo " <h3> Vehicle Details </h3> ";
							echo " <br/><br/>0";
							echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
							echo "<tr><td width=5%>Reg No: </td><td width=50%>$reg</td></tr>";
							echo "<tr><td>Type: </td><td>$type</td></tr>";
							echo "<p align=\"left\"><tr><td>Date of Purchase: </td><td>$dop</td></tr>";
							echo "<tr><td>Model: </td><td>$model</td></tr>";
							echo "<tr><td>Rent: </td><td>$cost</td></tr>";
							echo "</table>";
						}
						
						else if(mysqli_num_rows($result)>1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							echo "<div style=\"width:900px; float:left;\">";
							
							for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
							{
								$val=mysqli_fetch_array($result);
								$reg=$val['Reg_No'];
								$type=$val['Type'];
								echo "<div style=\"width:900px; float:left;\">";
								echo "<h3 align=\"left\"><a href=\"?pid=vehicle_details&id=$reg\">$reg</a></h3>";
								echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
								echo "</div>";
							}
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Value not found\") </script>";
						}
					}
					
				}
			}
				if(isset($_GET['pid']) && strcmp($_GET['pid'],"vehicle_details") == 0)
			{
                                        require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=search_vehicle&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Type, Reg Id.\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>";
				
				$search=$_GET['id'];
				$result=mysqli_query($con,"select * from vehicles where Reg_No='$search'");
				
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				$val=mysqli_fetch_array($result);
				$reg=$val['Reg_No'];
				$type=$val['Type'];
				$dop=$val['DOP'];
				$model=$val['MODEL'];
				$cost=$val['Rent'];
				
				echo " <h3> Vehicle Details </h3> ";
				echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
				echo "<tr><td width=5%>Reg No: </td><td width=50%>$reg</td></tr>";
				echo "<tr><td>Type: </td><td>$type</td></tr>";
				echo "<p align=\"left\"><tr><td>Date of Purchase: </td><td>$dop</td></tr>";
				echo "<tr><td>Model: </td><td>$model</td></tr>";
				echo "<tr><td>Rent: </td><td>$cost</td></tr>";
				echo "</table>";
			}
						
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"add_vehicle") == 0)
			{   
                                require("header.php");
				echo "<br/> <br/>";
				echo " <h1> Add Vehicle </h1>";
				echo "<br/> <br/> <br/>";
				echo "<table border=0 cellpadding=1 cellspacing=5 style=\"margin:-150px 700px 0px -3px;\">";
				echo " <form action=\"#\" method=POST>";
				//echo " <tr><td>Registration Id: </td><td><input type=\"text\" name=\"Reg_No\" required></tr></td><br/><br/>";
				echo " <tr><td>Type: </td><td><select name=\"drop\"><option value=\"Ambulance\" required>Ambulance</option><option value=\"Taxi\">Taxi</option> </select></td></tr><br/><br/>";
				//echo " <tr><td>Date of purchase:</td><td> <input type=\"text\" name=\"DOP\" required placeholder=\"YYYY-MM-DD\"></td><td></td></tr><br/><br/>";
				echo " <tr><td>Model: </td><td><input type=\"text\" name=\"Model\" required></td></tr><br/><br/><br/>";
				echo " <tr><td><input type=\"submit\" name=\"submit\"></submit></td></tr>";
				echo " </table></form> ";
				
				
				
				if(isset($_POST['drop']))
				{
					$type=$_POST['drop'];
					
					if($type=="Ambulance")
					{
						$cost=200.00;
						
						if(isset($_POST['Model']) )
						{	
					
							$sel=mysqli_query($con,"select curdate() date");
							$DOP=mysqli_fetch_array($sel);
							$DOP=$DOP['date'];
							
							$model=$_POST['Model'];
							
							$result=mysqli_query($con,"select max(Reg_No) R from vehicles");
							$val=mysqli_fetch_array($result);
							//echo $val;
							$test=$val['R'];
							$reg="V".(substr($test,1)+1);
							
							
							
							$query="INSERT INTO vehicles SET Type=\"Ambulance\",Reg_No='$reg',DOP='$DOP',Model='$model',ID=NULL,Rent=200";
							$result=mysqli_query($con,$query);
							
							echo "<script type=\"text/javascript\">alert(\"Vehicle No. $reg has been added.\")</script>";
							
							
						}
					}
					
					if($type=="Taxi")
					{
						if(isset($_POST['Model']) )
						{	
					
							//$DOP=$_POST['DOP'];
							$model=$_POST['Model'];
							
							$sel=mysqli_query($con,"select curdate() date");
							$DOP=mysqli_fetch_array($sel);
							$DOP=$DOP['date'];
							
							
							$result=mysqli_query($con,"select max(Reg_No) R from vehicles");
							$val=mysqli_fetch_array($result);
							//echo $val;
							$test=$val['R'];
							$reg="V".(substr($test,1)+1);
							
							
							
							$query="INSERT INTO vehicles SET Type=\"Taxi\",Reg_No='$reg',DOP='$DOP',Model='$model',ID=NULL,Rent=500";
							$result=mysqli_query($con,$query);
								
							echo "<script type=\"text/javascript\">alert(\"Vehicle Added\") </script>";
						
							
						}	
					}
				}
			}	
			
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"discard_vehicle") == 0)
			{
                                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=discard_vehicle&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Enter Reg-No, Type to delete\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>"; 
				
				if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
				{
					$search=$_POST['search_val'];
					
					if(!empty($search) &&($search[0]=='V' || $search[0]=='v'))
					{
						
						$result=mysqli_query($con,"select * from vehicles where Reg_No='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							$query=mysqli_query($con,"delete from vehicles where Reg_No='$search'");
							echo "<br/><br/><br/>";
							echo "<script type=\"text/javascript\">alert(\"Vehicle discarded\") </script>";
 
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Vehicle not found\") </script>";
						}
					}
					
					else if(!empty($search) &&($search[0]!='V' || $search[0]!='v'))
					{
						$result=mysqli_query($con,"select * from vehicles where Type='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							$query=mysqli_query($con,"delete from vehicles where Type='$search'");
							echo "<br/><br/><br/>";
							echo "<script type=\"text/javascript\">alert(\"Vehicle discarded\") </script>";
						}
						
						else if(mysqli_num_rows($result)>=1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							echo "<div style=\"width:900px; float:left;\">";
							
							for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
							{
								$val=mysqli_fetch_array($result);
								$reg=$val['Reg_No'];
								$type=$val['Type'];
								echo "<div style=\"width:900px; float:left;\">";
								echo "<h3 align=\"left\"><a href=\"?pid=discard_vehicle&id=$reg\">$reg</a></h3>";
								echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
								echo "</div>";
							}
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Vehicle not found\") </script>";
						}
						
					}
					
					else
						echo "<script type=\"text/javascript\">alert(\"Please enter value\") </script>";
				}
			}
			
			
						
			else if(isset($_GET['pid']) && strcmp($_GET['pid'],"search_room") == 0)
			{
                                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=search_room&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Type, Room Id.\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>";
				
				if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
				{
					$search=$_POST['search_val'];
					
					if(!empty($search) && ($search[0]=='Z' || $search[0]=='z'))
					{
						
						$result=mysqli_query($con,"select * from room where Room_No='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							$val=mysqli_fetch_array($result);
							$reg=$val['Room_No'];
							$type=$val['Type'];
							$ext=$val['Extension'];
							$cost=$val['Rent'];
							
							echo " <h3> Room Details </h3> ";
							echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
							echo "<tr><td width=5%>Room No: </td><td width=50%>$reg</td></tr>";
							echo "<tr><td>Type: </td><td>$type</td></tr>";
							echo "<p align=\"left\"><tr><td>Extension: </td><td>$ext</td></tr>";
							echo "<tr><td>Rent: </td><td>$cost</td></tr>";
							echo "</table>";
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Room not found\") </script>";
						}
					}
					
					else if(!empty($search) && ($search[0]!='Z' || $search[0]!='z'))
					{
						$result=mysqli_query($con,"select * from Room where Type='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							$val=mysqli_fetch_array($result);
							$reg=$val['Room_No'];
							$type=$val['Type'];
							$ext=$val['Extension'];
							$cost=$val['Rent'];
							
							echo " <h3> Room Details </h3> ";
							echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
							echo "<tr><td width=5%>Room No: </td><td width=50%>$reg</td></tr>";
							echo "<tr><td>Type: </td><td>$type</td></tr>";
							echo "<p align=\"left\"><tr><td>Extension: </td><td>$ext</td></tr>";
							echo "<tr><td>Rent: </td><td>$cost</td></tr>";
							echo "</table>";
						}
						
						else if(mysqli_num_rows($result)>=1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							echo "<div style=\"width:900px; float:left;\">";
							
							for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
							{
								$val=mysqli_fetch_array($result);
								$reg=$val['Room_No'];
								$type=$val['Type'];
								echo "<div style=\"width:900px; float:left;\">";
								echo "<h3 align=\"left\"><a href=\"?pid=room_details&id=$reg\">$reg</a></h3>";
								echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
								echo "</div>";
							}
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Room not found\") </script>";
						}
					}
					
					else
						echo "<script type=\"text/javascript\">alert(\"Please enter value\") </script>";
				}
			}
			
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"add_room") == 0)
			{
                                require("header.php");
				echo "<br/> <br/>";
				echo " <h1> Add Room </h1>";
				echo "<br/> <br/> <br/><br/><br/><br/><br/><br/><br/> ";
				echo "<table border=0 cellpadding=1 cellspacing=5 style=\"margin:-150px 700px 0px -3px;\">";
				echo " <form action=\"#\" method=POST>";
				//echo "<tr><td> Room Id:</td><td> <input type=\"text\" name=\"Room_No\" required><br/><br/> </td></tr>";
				echo " <tr><td>Type: </td><td><select name=\"drop\"><option value=\"Gen\">General</option><option value=\"pri\">Private</option><option value=\"icu\">ICU</option> </select></td></tr><br/><br/>";
				echo "<tr><td> Extension: </td><td><input type=\"text\" name=\"ext\" required><br/><br/> </td></tr>";
				echo "<tr><td> <input type=\"submit\" name=\"submit\"></submit> </td></tr>";
				echo "</table> </form> ";
				
				
				
				$flag=1;
				
				if(isset($_POST['drop']) && isset($_POST['ext']) ) //
				{
					$ext=$_POST['ext'];
					$check=mysqli_query($con,"select * from room where Extension='$ext'");
					if(mysqli_num_rows($check)>=1)
					{
						//echo "check";
						$flag=0;
					}		
					$type=$_POST['drop'];
					
					if($type=="Gen")
					{
						$cost=2500.00;
						
						if(isset($_POST['ext']) && $flag==1)
						{			
				
							$ext=$_POST['ext'];
					
							
							$result=mysqli_query($con,"select * from room");
							$val=mysqli_num_rows($result);
							$result=mysqli_query($con,"select max(Room_No) R from room");
							$val=mysqli_fetch_array($result);
							//echo $val;
							$test=$val['R'];
							$room="Z".(substr($test,1)+1);
							
							$query="INSERT INTO room SET Room_No='$room',Type=\"Gen\",Extension='$ext',Rent=2500,Patient_ID=null";
							$result=mysqli_query($con,$query);
							
							echo "<script type=\"text/javascript\">alert(\"Room No. $room has been added.\")</script>";
							
							
								
						}
					}
					
					if($type=="pri" && $flag==1)
					{
						$cost=5000.00;
						
						if(isset($_POST['ext']))
						{			
				
							$ext=$_POST['ext'];
					
							/*echo $reg;
							echo $type;
							echo $DOP;
							echo $model;*/
							$result=mysqli_query($con,"select * from room");
							$val=mysqli_num_rows($result);
							$result=mysqli_query($con,"select max(Room_No) R from room");
							$val=mysqli_fetch_array($result);
							//echo $val;
							$test=$val['R'];
							$room="Z".(substr($test,1)+1);
							
							$query="INSERT INTO room SET Room_No='$room',Type=\"PRI\",Extension='$ext',Rent=2500,Patient_ID=null";
							$result=mysqli_query($con,$query);
							
							echo "<script type=\"text/javascript\">alert(\"Room Added\") </script>";
							
							
						}
					}
					
					if($type=="icu" && $flag==1)
					{
						$cost=2500.00;
						
						if(isset($_POST['ext']))
						{			
				
							$ext=$_POST['ext'];
					
							
							$result=mysqli_query($con,"select * from room");
							$val=mysqli_num_rows($result);
							$result=mysqli_query($con,"select max(Room_No) R from room");
							$val=mysqli_fetch_array($result);
							//echo $val;
							$test=$val['R'];
							$room="Z".(substr($test,1)+1);
							
							$query="INSERT INTO room SET Room_No='$room',Type=\"ICU\",Extension='$ext',Rent=2500,Patient_ID=null";
							$result=mysqli_query($con,$query);
							
							echo "<script type=\"text/javascript\">alert(\"Room Added\") </script>";
							
							
						}
					}
					
					if($flag==0)
					{
						echo "<script type=\"text/javascript\">alert(\"Extension already in use!\") </script>";
					}
				}	
				
				
				
			}	
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"room_details") == 0)
			{
                                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=search_room&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Type, Reg Id.\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>";
				
				$search=$_GET['id'];
				$result=mysqli_query($con,"select * from room where Room_No='$search'");
				
				echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
				$val=mysqli_fetch_array($result);
				$reg=$val['Room_No'];
				$type=$val['Type'];
				$ext=$val['Extension'];
				
				echo " <h3> Room Details </h3> ";
				echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
				echo "<tr><td width=5%>Room No: </td><td width=50%>$reg</td></tr>";
				echo "<tr><td>Type: </td><td>$type</td></tr>";
				echo "<p align=\"left\"><tr><td>Extension: </td><td>$ext</td></tr>";
				echo "</table>";
			}
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"discard_room") == 0)
			{
                                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=discard_room&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Enter Room-No, Type to delete\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>"; 
				
				if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
				{
					$search=$_POST['search_val'];
					
					if(!empty($search) && ($search[0]=='Z' || $search[0]=='z'))
					{
						
						$result=mysqli_query($con,"select * from room where Room_No='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							$query=mysqli_query($con,"delete from room where Room_No='$search'");
							//echo "hello";
							echo "<br/><br/><br/>";
							echo "<script type=\"text/javascript\">alert(\"Room Discarded\") </script>";
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Invalid Entry\") </script>";
						}
					}
					
					else if(!empty($search) && ($search[0]!='Z' || $search[0]!='z'))
					{
						$result=mysqli_query($con,"select * from room where Type='$search'");
						
						if(mysqli_num_rows($result)==1)
						{
							$query=mysqli_query($con,"delete from room where Type='$search'");
							echo "<br/><br/><br/>";
							echo "<script type=\"text/javascript\">alert(\"Room discarded\") </script>";
						}
						
						else if(mysqli_num_rows($result)>=1)
						{
							echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
							echo "<div style=\"width:900px; float:left;\">";
							
							for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
							{
								$val=mysqli_fetch_array($result);
								$reg=$val['Room_No'];
								$type=$val['Type'];
								echo "<div style=\"width:900px; float:left;\">";
								echo "<h3 align=\"left\"><a href=\"?pid=discard_room&id=$reg\">$reg</a></h3>";
								echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
								echo "</div>";
							}
						}
						
						else
						{
							echo "<br/> <br/>";
							echo "<script type=\"text/javascript\">alert(\"Invalid Entry\") </script>";
						}
					}
					
					else
						echo "<script type=\"text/javascript\">alert(\"Please enter value\") </script>";
				}
			}
			
				
	if(isset($_GET['pid']) && strcmp($_GET['pid'],"alloted_vehicle") == 0)
{
                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=alloted_vehicle&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Enter Reg-No, Type.\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>";
				
				if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
				{
					$search=$_POST['search_val'];
					
					if((!empty($search)) && ($search[0]=='V' || $search[0]=='v'))
					{
						$find=mysqli_query($con,"select * from vehicles where Reg_No='$search'");
						
						if(mysqli_num_rows($find)==0)
							echo "<script type=\"text/javascript\">alert(\"Vehicle not found!\") </script>";
							
						else
						{
							$result=mysqli_query($con,"select * from vehicle_given where Reg_No='$search'");
							
							if(mysqli_num_rows($result)==0)
							{
								echo "<script type=\"text/javascript\">alert(\"Vehicle not alloted!\") </script>";
							}
							
							else
							{
								if((!empty($result) && mysqli_num_rows($result)==1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									$val=mysqli_fetch_array($result);
									$reg=$val['Reg_No'];
									$id=$val['ID'];
									$ad=$val['Allot_date'];
									$rd=$val['Return_date'];
									$rent=mysqli_query($con,"select Rent from vehicles where Reg_No='$search'");	
									$cost=mysqli_fetch_array($rent);
									$price=$cost['Rent'];
									
									echo " <h3> Vehicle Allotment History</h3> ";
									echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
									echo "<tr><td width=5%>Reg No: </td><td width=50%>$reg</td></tr>";
									echo "<tr><td>Used by: </td><td>$id</td></tr>";
									echo "<p align=\"left\"><tr><td>Allotment Room: </td><td>$ad</td></tr>";
									echo "<tr><td>Return Date: </td><td>$rd</td></tr>";
									echo "<tr><td>Rent: </td><td>$price</td></tr>";
									echo "</table>";
								}
								
								else if(!empty($result) && (mysqli_num_rows($result)>1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									echo "<div style=\"width:900px; float:left;\">";
									
									echo " <h3> Select any vehicle</h3> ";
									
									for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
									{
										$val=mysqli_fetch_array($result);
										$reg=$val['Reg_No'];
										$id=$val['ID'];
										$ad=$val['Allot_date'];
										$rd=$val['Return_date'];
										echo "<div style=\"width:900px; float:left;\">";
										echo "<h3 align=\"left\"><a href=\"?pid=vehicle_al&reg=$reg&id=$id&ad=$ad&rd=$rd\">$reg</a></h3>";
										//echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
										echo "</div>";
									}
								}
							}	
						}
					}
					
					
					else if((!empty($search)) && ($search[0]!='V' || $search[0]!='v'))
					{
						$find=mysqli_query($con,"select * from vehicles where Type='$search'");
						
						if(mysqli_num_rows($find)==0)
							echo "<script type=\"text/javascript\">alert(\"Vehicle not found!\") </script>";
							
						else
						{
							$result=mysqli_query($con,"select b.Reg_No,b.ID,b.Allot_date,b.Return_date from vehicles a,vehicle_given b where a.Type='$search' and a.Reg_No=b.Reg_No");
							
							if(mysqli_num_rows($result)==0)
							{
								echo "<script type=\"text/javascript\">alert(\"Vehicle not alloted!\") </script>";
							}
							
							else
							{
								if((!empty($result) && mysqli_num_rows($result)==1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									$val=mysqli_fetch_array($result);
									$reg=$val['Reg_No'];
									$id=$val['ID'];
									$ad=$val['Allot_date'];
									$rd=$val['Return_date'];
									$rent=mysqli_query($con,"select Rent from vehicles where Type='$search'");		
									$cost=mysqli_fetch_array($rent);
									$price=$cost['Rent'];
									
									echo " <h3> Vehicle Allotment History</h3> ";
									echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
									echo "<tr><td width=5%>Reg No: </td><td width=50%>$reg</td></tr>";
									echo "<tr><td>Used by: </td><td>$id</td></tr>";
									echo "<p align=\"left\"><tr><td>Allotment Date: </td><td>$ad</td></tr>";
									echo "<tr><td>Return Date: </td><td>$rd</td></tr>";
									echo "<tr><td>Rent: </td><td>$price</td></tr>";
									echo "</table>";
								}
								
								
								else if(!empty($result) && (mysqli_num_rows($result)>1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									echo "<div style=\"width:900px; float:left;\">";
									
									echo " <h3> Select any vehicle</h3> ";
									
									for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
									{
										$val=mysqli_fetch_array($result);
										$reg=$val['Reg_No'];
										$id=$val['ID'];
										$ad=$val['Allot_date'];								
										$rd=$val['Return_date'];
										
										echo "<div style=\"width:900px; float:left;\">";
										echo "<h3 align=\"left\"><a href=\"?pid=vehicle_al&reg=$reg&id=$id&ad=$ad&rd=$rd\">$reg</a></h3>";
										//echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
										echo "</div>";
									}
								}
							}	
						}
					}
					
					else
						echo "<script type=\"text/javascript\">alert(\"Enter Value!\") </script>";
				}	
			}
			
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"vehicle_al") == 0)
			{
                                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=alloted_vehicle&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Type, Reg Id.\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>";
				
				if(isset($_GET['id']) && isset($_GET['reg']) && isset($_GET['ad']) || isset($_GET['rd']) )
				{
					$search=$_GET['reg'];
					//$result=mysqli_query("select * from vehicle_given where Reg_No='$search'");
					echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
					//$val=mysqli_fetch_array($result);
					
				//	$reg=$val['Reg_No'];
					$id=$_GET['id'];
					$reg=$_GET['reg'];
					$ad=$_GET['ad']; 
					$rd=$_GET['rd'];
					
					$rent=mysqli_query($con,"select Rent from vehicles where Reg_No='$search'");	
					$cost=mysqli_fetch_array($rent);
					$price=$cost['Rent'];
					
					echo " <h3> Vehicle Allotment History</h3> ";
					echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
					echo "<tr><td width=5%>Reg No: </td><td width=50%>$reg</td></tr>";
					echo "<tr><td>Used by: </td><td>$id</td></tr>";
					echo "<p align=\"left\"><tr><td>Allotment Date: </td><td>$ad</td></tr>";
					echo "<tr><td>Return Date: </td><td>$rd</td></tr>";
					echo "<tr><td>Rent: </td><td>$price</td></tr>";
					echo "</table>";					
				}
			}
			
/*Room allotment */
			
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"alloted_room") == 0)
			{
                                require("header.php");
				echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=alloted_room&option=search_val\" method=\"post\">";
				echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Type, Room Id.\"></td>";
				echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
				echo "</form></table>";
				
				if(isset($_GET['option']) && strcmp($_GET['option'],"search_val")==0)
				{
					$search=$_POST['search_val'];
					
					if((!empty($search)) && ($search[0]=='Z' || $search[0]=='z'))
					{
						$find=mysqli_query($con,"select * from room where Room_No='$search'");
						
						if(mysqli_num_rows($find)==0)
							echo "<script type=\"text/javascript\">alert(\"Room not found!\") </script>";
							
						else
						{
							$result=mysqli_query($con,"select * from room_given where Room_No='$search'");
							
							if(mysqli_num_rows($result)==0)
							{
								echo "<script type=\"text/javascript\">alert(\"Room not alloted!\") </script>";
							}
							
							else
							{
								if((!empty($result) && mysqli_num_rows($result)==1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									$val=mysqli_fetch_array($result);
									$room=$val['Room_No'];
									$id=$val['Patient_ID'];
									$ad=$val['Allot_date'];
									$rd=$val['Discharge_date'];
									$rent=mysqli_query($con,"select Rent from room where Room_No='$search'");
									$price=mysqli_fetch_array($rent);
									$cost=$price['Rent'];
									
									echo " <h3> Room Allotment History</h3> ";
									echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
									echo "<tr><td width=5%>Room No: </td><td width=50%>$room</td></tr>";
									echo "<tr><td>Patient Id: </td><td>$id</td></tr>";
									echo "<p align=\"left\"><tr><td>Allotment Date: </td><td>$ad</td></tr>";
									echo "<tr><td>Discharge Date: </td><td>$rd</td></tr>";
									echo "<tr><td>Rent: </td><td>$cost</td></tr>";
									echo "</table>";
								}
								
								else if(!empty($result) && (mysqli_num_rows($result)>1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									echo "<div style=\"width:900px; float:left;\">";
									
									for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
									{
										$val=mysqli_fetch_array($result);
										$room=$val['Room_No'];
										$id=$val['Patient_ID'];
										$ad=$val['Allot_date'];
										$rd=$val['Discharge_date'];
			
										echo "<div style=\"width:900px; float:left;\">";
										echo "<h3 align=\"left\"><a href=\"?pid=room_al&id=$id&room=$room&ad=$ad&rd=$rd\">$room</a></h3>";										echo "<div style=\"width:900px; float:left;\">";
										//echo "<h3 align=\"left\"><a href=\"?pid=room_al&id=$reg\">$reg</a></h3>";
										//echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
										echo "</div>";
									}
								}
							}	
						}
					}
					
					else if((!empty($search)) && ($search[0]!='Z' || $search[0]!='z'))
					{
						$find=mysqli_query($con,"select * from room where Type='$search'");
						
						if(mysqli_num_rows($find)==0)
							echo "<script type=\"text/javascript\">alert(\"Room not found!\") </script>";
							
						else
						{
							$result=mysqli_query($con,"select * from room a,room_given b where a.Type='$search' and a.Room_No=b.Room_No");
							//echo mysqli_num_rows($result);
						
							if(mysqli_num_rows($result)==0)
							{
								echo "<script type=\"text/javascript\">alert(\"Room not alloted!\") </script>";
							}
		
							else
							{
								if((!empty($result) && mysqli_num_rows($result)==1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									$val=mysqli_fetch_array($result);
									$room=$val['Room_No'];
									$id=$val['Patient_ID'];
									$ad=$val['Allot_date'];
									$rd=$val['Discharge_date'];
									$rent=mysqli_query($con,"select Rent from room where Room_No='$search'");
									$price=mysqli_fetch_array($rent);
									$cost=$price['Rent'];
									
									
									echo " <h3> Room Allotment History</h3> ";
									echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
									echo "<tr><td width=5%>Room No: </td><td width=50%>$room</td></tr>";
									echo "<tr><td>Patient Id: </td><td>$id</td></tr>";
									echo "<p align=\"left\"><tr><td>Allotment Date: </td><td>$ad</td></tr>";
									echo "<tr><td>Discharge Date: </td><td>$rd</td></tr>";
									echo "<tr><td>Rent: </td><td>$cost</td></tr>";
									echo "</table>";
								}
								
								else if(!empty($result) && (mysqli_num_rows($result)>1))
								{
									echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
									echo "<div style=\"width:900px; float:left;\">";
									
									echo " <h3> Select any room</h3> ";
									
									for($i=0;$i<mysqli_num_rows($result);$i=$i+1)
									{
										$val=mysqli_fetch_array($result);
										$room=$val['Room_No'];
										$id=$val['Patient_ID'];
										$ad=$val['Allot_date'];
										$rd=$val['Discharge_date'];
			
										echo "<div style=\"width:900px; float:left;\">";
										echo "<h3 align=\"left\"><a href=\"?pid=room_al&id=$id&room=$room&ad=$ad&rd=$rd\">$room</a></h3>";
										//echo "<p align=\"left\"><b>Type:</b> $type<br/> ";
										echo "</div>";
									}
								}
							}	
						}
					}
					
					
					else
						echo "<script type=\"text/javascript\">alert(\"Enter Value!\") </script>";
				}
		
				/* Room allotment finished */
			}	
			if(isset($_GET['pid']) && strcmp($_GET['pid'],"room_al") == 0)
				{
                                require("header.php");
					echo "<br/><table border=0 cellpadding=1 cellspacing=1 height=5 style=\"\"><form action=\"login.php?pid=alloted_room&option=search_val\" method=\"post\">";
					echo "<tr><td style=\"border-bottom: #FFFFFF\"><input name=\"search_val\" type=\"text\" size=50 placeholder=\"Search by Type, Room Id.\"></td>";
					echo "<td style=\"border-bottom: #FFFFFF\"><input type=\"image\" name=\"commit\" value=\"submit\" src=\"images/search_button.gif\" style=\"height:30px\"></td></tr>";
					echo "</form></table>";
					
					if(isset($_GET['id']) && isset($_GET['room']) && isset($_GET['ad']) || isset($_GET['rd']) )
					{
						$search=$_GET['room'];
						//$result=mysqli_query("select * from vehicle_given where Reg_No='$search'");
						echo "<div><link rel=\"stylesheet\" href=\"css/style01.css\">";
						//$val=mysqli_fetch_array($result);
						
					//	$reg=$val['Reg_No'];
						$id=$_GET['id'];
						$room=$_GET['room'];
						$ad=$_GET['ad']; 
						$rd=$_GET['rd'];
						
						$rent=mysqli_query($con,"select Rent from room where Room_No='$search'");	
						$cost=mysqli_fetch_array($rent);
						$price=$cost['Rent'];
						
						echo " <h3> Room Allotment History</h3> ";
						echo "<table border=0 cellpadding=1 cellspacing=0 style=\"margin:5px 0px 0px -3px;\">";
						echo "<tr><td width=5%>Reg No: </td><td width=50%>$room</td></tr>";
						echo "<tr><td>Used by: </td><td>$id</td></tr>";
						echo "<p align=\"left\"><tr><td>Allotment Date: </td><td>$ad</td></tr>";
						echo "<tr><td>Return Date: </td><td>$rd</td></tr>";
						echo "<tr><td>Rent: </td><td>$price</td></tr>";
						echo "</table>";					
					}
				}
		
?>
</div>
</center>
</body>
</html>