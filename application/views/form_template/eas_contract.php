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
}
?>
<div style="padding: 100px;">
<div>
<table style="width: 921px;" cellspacing="0" cellpadding="0">
<tbody>
<tr style="height: 49px;">
<td style="width: 253px; height: 98px;" rowspan="2">
<p><img src="<?php echo $this->config->item('img_url');?>eas_logo.png" alt="EAS" width="120" height="100" /></p>
</td>
<td style="width: 383px; height: 49px;">
<p>Empowering Assurance Systems (p) Ltd</p>
</td>
<td style="width: 379px; height: 49px;">
<p>Document ID: EAS 001</p>
</td>
</tr>
<tr style="height: 49px;">
<td style="width: 383px; height: 49px;">
<p>Certification audit contract</p>
</td>
<td style="width: 379px; height: 49px;">
<p>&nbsp;&nbsp; V 1.4 Dated: 25.03.2017</p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
</div>
<p><strong>&nbsp;</strong></p>
<p><strong><u>CERTIFICATION AUDIT CONTRACT</u></strong></p>
<p><strong>&nbsp;</strong></p>
<table cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td>
<p>This Contract (as mandated in clause 5.1.2 of IS/ISO/IEC 17021: 2015) is made on this date <?php echo date('d-m-Y',strtotime($application_date));?> by and between <strong><u>M/s:</u></strong><strong><?php echo $database_record->profile_name;?></strong></p>
<p><strong>Address: </strong><strong><?php echo $database_record->profile_address;?></strong></p>
</td>
</tr>
<tr>
<td>
<p><em>Please detail any site address </em></p>
<p>(here-in-after called Client) and</p>
<p><strong><u>M/s:</u></strong><u> </u><strong><u>Empowering Assurance Systems</u></strong><u> </u><strong><u>P</u></strong><u> </u><strong><u>Ltd</u></strong></p>
<p>Address: Regd. Office: 1495, Manasarovar, 16th Main Road, Anna Nagar West, Chennai - 600040.</p>
</td>
</tr>
</tbody>
</table>
<p><strong>&nbsp;</strong><strong>(here-in-after called</strong> <strong>EAS)</strong> <strong>for</strong> <strong>the</strong> <strong>provision</strong> <strong>of</strong> <strong>Certification</strong> <strong>Activities</strong> <strong>as</strong> <strong>follows:</strong></p>
<p><strong>Article</strong> <strong>1:</strong> <strong>Purpose</strong> <strong>of</strong> <strong>Contract</strong></p>
<p>The purpose of this contract is to describe the rights and duties of EAS and Client while performing certification Audit requested by Client as per the EAS certification requirements.</p>
<p><strong>Article</strong> <strong>2:</strong> <strong>Certification</strong> <strong>Scope</strong></p>
<p>Client shall have a documented management system that meets applicable standard and other normative documents.</p>
<p>EAS shall assess and certify Client&rsquo;s management system according to the standard and scope initially specified by Client. The Final Certification scope shall be limited to the products, services and other activities assessed &amp; confirmed during Certification Audit(s).</p>
<p><strong>Article</strong> <strong>3:</strong> <strong>General</strong> <strong>requirements</strong></p>
<ol style="margin-left: 50px;"type="1">
<li>Certification Audit of Client&rsquo;s management system shall be performed on the basis of the requirements of<strong>
    <?php
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
    ?>
