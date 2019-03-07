<?php 
if($quotation_details)
{
    $aq_id                        =   $quotation_details->aq_id;
    $scheme                       =   $quotation_details->scheme;
    $stage_1_manday               =   $quotation_details->stage_1_manday;
    $stage_2_manday               =   $quotation_details->stage_2_manday;
    $surveillance_manday          =   $quotation_details->surveillance_manday;
    $reduction_in_manday          =   $quotation_details->reduction_in_manday;
    $application_Fee              =   $quotation_details->application_Fee;
    $application_Fee_gst          =   $quotation_details->application_Fee_gst;
    $application_Fee_total        =   $quotation_details->application_Fee_total;
    $application_Fee_remark       =   $quotation_details->application_Fee_remark;
    $stage_1_assessment_fee       =   $quotation_details->stage_1_assessment_fee;
    $stage_1_assessment_gst       =   $quotation_details->stage_1_assessment_gst;
    $stage_1_assessment_total     =   $quotation_details->stage_1_assessment_total;
    $stage_1_assessment_remark    =   $quotation_details->stage_1_assessment_remark;
    $stage_2_assessment_fee       =   $quotation_details->stage_2_assessment_fee;
    $stage_2_assessment_gst       =   $quotation_details->stage_2_assessment_gst;
    $stage_2_assessment_total     =   $quotation_details->stage_2_assessment_total;
    $stage_2_assessment_remark    =   $quotation_details->stage_2_assessment_remark;
    $fee_total                    =   $quotation_details->fee_total;
    $gst_total                    =   $quotation_details->gst_total;
    $total_total                  =   $quotation_details->total_total;
    $total_remark                 =   $quotation_details->total_remark;
    $fee_1st_Surveillance         =   $quotation_details->fee_1st_Surveillance;
    $remark_1st_Surveillance      =   $quotation_details->remark_1st_Surveillance;
    $fee_2nd_Surveillance         =   $quotation_details->fee_2nd_Surveillance;
    $remark_2nd_Surveillance      =   $quotation_details->remark_2nd_Surveillance;
    $approve_status               =   $quotation_details->approve_status;
    
    $temp1_fee                  =   $quotation_details->temp1_fee;
    $temp1_gst                  =   $quotation_details->temp1_gst;
    $temp1_total                =   $quotation_details->temp1_total;
    $temp1_remark               =   $quotation_details->temp1_remark;
    
    $temp2_fee                  =   $quotation_details->temp2_fee;
    $temp2_gst                  =   $quotation_details->temp2_gst;
    $temp2_total                =   $quotation_details->temp2_total;
    $temp2_remark               =   $quotation_details->temp2_remark;
    
    
    $fee_1st_Surveillance_gst   =   ($fee_1st_Surveillance*18)/100;
    $fee_1st_Surveillance_total =   $fee_1st_Surveillance_gst+$fee_1st_Surveillance;
    
    $fee_2nd_Surveillance_gst   =   ($fee_2nd_Surveillance*18)/100;;
    $fee_2nd_Surveillance_total =   $fee_2nd_Surveillance_gst+$fee_2nd_Surveillance;
    
}
?>
<div style="padding: 100px;">
<table style="width: 921px;">
<tbody>
<tr style="height: 49px;">
<td style="width: 253px; height: 98px;" rowspan="2">
<p><img src="<?php echo $this->config->item('img_url');?>ias_logo.png" alt="IAS" width="120" height="100" /></p>
</td>
<td style="width: 431px;">
<p>Integrated Assessment Services (p)Ltd</p>
</td>
</tr>
</tbody>
</table>
<table style="width: 921px;">
<tbody>
<tr>
<th colspan="3" style="text-align: center;"><p ><strong><u>QUOTE FOR CERTIFICATION</u></strong></p></th>
</tr>
<tr>
<th>To <br />
<?php echo $database_record->profile_name;?>
<br />
<?php echo $database_record->profile_address;?>
</th>
<th></th>
<th>Date:<?php echo date('d-m-Y',strtotime($application_date));?></th>
</tr>
</tbody>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p><u>ABOUT US</u></p>
<p>&nbsp;</p>
<p>INTEGRATED ASSESSMENT SERVICES PVT LTD is one of the fast growing certification bodies in India.&nbsp; IAS is offering certification all over India and is expanding its operations in many other countries as well. IAS is accredited by Accreditation of Certification Bodies, (UQAS) U.K for ISO 9001, ISO 14001, OHSAS 18001, ISO 22000, ISO 27001 and ISO 13485.</p>
<p>&nbsp;</p>
<p>IAS believes in professional and partnership approach to certifications, which comes with &nbsp;&nbsp;sector&nbsp;&nbsp; specific experience&nbsp;&nbsp; and&nbsp;&nbsp; audit&nbsp;&nbsp; experience.&nbsp;&nbsp; IAS&nbsp;&nbsp; strengthens&nbsp;&nbsp; your management&nbsp; system&nbsp; through&nbsp; value&nbsp; added&nbsp; audits&nbsp; and&nbsp; creates&nbsp; confidence&nbsp;&nbsp; in&nbsp; the&nbsp; entire supply chain.</p>
<p>&nbsp;</p>
<p>PRODUCT CERTIFICATION:</p>
<ul>
<li>CE Marking under Machinery, LVD, EMC, PPE, RoHS, PED, SPVD, TPED, Lifts, ATEX, Medical Devices, Invitro, Construction Products and Toys.</li>
<li>IAS is also involved in product certification such as KOSHAR, HALAL, Green Certification, RoHS, UL, Oeko Certification, Chlorine Free certification, Lead Free Certification.</li>
</ul>
<p>&nbsp;</p>
<p>&nbsp;</p>
<ol>
<li>ORGANIZATION DETAILS</li>
</ol>
<table style="margin-left: auto; margin-right: auto; text-align: center;" border="1" width="800">
<tbody>
<tr>
<td width="169">
<p>&nbsp;</p>
<p>Name of &nbsp;Company:</p>
</td>
<td width="426">
<p><?php echo $database_record->profile_name;?></p>
</td>
</tr>
<tr>
<td width="169">
<p>&nbsp;</p>
<p>Address:</p>
</td>
<td width="426">
<p><?php echo $database_record->profile_address;?></p>
</td>
</tr>
<tr>
<td width="169">
<p>Phone No:</p>
</td>
<td width="426">
<p><?php echo $database_record->mobile_number;?></p>
</td>
</tr>
<tr>
<td width="169">
<p>E-mail Address:</p>
</td>
<td width="426">
<p><?php echo $database_record->profile_email;?></p>
</td>
</tr>
<tr>

