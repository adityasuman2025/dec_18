<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    #############################################################
    #################### Recruitment Process ####################
    #############################################################
    function recruitmentReference()
    {
        return array(1=>'Recruiter',2=>'Employee referral',3=>'Consultancy',4=>'Walkin',5=>'Job fair',6=>'SMS',7=>'Portal',8=>'Other');
    }
    function emp_experience()
    {
        return array(1=>'Fresher',2=>'Experienced',3=>'Not Updated');
    }
    function emp_qulification()
    {
        return array(1=>'BA',2=>'BBM',3=>'BBS',4=>'BE/B.Tech',5=>'BCA',6=>'BCOM',7=>'BSC',8=>'Diploma',9=>'MA',10=>'MBA',11=>'MCom',12=>'ME/M.Tech',13=>'PGDC',14=>'Plus-Two / PUC',15=>'Tenth class',16=>'Other');
    }
    #####################################################################
    #####################################################################
    function interview_status()
    {
        //donot change the order 5,6,10= selected using in script
        return array(1=>'Pending ',2=>'Not Attended / Cancelled',3=>'On Hold',4=>'Shortlisted',5=>'Follow Up',6=>'Schedule',7=>'Send to Interviewer',8=>'Selected to Next level',9=>'Not suitable for this position',11=>'Did not complete the recruitment process',12=>'Keep for Future Reference',10=>'Selected',13=>'Reject',14=>'Joined',15=>'No Responce',16=>'Not Intrested');
    }
    function interviewer_interview_status()
    {
        //donot change the order 5,6,10= selected using in script
        // change according interview_status
        return array(8=>'Selected to Next level',9=>'Not suitable for this position',11=>'Did not complete the recruitment process',12=>'Keep for Future Reference');
    }
    #####################################################################
    #####################################################################
    function rounds_of_interviews()
    {
        return array(1=>'First Round Interview',2=>'Second Round Interview',3=>'Third Round Interview',4=>'Final Round Interview',5=>'Technical Interview',6=>'Technical Test',7=>'Machine Test',8=>'Written Test',9=>'Group Discussion',10=>'Panal Discussion',11=>'Aptitude Test',12=>'English Test',13=>'Telephone Interview',14=>'Skype Interview',15=>'HR Interview',16=>'Physical Test');
    }
    function recruitment_status()
    {
        return array(1=>'Joined',2=>'Offer Declined',3=>'Postpone DOJ',4=>'Not joined');
    } 
    function employee_status()
    {
        return array(1=>'Active',2=>'Inactive',3=>'Resigned',4=>'Pending Approval',5=>'Terminated',6=>'Absconded');
    } 
    function career_job_application_status()
    {
        return array(1=>'In Hold',2=>'Shortlisted',3=>'Rescheduled',4=>'Recruited',5=>'Rejected',6=>'Not Answering');
    }
    #############################################################
    #################### /Recruitment Process ###################
    #############################################################
    function hiring_status()
    {
        return array('1'=>'Pending Requests','2'=>'Honoured Requests','3'=>'Cancelled Requests','4'=>'Rejected Requests');
    } 
    function career_status()
    {
        return array('1'=>'Active','2'=>'Inactive','3'=>'Pending','4'=>'Cancelled','5'=>'Rejected');
    } 
	function user_document_category()
    {
        return array(1=>'Personal Details ',2=>'Education Certificates',3=>'Professional Details');
    }
	function user_document_category_type()
    {
        return array(1=>'Joining Form',2=>'Resume',3=>'Signed Offer Letter',4=>'ID Proof - PAN',5=>'ID Proof - Aadhar',6=>'ID Proof - Passport',7=>'ID Proof - Voter ID',8=>'ID Proof - Drivers License',9=>'Address Proof - Rental Agreement',10=>'Address Proof - Electricity Bill',11=>'Address Proof - Ration Card ',12=>'Employee Photo',13=>'10th - Markscards & Certificates',14=>'12th - Markscards & Certificates',15=>'Degree - Markscards & Certificates',16=>'Diploma - Markscards & Certificates',17=>'PG Degree - Markscards & Certificates',18=>'Last Job - Offer Letter',19=>'Last Job - 3 Months Pay Slip',20=>'Last Job - Relieving Letter',21=>'Previous Job - Offer Letter',22=>'Previous Job - Relieving Letter',23=>'Appraisal Letter',24=>'Certification',25=>'Monthly Rewards');
    }
	function edu_document_category_type()
    {
        return array(13=>'10th - Markscards & Certificates',14=>'12th - Markscards & Certificates',15=>'Degree - Markscards & Certificates',16=>'Diploma - Markscards & Certificates',17=>'PG Degree - Markscards & Certificates');
    }
	function profession_document_category_type()
    {
        return array(18=>'Last Job - Offer Letter',19=>'Last Job - 3 Months Pay Slip',20=>'Last Job - Relieving Letter',21=>'Previous Job - Offer Letter',22=>'Previous Job - Relieving Letter',23=>'Appraisal Letter',24=>'Certification',25=>'Monthly Rewards');
    }
	function personal_document_category_type()
    {
        return array(1=>'Joining Form',2=>'Resume',3=>'Signed Offer Letter',4=>'ID Proof - PAN',5=>'ID Proof - Aadhar',6=>'ID Proof - Passport',7=>'ID Proof - Voter ID',8=>'ID Proof - Drivers License',9=>'Address Proof - Rental Agreement',10=>'Address Proof - Electricity Bill',11=>'Address Proof - Ration Card ',12=>'Employee Photo');
    }
	function personal_document_id_proof()
    {
        return array(4=>'ID Proof - PAN',5=>'ID Proof - Aadhar',6=>'ID Proof - Passport',7=>'ID Proof - Voter ID',8=>'ID Proof - Drivers License');
    }
	function personal_document_address_proof()
    {
        return array(9=>'Address Proof - Rental Agreement',10=>'Address Proof - Electricity Bill',11=>'Address Proof - Ration Card ');
    }
	function leave_check_office_array()
    {
        return array(4,5,6);
    }
	function employee_gender()
	{
		return array('1'=>'Male','2'=>'Female','3'=>'other');
	}
	function employee_hrm_status()
	{
		return array(1=>'Active',2=>'Resigned',3=>'Terminated',4=>'Absconded',5=>'Transferred',6=>'Not Certified');
	}
    function tds_status_list()
	{
		return array(1=>'Calucated ',2=>'Recalculate',3=>'Not Calucated');
	}
	function employee_marital_status()
	{
		return array(1=>'Single',2=>'Married',3=>'Divorced',4=>'Widowed',5=>'other');
	}
    function companyType()
    {
        return array('Company','Head Office','Regional Office','Partner Office');
    }
    function separation_status()
    {
        return array('2'=>'In Progress','4'=>'Approved','5'=>'Rejected','6'=>'Employee Revoked');
    }
    function notificationstatus()
    {
        return array(1=>'Announcements',2=>'Notice',3=>'Information',4=>'Awards',5=>'Anniversary',6=>'Birthday',7=> 'Others');
    }
	function user_office()
	{
		return array('1'=>'Employee','2'=>'Partner');
	}
?>