<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    function address($branch="")
    {
        $branch                 =   ($branch)?$branch:1;
        if($branch              ==  1)
        {
            return "<p>Address:</p><p> </p>";
        }
    }
    function acknowledge_mail($name="")//1
    {
        $name                               =   ($name)?$name:'Candidate';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name.'</p>';
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p>This has reference to your application, indicating interest in seeking career opportunities with us.We thank you for the same.  Our team will review your application and will get back if your profile matches our needs for the role</p>";
        $mailer_array['note']               =   "<p></p>";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function interview_scheduled_mail($name="")//2
    {
        $name                               =   ($name)?$name:'Candidate';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name.'</p>';
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p>We are glad to inform you that your profile is shortlisted and we would like to personally meet you for a discussion on (Date) at (Time) at the following address. We hope this date and time is suitable to you. If it is not, we request you to indicate suitable date and time for the same.  </p>";
        $mailer_array['note']               =   "";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function interview_rescheduled_mail($dateIn,$TimeIn,$name="")//3
    {
        $name                               =   ($name)?$name:'Candidate';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name."</p>";
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p>This is to inform that your interview is rescheduled to ".$dateIn." at ".$TimeIn." at our office.</p>";
        $mailer_array['note']               =   "<p></p>";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function candidate_rejection_mail($name="")//4
    {
        $name                               =   ($name)?$name:'Candidate';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name."</p>";
        $mailer_array['greetings']          =   "<p></p>";
        $mailer_array['contents']           =   "<p>Thank you for your application. After reviewing your CV, we feel that  your profile is not suitable for our current requirement, so we've made the decision to not move forward at this time.  We shall reach out to you in the future when a position opens up that may be a good fit and you can also keep an eye on our jobs page as we're growing and adding openings.</p>";
        $mailer_array['note']               =   "<p>We wish you success in your job search.</p>";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function rejection_mail_after_interview($name="",$Position_name="")//5
    {
        $name                               =   ($name)?$name:'Candidate';
        $Position_name                      =   ($Position_name)?' of '.$Position_name:'';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name."</p>";
        $mailer_array['greetings']          =   "<p></p>";
        $mailer_array['contents']           =   "<p>Thank you for expressing your interest for the position ".$Position_name." . As you can imagine, we received a large number of applications and interviewed many candidates, I am sorry to inform you that you have not been selected for this position.</p>";
        $mailer_array['note']               =   "<p>We thank you for the time you invested and wish you the best of luck in your future endeavors.</p><p>Best wishes for a successful job search. Thank you, again, for your interest in our company.</p>";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function selected_to_next_round_mail($name="",$Position_name="",$Panel_name="")//6
    {
        $name                               =   ($name)?$name:'Candidate';
        $Position_name                      =   ($Position_name)?' of '.$Position_name:'';
        $Panel_name                         =   ($Panel_name)?$Panel_name:'Panel';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name."</p>";
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p> We enjoyed meeting with you to discuss your interest for the position  ".$Position_name." We feel your skills are a good match for this position and you have been shortlisted for the next round of an interview with the ".$Panel_name."</p>";
        $mailer_array['note']               =   "<p>This round of an interview will provide us with more insights into your capabilities as well as your overall fitness in the position and within our existing team. While the first round of an interview focused on your skills and abilities, this round of an interview will focus more on your technical skills.</p><p>We look forward to meet you again.</p>";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function provisional_offer_letter_mail($name="",$Position_name,$location,$ctc,$link)//7
    {
        $name                               =   ($name)?$name:'Candidate';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name."</p>";
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p>We are delighted to offer an employment with us as ".$Position_name." . The location of your reporting will be at ".$location." office and joining date will be on Date and Time.</p><p>Your gross salary will be ".$ctc.", the deductions shall be in accordance with company’s compensation policy.</p><p>Please indicate your acceptance of this provisional offer and your permission to conduct a background check. Upon receipt of your acceptance and uploading of required documents we shall provide you with the offer letter</p><p>Please <a href='".$link."'>click here </a> to find the employee details form and request you to fill the same and upload the documents before the joining date. This will facilitate the on boarding process.</p>";
        $mailer_array['note']               =   "<p>Documents to be uploaded: </p> <p><ol> Passport Size Photo</ol><ol>All the marks sheet photocopy – Starting from 10th class to highest qualification</ol><ol>KYC - Aadhar card, Pan card,  address proof (Electricity bill, rental agreement, DL, voter ID, Ration card)</ol><ol>Employment proofs including offer letter, salary slips, appraisal letter, relieving or experience letter of all the previous jobs.</ol></p><p>Note: (Originals to be presented for verification on the day of joining)</p> <p>Please contact contact person at Contact  number for any queries.</p> <p>We look forward to working with you. </p>";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function offer_letter_mail($name="",$Position_name="")//4
    {
        $name                               =   ($name)?$name:'Candidate';
        $Position_name                      =   ($Position_name)?' of '.$Position_name:'';
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "";
        $mailer_array['to_name']            =   "<p>Dear ".$name."</p>";
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p>We are pleased to offer you the position ".$Position_name." in our organization. Please find the attachment for the offer letter. </p>";
        $mailer_array['note']               =   "";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        return $mailer_array;
    }
    function jd_to_consultancy_mail($name="",$opPosition,$opDescription,$opExperience,$opLocation)//4
    {
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "Job Requirement";
        $mailer_array['to_name']            =   "<p>Hello ".$name."</p>";
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p>We are looking for candidates for below job description in our organization. Please refer if any.<br>Position Name  : ".$opPosition."<br>Job Description".$opDescription."<br>Experience In Years: ".$opExperience."<br>Location : ".$opLocation."</p>";
        $mailer_array['note']               =   "";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        $mailer_array['footer']             =   "";
        return $mailer_array;
    }
    function send_payslip_mail($name="")//4
    {
        $mailer_array                       =   array();
        $mailer_array['subject']            =   "Pay Slip";
        $mailer_array['to_name']            =   "<p>Hello ".$name."</p>";
        $mailer_array['greetings']          =   "<p>Greetings</p>";
        $mailer_array['contents']           =   "<p>Below is the attached copy of your Pay Slip</p>";
        $mailer_array['note']               =   "";
        $mailer_array['regards']            =   "<p>Regards,</p><p> Talent Acquisition Team </p>";
        $mailer_array['footer']             =   "";
        return $mailer_array;
    }
    function sendAbsconding_email_by_hr($toName='')
    {
        $mailer_array                       =   array();

        $mailer_array['body']               =   "<table style='border: 1px solid #edbd00; border-radius: 10px 10px 10px 10px; box-shadow: 1px 1px 5px #8e8e8e; width: 650px;' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td style='height: 18px; width: 650px; border-top-left-radius: 10px; border-top-right-radius: 10px; line-height: 18px;'>&nbsp;</td></tr><tr><td style='height: 85px; width: 650px; background-color: #ffffff;'><div style='width: auto; height: auto; float: left; display: block; margin: 20px 0px 15px 20px;'><a style='border:none; text-decoration:none;' href='#' target='_blank'><img src='./images/logo-new.png' alt='' /></a></div></td></tr><tr><td style='height: 1px; width: 650px; line-height: 1px; background-color: #e9e9e9;'>&nbsp;</td></tr><tr><td><div style='width: 650px; height: auto; float: left;'><p style='display: block; width: 590px; height: auto; padding: 1px 30px 10px 10px; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #505050; line-height: 20px; text-align: justify;'>To <br>  Mr/Ms. ".$toName.",<br>  <br>Sub :    <strong>Absconding Warning </strong>  <br /><br />  It has been noted that you have been absent from work for 2 days without   prior information or permission from your reporting manager. Therefore   this email is to notify you that you have 24 hours to report back to   work or you will be classed as an Absconding Employee.<br /><br /> Failing to report to duty will lead to stringent disciplinary action taken against you.<br>  <br>  Look forward to hearing back from you.<br>  <br>  Yours Sincerely,<br>  <br>  Human Resource Development Team</p></div></td></tr><tr><td><div style='width: 650px; height: auto; background-color: #0073ac; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; margin: 20px 0px 0px 0px;'><p style='display: block; width: 590px; height: auto; margin: 0px 30px 0px 30px; color: #ffffff; text-align: center; line-height: 20px; font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; padding: 15px 0px 15px 0px;'></p></div></td></tr></tbody></table>"; 
        return $mailer_array['body'] ;
    }
?>