<td width="169">
<p>Quote reference no:</p>
</td>
<td width="426">
<p><?php echo 'IASQTN000'.$aq_id; ?></p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<ol start="2">
<li>SCOPE OF CERTIFICATION:</li>
</ol>
<p>To provide <?php
    $scheme_array = explode(', ', $scheme);
    $come                   =   "";
    foreach($schemes_list	as $scheme_tail)
    {
       if(in_array($scheme_tail->scheme_id,$scheme_array))
       {
          echo $come.'<b>'.$scheme_tail->scheme_name.'</b> ';
          $come             =   ',';
       }
       
    }			  
    ?> Certification from IASPL for the following products:</p>
<p>&nbsp;</p>
<ul>
<li>Product Covered:</li>
</ul>

<p>&nbsp;</p>
<ul>
<li>Applicable Directives (Only for CE Marking):</li>
</ul>
<p>&nbsp;</p>
<table style="margin-left: auto; margin-right: auto; text-align: center;" border="1" width="800">
<tbody>
<tr>
<td width="66">
<p>S. NO</p>
</td>
<td width="518">
<p>Respective Directives</p>
</td>
</tr>
<tr>
<td width="66">
<p>1.</p>
</td>
<td width="518">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td width="66">
<p>2.</p>
</td>
<td width="518">
<p>&nbsp;</p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<ol start="3">
<li>FEES</li>
</ol>
<table style="margin-left: auto; margin-right: auto; text-align: center;" border="1" width="800">
<tbody>
<tr>
<td colspan="4" width="100%">
<p>INITIAL COST</p>
</td>
</tr>
<tr>
<td colspan="4" width="100%">
<p>For Product(s):</p>
</td>
</tr>
<tr>
<td width="26%">
<p>Service</p>
</td>
<td width="34%">
<p>Amount (Rs.)</p>
</td>
<td width="20%">
<p>GST @ 18%</p>
</td>
<td width="18%">
<p>Total Amount (Rs)</p>
</td>
</tr>
<tr>
<td width="26%">
<p>Application Fees</p>
</td>
<td width="34%">
<p>Rs.<?php echo $application_Fee; ?></p>
</td>
<td width="20%">
<p><Rs.<?php echo $application_Fee_gst; ?></p>
</td>
<td width="18%">
<p>Rs.<?php echo $application_Fee_total; ?></p>
</td>
</tr>
<tr>
<td width="26%">
<p>Guidance in Preparation of TCF (Technical File)</p>
</td>
<td width="34%">
<p>Rs.<?php echo $stage_1_assessment_fee; ?></p>
</td>
<td width="20%">
<p>Rs.<?php echo $stage_1_assessment_gst; ?></p>
</td>
<td width="18%">
<p>Rs.<?php echo $stage_1_assessment_total; ?></p>
</td>
</tr>
<tr>
<td width="26%">
<p>Document Review and Stage 1Audit</p>
</td>
<td width="34%">
<p>Rs.<?php echo $stage_2_assessment_fee; ?></p>
</td>
<td width="20%">
<p>Rs.<?php echo $stage_2_assessment_gst; ?></p>
</td>
<td width="18%">
<p>Rs.<?php echo $stage_2_assessment_total; ?></p>
</td>
</tr>
<tr>
<td width="26%">
<p>Assessment and Certification (by IASPL)</p>
</td>
<td width="34%">
<p>Rs.<?php echo $temp1_fee; ?></p>
</td>
<td width="20%">
<p>Rs.<?php echo $temp1_gst; ?></p>
</td>
<td width="18%">
<p>Rs.<?php echo $temp1_total; ?></p>
</td>
</tr>
<tr>
<td width="26%">
<p>TCF holding and Authorized Representation Charges</p>
</td>
<td width="34%">
<p>Rs.<?php echo $temp2_fee; ?></p>
</td>
<td width="20%">
<p>Rs.<?php echo $temp2_gst; ?></p>
</td>
<td width="18%">
<p>Rs.<?php echo $temp2_total; ?></p>
</td>
</tr>
<tr>
<td width="26%">
<p><strong>Total Amount</strong></p>
</td>
<td width="34%">
<p><strong>Rs.<?php echo $fee_total; ?></strong></p>
</td>
<td width="20%">
<p><strong>Rs.<?php echo $gst_total; ?></strong></p>
</td>
<td width="18%">
<p><strong>Rs.<?php echo $total_total; ?></strong></p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="margin-left: auto; margin-right: auto; text-align: center;" border="1" width="800">
<tbody>
<tr>
<td colspan="5" width="630">
<p>Surveillance -1 ( To be paid at the time of Surveillance audit &ndash; 1<sup>st</sup>&nbsp; Year)</p>
</td>
</tr>
<tr>
<td width="195">
<p>Service</p>
</td>
<td width="48">
<p>Unit</p>
</td>
<td width="156">
<p>Amount (Rs.)</p>
</td>
<td width="101">
<p>GST @ 18 %</p>
</td>
<td width="130">
<p>Total Amount (Rs)</p>
</td>
</tr>
<tr>
<td width="195">
<p>&nbsp;</p>
<p>Surveillance audits</p>
</td>
<td width="48">
<p>1</p>
</td>
<td width="156">
<p>Rs.<?php echo $fee_1st_Surveillance; ?></p>
</td>
<td width="101">
<p><?php echo $fee_1st_Surveillance_gst; ?></p>
</td>
<td width="130">
<p><?php echo $fee_1st_Surveillance_total; ?></p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table style="margin-left: auto; margin-right: auto; text-align: center;" border="1" width="800">
<tbody>
<tr>
<td colspan="5" width="630">
<p>Surveillance -2 ( To be paid at the time of Surveillance audit- 2<sup>nd</sup> Year)</p>
</td>
</tr>
<tr>
<td width="195">
<p>Service</p>
</td>
<td width="48">
<p>U&nbsp; Unit</p>
</td>
<td width="156">
<p>Amount (Rs.)</p>
</td>
<td width="101">
<p>GST @ 18 %</p>
</td>
<td width="130">
<p>Total Amount (Rs)</p>
</td>
</tr>
<tr>
<td width="195">
<p>&nbsp;</p>
<p>Surveillance audits</p>
</td>
<td width="48">
<p>1</p>
</td>
<td width="156">
<p>Rs.<?php echo $fee_2nd_Surveillance; ?></p>
</td>
<td width="101">
<p>Rs.<?php echo $fee_2nd_Surveillance_gst; ?></p>
</td>
<td width="130">
<p>Rs.<?php echo $fee_2nd_Surveillance_total; ?></p>
</td>
</tr>
</tbody>
</table>
<p>Note:</p>
<ol>
<li>a) Traveling cost for one auditor from Chennai be charged extra for outstation clients.</li>
<li>b) Boarding &amp; Lodging in case to be arranged by IASPL will be charged extra at actual</li>
<li>c) 50% of Initial costs in advance, 50% on audit.</li>
<li>d) The above quote is valid for 60 days and covers the product as mentioned in the scope of certification.</li>
</ol>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u>G</u><u>E</u><u>N</u><u>E</u><u>R</u><u>A</u><u>L</u><u>&nbsp;</u><u>T</u><u>E</u><u>R</u><u>M</u><u>S</u><u>&nbsp;</u><u>&amp;</u><u>&nbsp;</u><u>CO</u><u>N</u><u>D</u><u>I</u><u>T</u><u>I</u><u>O</u><u>N</u><u>S</u></p>
<ul>
<li>Applicant is responsible for design, production&nbsp;&nbsp; and&nbsp;&nbsp; quality assurance&nbsp;&nbsp; of&nbsp;&nbsp; the product(s).</li>
<li>Applicant shall provide all the technical documentation such as product description, drawings, circuit diagrams, Bill of material, instruction manuals etc. as and when required by IASPL.</li>
<li>IASPL has no liability for errors made in connection with the performance of order except for errors due to gross negligence of IASPL.</li>
<li>In case required by IASPL, applicant shall provide free of all costs to IASPL, sample of product for testing/examination to ensure that it conforms with approved design. In such cases, applicant is obliged to pay costs related to such testing/examination.</li>
<li>The quotation is based on the information provided by the applicant to IASPL. The quotation is valid for the scope that has been stated in quote. In case of any deviations from the information and scope of certification, the charges may need to be altered accordingly.</li>
<li>Applicant shall notify in writing about any proposed product alterations. IASPL will evaluate whether certificate can be maintained or equipment has to be re-certified, if necessary by repeated testing. IASPL takes&nbsp;&nbsp; no responsibility&nbsp;&nbsp; for damages&nbsp;&nbsp; to the supplied sample during testing, storage or transportation.</li>
<li>In case product fails during testing&nbsp;&nbsp; and&nbsp;&nbsp; client requests&nbsp;&nbsp; retesting after&nbsp;&nbsp; taking necessary</li>
</ul>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<ul>
<li>Applicant shall provide safe and unrestricted access to pertinent work area.</li>
<li>The validity of certificate issued by a REPRESENTATIVE BODY is Three years for CE MARKING; Two years for Green and Okeo-Tex Certification. After this&nbsp;&nbsp; period&nbsp; there&nbsp; is&nbsp; possibility of&nbsp; prolongation&nbsp; of&nbsp; validity&nbsp; of&nbsp; certificate,&nbsp; if&nbsp; during previous three years/two years there&nbsp; have not been any changes of conditions, which can have influence&nbsp; on granting of&nbsp; &nbsp; Validity of certificate can be cancelled before its Period of validity, if the holder of certificate infringe or not abide conditions during which certificate has been granted.</li>
<li>Please complete the DECLARATION FORM enclosed here with and send it immediately to IASPL along with &lsquo;Technical construction File&rsquo;(TCF) in case of CE MARKING certification.</li>
<li>We understand the requirements of IASPL defined in the PROCEDURE FOR PRODUCT CERTIFICATION.</li>
</ul>
<p>&nbsp;</p>
<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</p>




<table style="margin-left: auto; margin-right: auto; text-align: center;" border="1" width="800">
<tbody>
<tr>
<td width="294">
<p>&nbsp;</p>
<p>(IAS)</p>
</td>
<td width="60">
<p>&nbsp;</p>
</td>
<td width="298">
<p><strong>&nbsp;</strong></p>
<p><strong>M/s:</strong></p>
<p><strong>&nbsp;</strong></p>
</td>
</tr>
<tr>
<td width="294">
<p>Authorized Signature</p>
</td>
<td width="60">
<p>&nbsp;</p>
</td>
<td width="298">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Signature with stamp</p>
</td>
</tr>
<tr>
<td width="294">
<p>Integrated Assessment Services Pvt.Ltd.</p>
<p>1495, MANSAROVAR,16th Main Road,</p>
<p>Anna Nagar West,</p>
<p>Chennai - 600 040.</p>
</td>
<td width="60">
<p>&nbsp;</p>
</td>
<td width="298">
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