</strong></li>
<li>The audit program shall include a two-stage initial audit, surveillance audits in the first and second years, and a re-certification audit in the third year prior to expiration of certificate.</li>
<li>An audit plan shall be established for each audit in consultation with the Client.</li>
<li>A documented report shall be provided after each audit</li>
<li>Client shall make all necessary arrangements for the conduct of the audits, including provision for examining documentation and access to all processes and areas, records and personnel for the purpose of initial certification, surveillance, re-certification and resolution of complaints.</li>
<li>Client shall make provisions, where applicable, to accommodate the presence of observers (e.g. accreditation auditors or trainee auditors).</li>
<li>Client shall comply with certification requirements.</li>
</ol>
<p><strong>Article 4: Certification Audit</strong></p>
<p><strong>Stage</strong> <strong>1 audit:</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The stage 1 audit is performed by EAS auditor(s).</p>
<p>a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to audit the client's management system documentation;</p>
<p>b) to evaluate the client's location and site-specific conditions and to undertake discussions with the client's personnel to determine the preparedness for the stage 2 audit;</p>
<p>c) &nbsp;&nbsp;to review the client's status and understanding regarding requirements of the standard, in particular with respect to the identification of key performance or significant aspects, processes, objectives and operation of the management system</p>
<p>d) &nbsp; to collect necessary information regarding the scope of the management system, processes and location(s) of the client, and related statutory and regulatory aspects and compliance (e.g. quality, environmental, legal aspects of the client's operation, associated risks, etc.);</p>
<p>e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to review the allocation of resources for stage 2 audit and agree with the client on the details of the stage 2 audit;</p>
<p>f)&nbsp; &nbsp;to provide a focus for planning the stage 2 audit by gaining a sufficient understanding of the client's management system and site operations in the context of possible significant aspects;</p>
<p>g) &nbsp;&nbsp;to evaluate if the internal audits and management reviews are being planned and performed, and that the level of implementation of the management system substantiates that the client is ready for the stage 2 audit.</p>
<p>h)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; For most management systems, at least part of the stage 1 audit is carried out at the client's premises in order to achieve the objectives stated above.</p>
<p>i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Stage 1 audit findings shall be documented and communicated to the client, including identification of any areas of concern that could be classified as nonconformity during the stage 2 audit.</p>
<p>j)&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EAS while determining the interval between stage 1 and stage 2 audits shall give consideration to the needs of the client to resolve areas of concern identified during the stage 1 audit.</p>
<p>k)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EAS may also need to revise its arrangements for stage 2.</p>
<p><strong>Stage</strong> <strong>2</strong> <strong>audits:</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The purpose of the stage 2 audit is to evaluate the implementation, including effectiveness, of the client's management system.</p>
<p>&nbsp;&nbsp;&nbsp;&nbsp; Audit is performed by EAS auditor(s). This takes place at the site(s) of the client. It includes at least the following:</p>
<p>a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; information and evidence about conformity to all requirements of the applicable management system standard or other normative document;</p>
<p>b)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; performance monitoring, measuring, reporting and reviewing against key performance objectives and targets (consistent with the expectations in the applicable management system standard or other normative document);</p>
<p>c)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; the client's management system and performance as regards legal compliance;</p>
<p>d)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; operational control of the client's processes;</p>
<p>e)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; internal auditing and management review;</p>
<p>f)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; management responsibility for the client's policies;</p>
<p>g)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Links between the normative requirements, policy, performance objectives and targets (consistent with the expectations in the applicable management system standard or other normative document), any applicable legal requirements, responsibilities, competence of personnel, operations, procedures, performance data and internal audit findings and conclusions.</p>
<p>h)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If nonconformity is found during Stage 2 Audit, EAS shall issue NCR (Non-conformity Report).</p>
<p>i)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Client shall take or propose corrective actions and submit to EAS. EAS shall review the actions by examining evidences attached and/or express a need for a follow-up visit.</p>
<p><strong>Article</strong> <strong>5:</strong> <strong>Confirmation</strong> <strong>of</strong> <strong>Certification</strong> <strong>Scope</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Audit team and Client shall discuss certification standard, certification scope (item) and address of sites with each other. If the application of scope is ambiguous, it shall be resolved in accordance with EAS criteria and the final scope shall be stated and confirmed by the Client.</p>
<p><strong>Article</strong> <strong>6:</strong> <strong>Issuing</strong> <strong>Certificate</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; The certificate shall be granted to a client with a validity of three years (or less in case of transfer) after the following conditions have been met by the client organization:</p>
<ol style="margin-left: 50px;"type="1">
<li>Client has a documented management system that meets applicable standard or other normative documents.</li>
<li>One Internal Audit and Management Review cycle has been completed and Non-Conformity, if any, have been corrected.</li>
<li>The applicant meets the criteria of certification and all Major non-conformity found during Audit have been addressed and satisfactorily closed.</li>
<li>There are no adverse reports/ information/ complaints with the EAS about the applicant regarding the quality and effectiveness of implementation of certification system as per EAS certification criteria</li>
<li>The applicant has paid all the fees as per &lsquo;Fee Structure &ndash; Annexure A of this document&rsquo;.&nbsp;&nbsp;&nbsp;&nbsp;</li>
</ol>
<p>EAS shall after review of the result of corrective action(s) submitted by Client and on point number 4 above satisfy itself prior to granting its certificate.&nbsp; If EAS decides that the corrective action(s) taken or proposed by Client is (are) acceptable, then the certificate shall be issued.&nbsp; The date of issue shall be the date of formal decision by EAS.</p>
<p>In case of ISO 9001: 2008 and ISO 14001 : 2004 Certificates, Client shall ensure that the management system of the organization will be revised to be in line with 2015 version and the audit process will be completed before 15 September, 2018.&nbsp; After this date, the earlier versions will not be valid.</p>
<p><strong>Article</strong> <strong>7:</strong> <strong>Rights</strong> <strong>&amp;</strong> <strong>duties</strong> <strong>of</strong> <strong>Client</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Client shall (in addition to what is stated elsewhere);</p>
<ol style="margin-left: 50px;"type="1">
<li>Conforms to the requirements of EAS when making reference to its certification status in communication media such as the Internet, brochures or advertising, or other documents,</li>
<li>Not make or permit any misleading statement regarding its certification,</li>
<li>Not use or permit the use of a certification document or any part thereof in a misleading manner,</li>
<li>Upon suspension or withdrawal of its certification, discontinue its use in all advertising matter that contains a reference to certification, as directed by EAS,</li>
<li>Amends all advertising matter when the scope of certification has been reduced,</li>
<li>Not allow reference to its management system certification to be used in such a way as to imply that the EAS certifies a product (including service) or process,</li>
<li>Not imply that the certification applies to activities that are outside the scope of certification, and</li>
<li>Not use its certification in such a manner that would bring EAS and/or its certification system into disrepute and lose public trust.</li>
<li>Not use certification mark on laboratory test, calibration or inspection reports</li>
<li>Not use certification mark on a product or product packaging that may be interpreted as denoting product conformity.</li>
<li>Record and address complaints, report complaints to EAS</li>
<li>For ensuring that EAS Personnel are provided adequate protective equipment for the safety, as applicable. Where special training is required this is to be disclosed and provided by Client in advance of a visit.</li>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Pay EAS for the Certification Activities as per the Fee Structure.</li>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notify EAS within 30 days of changes to Client's quality system or changes significantly affecting Client, such as a change of ownership, change in key personnel or facilities, which call &ldquo;change&rdquo; from now</li>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Allow EAS to conduct special audits at short notice to investigate complaints, or in response to changes, or as follow up on suspension.</li>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notice of changes by a client</li>
</ol>
<p>Client shall inform EAS, without delay, of matters that may affect the capability of the management system to continue to fulfill the requirements of the standard used for certification. These include, for example, changes relating to</p>
<ol style="margin-left: 50px;"type="1">
<li>the legal, commercial, organizational status or ownership,</li>
<li>organization and management (e.g. key managerial, decision-making or technical staff),</li>
<li>contact address and sites,</li>
<li>scope of operations under the certified management system, and</li>
<li>Major changes to the management system and processes.</li>
<li>EAS shall review the changes and determine the necessary action including short-notice audits revisions to certificates if required.</li>
<li>For OHSMS Certification, the client shall inform EAS, without delay, of any significant events including, but not limited to fatal incidents, serious injuries, occupational disease or legal action by a regulatory authority.</li>
<li>For OHSMS Certification, the client shall fully inform EAS, at the time of surveillance or recertification, of any OHS related findings by third-parties.</li>
</ol>
<p><strong>Article</strong> <strong>8:</strong></p>
<ol style="margin-left: 50px;"type="1">
<li>EAS has the ownership of certificates issued, certification documents, marks and audit reports. So long as Client maintains its status as being certified by EAS pursuant to the Client's Rights and Duties as above, Client will have the non-exclusive and non-transferable right to use the Certificate, the EAS Logo and any Accreditation Marks (except during Suspension as per Article 12 or violation of condition(s) as stipulated in EAS website) in Client's advertising and marketing materials and campaigns.</li>
<li>EAS shall take actions as appropriate to deal with the incorrect references to certification status or misleading use of certification documents, marks or audit reports. Such action could include requests for correction and corrective action, suspension, withdrawal of certification, publication of transgression and, if necessary, legal action.</li>
</ol>
<p><strong>Article</strong> <strong>9:</strong> <strong>On-going</strong> <strong>Surveillance</strong></p>
<ol style="margin-left: 50px;"type="1">
<li>If certified client agrees to subject itself to a minimum of two/one surveillance audits annually. The first surveillance audit shall be scheduled within or &nbsp;twelveth month from the date of stage 2 Audit and Second surveillance audit shall be scheduled within (or) 24<sup>th</sup> month for Stage 2 Audit.</li>
<li>A Periodic &ldquo;On-going Surveillance Plan&rdquo; shall be provided to the client with the certificate.</li>
<li>The Auditor days shall be as per &lsquo;Auditor Days based on EAS Criteria</li>
</ol>
<p><strong>Article</strong> <strong>10:</strong><strong> Re-certification </strong><strong>Audit</strong></p>
<p>Re-certification Audit shall be performed every three years or less from the date of stage 2 Audit. &nbsp;Re-certification Audit program verifies overall continuing effectiveness of the Client&rsquo;s management system in its entirety. EAS shall re-issue the certificate after re-certification audit and certification decision within 3 years validity from the date of re-certification stage 2 Audit. Client will be informed through letter stating that the expiration of existing certificate and new contract for Re-Certification.</p>
<p><strong>Article</strong> <strong>11:</strong> <strong>Withdrawal</strong> <strong>of</strong> <strong>EAS</strong><strong>&rsquo; </strong><strong>Accreditation</strong></p>
<p>If withdrawal happens as a result of EAS fault, the existing terms of contract will continue including the terms of costs and payments and EAS shall ensure that the existing certification of client is transferred to another reputed certifying organization and no additional costs shall be payable by the client, until expiry of 3 years from the date of initial certification.</p>
<p><strong>Article</strong> <strong>12:</strong> <strong>Suspension</strong> <strong>of</strong> <strong>Certification</strong> <strong>(After</strong> <strong>Granting</strong> <strong>Certification)</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EAS may suspend Client&rsquo;s Certification In the event that</p>
<p>1&nbsp;&nbsp; Client does not accept on-going surveillance visit within the time agreed;</p>
<ol style="margin-left: 50px;"start="2" type="1">
<li>Client failed to correct the nonconformity found at on-site Audit within the time agreed ;</li>
<li>Client failed to timely correct nonconformity, caused by misuse of certification mark, within one (1) month.</li>
<li>EAS determines that Client does not have resources or organization to satisfy with requirements of standard applied, or the certified system does not exist.</li>
<li>Client loses his credibility as a result of claims raised by interested parties and social conflicts.</li>
<li>The required actions against the changes of Certification system or requirements have not been taken by Client during the specified period.</li>
<li>Client didn&rsquo;t pay EAS for Certification activities as per the agreed fee structure in time.</li>
<li>EAS determines that Client didn&rsquo;t comply with obligation as defined in Article 6 of this contract.</li>
<li>Client used and/or applied the certificate to out of its certified scope.</li>
<li>It is proved that the information and/or materials provided by Client during Audit are misleading.</li>
<li>Client does not comply with article 8 &ldquo;Notification of Client&rsquo;s Change and Audit for Confirming Change&rdquo; of this contract.</li>
<li>EAS determines that Client does not comply with the contract(s) and/or contract(s) concluded with EAS.</li>
</ol>
<p><strong>Article</strong> <strong>13:</strong> <strong>Withdrawal</strong> <strong>of</strong> <strong>Certification</strong> <strong>(After</strong> <strong>Granting</strong> <strong>Certification)</strong></p>
<p>EAS may withdraw Client&rsquo;s Certification In cases when the client is not complying with followings</p>
<p>1.&nbsp; Failure to correct the reason for suspension within three months</p>
<p>2. Client discontinues the activities (manufacturing, installation, etc.) related to the scope of certification.</p>
<p>3. The certified Client is no longer identified because of its dismantlement or communication disconnecting, etc</p>
<p>4. The suspension of Client&rsquo;s Certification is more then 3 times during the term of validity of its Certification.</p>
<p>5. After receipt of EAS&rsquo; request to return the certificate(s), Client didn&rsquo;t return its certificate(s) to EAS within one (1) month.</p>
<p>6. Failure to make payment of any audit activity in due date.</p>
<p><strong>Article</strong> <strong>14:</strong> <strong>Appeals,</strong> <strong>Complaints</strong> <strong>and</strong> <strong>Disputes</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; If Client has appeals, complaints and disputes relating to Certification Audit and/or certification process, Client shall submit the appeals, complaints and disputes in writing to EAS. EAS shall handle the appeals, complaints and disputes, filed by Client, in accordance with EAS defined procedure. The result shall be provided to Client in writing. &ldquo;All unsettled disputes, if any and if necessary, shall be settled in a court of Law within the jurisdiction of the city of Chennai, Tamilnadu, India&rdquo;</p>
<p><strong>Article</strong> <strong>15:</strong> <strong>Confidentiality</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Information about Client shall not be disclosed to a third party without written consent of Client or individual concerned except in case where required by accreditation requirements. Where EAS is required by law to release confidential information to a third party, the Client or individual concerned shall be notified in advance of the information provided. Information about the client from sources other than the client (e.g complainant, regulators) shall be treated as confidential, consistent with the EAS&rsquo; policy. In the following cases, the information can be disclosed to a third party without written consent of Client;</p>
<ol style="margin-left: 50px;"type="1">
<li>&nbsp;&nbsp; Information about the certification status i.e validity, suspended or withdrawn.</li>
<li>&nbsp;&nbsp; Information required by accreditation body for evaluation of EAS.</li>
<li>&nbsp;&nbsp; Information about misuse of logo or certification scope by the client.</li>
</ol>
<p><strong>Article</strong> <strong>16:</strong> <strong>Notice</strong> <strong>of</strong> <strong>changes</strong> <strong>by</strong> <strong>EAS</strong></p>
<p>EAS shall give its certified clients due notice of any changes to its requirements for certification and verify compliance as follows:</p>
<ol style="margin-left: 50px;"type="1">
<li>EAS shall inform Client about changed requirements in detail and three month(s) of transitional period shall be observed.</li>
<li>Client shall submit documented implementation plan of changed requirements or its result in detail.</li>
<li>During surveillance audit, EAS shall verify Client&rsquo;s implementation and compliance of changed system based on Certification requirements changed.</li>
</ol>
<p><strong>Article</strong> <strong>17:</strong> <strong>Certification</strong> <strong>Fee</strong></p>
<ol style="margin-left: 50px;"type="1">
<li>All Audit activities including application, initial certification and surveillance audits shall be charged as per the &lsquo;<strong>FEE</strong> <strong>STRUCTURE</strong><strong>&rsquo;. </strong>If the Audit team decides that nonconformity, found during Audit, should be verified through follow-up visit, verification Audit shall be performed at the clients cost, subject to mutual understanding and acceptance of all terms between the client and EAS, in writing for verification Audit, if any.</li>
</ol>
<p><strong>Article</strong> <strong>18:</strong> <strong>Payment</strong></p>
<ol style="margin-left: 50px;"type="1">
<li>When concluding this contract, Client shall pay the Application fee.</li>
<li>All Audit fees (initial Audit, on-going surveillance, verification Audit and re-Audit etc.) shall be paid seven days prior to the Audit. In the event that the invoice is delayed, the fee shall be paid within seven days of receipt of the invoice.</li>
<li>All boarding, lodging &amp; traveling expenses shall be intimated in advance to the client and upon approval such expenses will be invoiced to Client .with Audit fees.</li>
<li>All fees with service tax have to be paid by cheque /DD only in favor of &lsquo;Empowering Assurance Systems Pvt Ltd&rsquo;.</li>
</ol>
<p><strong>Article</strong> <strong>19:</strong> <strong>Short-notice</strong> <strong>audits</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EAS may carry out audits of minimum one man-day on the certified clients at short notice to investigate complaints, or in response to changes, or as follow up on suspended clients. Client shall accept such audits and co-operate to meet the objectives of the audit. Fee for such audits shall be paid by the client as per the Man-day fee agreed with EAS, provided however that the client shall be informed well in advance about such audits and the estimated fees for the same.</p>
<p><strong>Article</strong><strong> 20</strong><strong>:</strong> <strong>Force</strong> <strong>Majeure</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Neither party shall be liable to the other party for nonperformance or delay in performance of any of its obligations under this contract due to war, natural disaster, epidemic, go-slow, lockout or any other causes reasonably beyond its control or unforeseen circumstances.</p>
<p><strong>Article</strong> <strong>21:</strong> <strong>Contract</strong> <strong>Interpretation</strong> <strong>and</strong> <strong>disputes</strong> <strong>settlement</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In case(s) of dispute(s) which may arise between the parties in respect of the execution, interpretation and performance of this Contract, both parties shall do their best to settle in an amicable manner, otherwise it will be referred to arbitrator for arbitration who will appointed with the mutual consent of both the above parties and his arbitrarily decision shall be binding upon both the party and the provision of The Indian Arbitration and conciliation Act 1996, will be applicable on them. The provision of The Indian Contract Act 1872 will be applicable if not mentioned in the contract. The event of a dispute the parties agree to submit to the jurisdiction of Chennai.</p>
<p><strong>Article</strong> <strong>22:</strong> <strong>Reliability,</strong> <strong>faithfulness</strong> <strong>and</strong> <strong>mutual</strong> <strong>co-operation</strong></p>
<ol style="margin-left: 50px;"type="1">
<li>Both parties shall comply with all articles stated in this contract upon mutual trust. EAS shall do its duties and Client shall give best assistance to EAS.</li>
<li>Client shall comply with all laws related to certification and give assistance for special surveillance Audit required by Accreditation body, if requested.</li>
<li>Client should allow trainee to participate in Audit when requested by EAS.</li>
<li>When Client chooses another certification body say for recertification, Client shall inform EAS about its intention and reason.</li>
</ol>
<p><strong>Article</strong> <strong>23:</strong> <strong>Limitation</strong> <strong>of</strong> <strong>Liability</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; EAS liability in respect of any single event or series of events for breach of EAS obligations as per this Contract shall be strictly limited to the amounts payable by Client to EAS in the 12 months preceding the date of the event or events. EAS is not liable for any other liability (including any third party liability) claim what so ever is claimed by the client to EAS. EAS is also not liable for any claims passed by its clients in case their products or services malfunction with their own clients or users. EAS has procured professional indemnity insurance to cover such as liabilities, if any.</p>
<p><strong>Article</strong> <strong>24:</strong> <strong>The</strong> <strong>Term</strong> <strong>of</strong> <strong>Contract</strong><strong>&nbsp; </strong></p>
<p>This contract shall become effective upon signing and shall continue in full force and effect till the certificate is valid. And the term of this contract can be extended by re-Audit and re-certification. This contract can be changed and/or renewed by mutual consent between both parties, if so required.</p>
<p><strong>Article</strong> <strong>25:</strong> <strong>Retention</strong> <strong>of</strong> <strong>contract</strong></p>
<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; IN WITNESS WHEREOF, Client and EAS hereby execute this Contract as of the date first set forth above in presence of the following witnesses. EAS shall retain copy of this original. This contract (duplicate) shall be retained by the client.</p>
<p><strong>Article 26: Accreditation Board Witnessed Audits</strong></p>
<p>It is a condition of this contract that all EAS certificated clients should, if requested, allow Accreditation Board auditors to witness EAS staff carrying out their audits. Failure to allow this could jeopardize the client's registration.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>Refers to Article 6, 7 &amp; 17 of Certification Audit Contract)</p>
<strong>1.Scope</strong>
<div>
<table style="width: 800px; margin-left: auto; margin-right: auto; text-align: center;" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 113px;">
<strong>Standard</strong>
</td>
<td style="width: 804px;" class="text-center">
<p><?php
    $scheme_array = explode(', ', $scheme);
    $come                   =   "";
    foreach($schemes_list	as $scheme_tail)
    {
       if(in_array($scheme_tail->scheme_id,$scheme_array))
       {
          echo $come.' <b>  '.$scheme_tail->scheme_name.'  </b> ';
          $come             =   ',';
       }
       
    }			  
    ?></p>
</td>
</tr>
</tbody>
</table>
</div>
<p><strong>&nbsp;</strong></p>
<table style="width: 800px; margin-left: auto; margin-right: auto; text-align: center;" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 912px;" colspan="3">
<p><strong>Man-days Required (for QMS / EMS / OHSMS/ FSMS/ ISMS/EnMS)</strong></p>
</td>
</tr>
<tr>
<td style="width: 401px;" colspan="2">
<p><strong>Man day calculation for the scheme</strong></p>
</td>
<td style="width: 511px;">
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td style="width: 18px;">
<p><strong>1</strong></p>
</td>
<td style="width: 383px;">
<p><strong>Stage 1Audit Man-days</strong></p>
</td>
<td style="width: 511px;">
<p><?php echo $stage_1_manday; ?></p>
</td>
</tr>
<tr>
<td style="width: 18px;">
<p><strong>2</strong></p>
</td>
<td style="width: 383px;">
<p><strong>Stage 2Audit Man-days </strong></p>
</td>
<td style="width: 511px;">
<p><?php echo $stage_2_manday; ?></p>
</td>
</tr>
<tr>
<td style="width: 18px;">
<p><strong>3</strong></p>
</td>
<td style="width: 383px;">
<p><strong>Surveillance Audit Man-days</strong></p>
</td>
<td style="width: 511px;">
<p><?php echo $surveillance_manday; ?></p>
</td>
</tr>
<tr>
<td style="width: 18px;">
<p><strong>4</strong></p>
</td>
<td style="width: 383px;">
<p><strong>Any reduction in manday</strong></p>
</td>
<td style="width: 511px;">
<p><?php echo $reduction_in_manday; ?></p>
</td>
</tr>
</tbody>
</table>
<p><strong>2.Certification Charges </strong>(To be paid now)</p>
<table style="width: 800px; margin-left: auto; margin-right: auto; text-align: center;" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 249px;">
<strong>Service</strong>
</td>
<td style="width: 143px;">
<p><strong>Fees</strong></p>
</td>
<td style="width: 145px;">
<p><strong>GST</strong></p>
</td>
<td style="width: 130px;">
<p><strong>Total Amount</strong></p>
</td>
<td style="width: 237px;">
<p><strong>Remarks</strong></p>
</td>
</tr>
<tr>
<td style="width: 249px;">
<p>Application Fee</p>
</td>
<td style="width: 143px;">
<p><?php echo $application_Fee; ?></p>
</td>
<td style="width: 145px;">
<p><?php echo $application_Fee_gst; ?></p>
</td>
<td style="width: 130px;">
<p><?php echo $application_Fee_total; ?></p>
</td>
<td style="width: 237px;">
<p><?php echo $application_Fee_remark; ?></p>
</td>
</tr>
<tr>
<td style="width: 249px;">
<p>Stage 1 Assessment</p>
</td>
<td style="width: 143px;">
<p><?php echo $stage_1_assessment_fee; ?></p>
</td>
<td style="width: 145px;">
<p><?php echo $stage_1_assessment_gst; ?></p>
</td>
<td style="width: 130px;">
<p><?php echo $stage_1_assessment_total; ?></p>
</td>
<td style="width: 237px;">
<p><?php echo $stage_1_assessment_remark; ?></p>
</td>
</tr>
<tr>
<td style="width: 249px;">
<strong>Stage 2 Assessment</strong>
</td>
<td style="width: 143px;">
<p><?php echo $stage_2_assessment_fee; ?></p>
</td>
<td style="width: 145px;">
<p><?php echo $stage_2_assessment_gst; ?></p>
</td>
<td style="width: 130px;">
<p><?php echo $stage_2_assessment_total; ?></p>
</td>
<td style="width: 237px;">
<p><?php echo $stage_2_assessment_remark; ?></p>
</td>
</tr>
<tr>
<td style="width: 249px;">
<strong>Total Amount</strong>
</td>
<td style="width: 143px;">
<p><strong><?php echo $fee_total; ?></strong></p>
</td>
<td style="width: 145px;">
<p><strong><?php echo $gst_total; ?></strong></p>
</td>
<td style="width: 130px;">
<p><strong><?php echo $total_total; ?></strong></p>
</td>
<td style="width: 237px;">
<p><strong><?php echo $total_remark; ?></strong></p>
</td>
</tr>
</tbody>
</table>
<p><strong>&nbsp;</strong></p>
<p><strong>3 a) First Surveillance Fee </strong>(To be paid Next Year)</p>
<table style="width: 800px; margin-left: auto; margin-right: auto; text-align: center;" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 244px;">
<p><strong>Service</strong></p>
</td>
<td style="width: 287px;">
<p><strong>Fee </strong></p>
</td>
<td style="width: 379px;">
<p><strong>Remark</strong></p>
</td>
</tr>
<tr>
<td style="width: 244px;">
<p><strong>1</strong><strong><sup>st</sup></strong><strong> Surveillance</strong></p>
</td>
<td style="width: 287px;">
<p><strong>Rs.<?php echo $fee_1st_Surveillance; ?></strong></p>
</td>
<td style="width: 379px;">
<p><?php echo $remark_1st_Surveillance; ?></p>
</td>
</tr>
</tbody>
</table>
<p><strong>b) Second Surveillance Fee </strong>(To be paid on Second Year)</p>
<table style="width: 800px; margin-left: auto; margin-right: auto; text-align: center;" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 245px;">
<p><strong>Service</strong></p>
</td>
<td style="width: 291px;">
<p><strong>Fee</strong></p>
</td>
<td style="width: 381px;">
<p><strong>Remark</strong></p>
</td>
</tr>
<tr>
<td style="width: 245px;">
<p><strong>2</strong><strong><sup>nd</sup></strong><strong> Surveillance</strong></p>
</td>
<td style="width: 291px;">
<p><strong>Rs.<?php echo $fee_2nd_Surveillance; ?></strong></p>
</td>
<td style="width: 381px;">
<p><?php echo $remark_2nd_Surveillance; ?></p>
</td>
</tr>
</tbody>
</table>
<p><strong><em>Terms and conditions:</em></strong><em> Beside the terms and conditions defined in the Certification Audit Contract the following applies:-</em></p>
<ol style="margin-left: 50px;"type="1">
<li>Any inadvertent cancellation of an audit within 7 days of the planned assessment will be invoiced as 50% of the cost of assessment.</li>
<li>This quotation expires in 3 months.</li>
<li>GST is applicable at actual (as per extent govt. norms)</li>
<li>Travel and accommodation will be at actual</li>
<li>Others</li>
</ol>
<table style="width: 800px; margin-left: auto; margin-right: auto; text-align: center;" border="1" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="width: 230px;">
<p>&nbsp;</p>
<p>(EAS)</p>
</td>
<td style="width: 287px;">
<p>&nbsp;</p>
</td>
<td style="width: 357px;">
<p><strong>&nbsp;</strong></p>
<p><strong>M/s: </strong></p>
</td>
</tr>
<tr>
<td style="width: 230px;">
<p>Authorized Signature</p>
</td>
<td style="width: 287px;">
<p>&nbsp;</p>
</td>
<td style="width: 357px;">
<p>Signature with stamp</p>
</td>
</tr>
<tr>
<td style="width: 230px;">
<p>Empowering Assurance Systems Pvt. Ltd.</p>
<p>1495, MANSAROVAR,16th Main Road,</p>
<p>Anna Nagar West,</p>
<p>Chennai - 600 040.</p>
</td>
<td style="width: 287px;">
<p>&nbsp;</p>
</td>
<td style="width: 357px;">
<p>&nbsp;</p>
<p>&nbsp;</p>
</td>
</tr>
</tbody>
</table>
<p><em>&nbsp;</em></p>
<div>
<p>&nbsp;</p>
<p>EAS signature / seal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Customer sign/seal</p>
</div>
</div>
