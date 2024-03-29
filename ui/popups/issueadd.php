<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2024 The Open University UK                            *
 *                                                                              *
 *  This software is freely distributed in accordance with                      *
 *  the GNU Lesser General Public (LGPL) license, version 3 or later            *
 *  as published by the Free Software Foundation.                               *
 *  For details see LGPL: http://www.fsf.org/licensing/licenses/lgpl.html       *
 *               and GPL: http://www.fsf.org/licensing/licenses/gpl-3.0.html    *
 *                                                                              *
 *  This software is provided by the copyright holders and contributors "as is" *
 *  and any express or implied warranties, including, but not limited to, the   *
 *  implied warranties of merchantability and fitness for a particular purpose  *
 *  are disclaimed. In no event shall the copyright owner or contributors be    *
 *  liable for any direct, indirect, incidental, special, exemplary, or         *
 *  consequential damages (including, but not limited to, procurement of        *
 *  substitute goods or services; loss of use, data, or profits; or business    *
 *  interruption) however caused and on any theory of liability, whether in     *
 *  contract, strict liability, or tort (including negligence or otherwise)     *
 *  arising in any way out of the use of this software, even if advised of the  *
 *  possibility of such damage.                                                 *
 *                                                                              *
 ********************************************************************************/

    include_once("../../config.php");

    $me = substr($_SERVER["PHP_SELF"], 1); // remove initial '/'
    if ($HUB_FLM->hasCustomVersion($me)) {
    	$path = $HUB_FLM->getCodeDirPath($me);
    	include_once($path);
		die;
	}

    checkLogin();

    include_once($HUB_FLM->getCodeDirPath("ui/headerdialog.php"));

    $errors = array();

	$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);
	$issue = optional_param("issue","",PARAM_TEXT);
	$desc = optional_param("desc","",PARAM_HTML);

    $sdt = trim(optional_param("startdate","",PARAM_TEXT));
    $edt = trim(optional_param("enddate","",PARAM_TEXT));
    $utcstarttime = trim(optional_param("utcstarttime",0,PARAM_INT));
    $utcendtime = trim(optional_param("utcendtime",0,PARAM_INT));

	$discussionend = trim(optional_param("discussionend","",PARAM_TEXT));
    $utcdiscussionstarttime = trim(optional_param("utcdiscussionstarttime",0,PARAM_INT));
    $utcdiscussionendtime = trim(optional_param("utcdiscussionendtime",0,PARAM_INT));

	$lemoningon = trim(optional_param("lemoningon","N",PARAM_ALPHA));
    $lemoningstart = trim(optional_param("lemoningstart","",PARAM_TEXT));
    $lemoningend = trim(optional_param("lemoningend","",PARAM_TEXT));
    $utclemoningstarttime = trim(optional_param("utclemoningstarttime",0,PARAM_INT));
    $utclemoningendtime = trim(optional_param("utclemoningendtime",0,PARAM_INT));

	$votingon = trim(optional_param("votingon","N",PARAM_ALPHA));
    $votingstart = trim(optional_param("votingstart","",PARAM_TEXT));
    $votingend = trim(optional_param("votingend","",PARAM_TEXT));
    $utcvotingstarttime = trim(optional_param("utcvotingstarttime",0,PARAM_INT));
    $utcvotingendtime = trim(optional_param("utcvotingendtime",0,PARAM_INT));

    if( isset($_POST["addissue"]) ) {

        if ($issue == ""){
            array_push($errors, $LNG->FORM_ISSUE_ENTER_SUMMARY_ERROR);
        }
		if ($sdt != "" && $edt != "") {
			$edate = strtotime($edt);
			$sdate = strtotime($sdt);
			if ($sdate >= $edate) {
            	array_push($errors, $LNG->FORM_ISSUE_START_END_DATE_ERROR);
			}

			if ($votingstart != "") {
				$vdate = strtotime($votingstart);
				if ($vdate > $edate || $vdate < $sdate) {
	            	array_push($errors, $LNG->FORM_ISSUE_VOTE_START_DATE_ERROR);
				}
			}
		}

        if(empty($errors)){

			// GET ROLES AND LINKS AS USER
			$r = getRoleByName("Issue");
			$roleIssue = $r->roleid;

			// CREATE THE ISSUE NODE
			$issuenode = addNode($issue, $desc, 'N', $roleIssue);

			if (!$issuenode instanceof Hub_Error) {
				// ISSUE DATES
		        $issuenode->updateStartDate($utcstarttime);
		        $issuenode->updateEndDate($utcendtime);

				// DISCUSSION
				if ($utcdiscussionendtime != 0) {
		        	$issuenode->updateNodeProperty('discussionstart', $utcstarttime);
		        }
		        $issuenode->updateNodeProperty('discussionend', $utcdiscussionendtime);

				// LEMONING
		        $issuenode->updateNodeProperty('lemoningon', $lemoningon);
		        $issuenode->updateNodeProperty('lemoningstart', $utclemoningstarttime);
		        $issuenode->updateNodeProperty('lemoningend', $utclemoningendtime);

				// VOTING
		        $issuenode->updateNodeProperty('votingon', $votingon);
		        $issuenode->updateNodeProperty('votingstart', $utcvotingstarttime);
		        $issuenode->updateNodeProperty('votingend', $utcvotingendtime);

			    if ($_FILES['image']['error'] == 0) {
					$imagedir = $HUB_FLM->getUploadsNodeDir($issuenode->nodeid);

					$photofilename = uploadImageToFit('image',$errors,$imagedir);
					if($photofilename == ""){
						$photofilename = $CFG->DEFAULT_ISSUE_PHOTO;
					}
					$issuenode->updateImage($photofilename);
				}

				if (isset($groupid) && $groupid != "") {
					$issuenode->addGroup($groupid);
				}

				echo '<script type=\'text/javascript\'>';
				echo "window.opener.location.reload(true);";
				echo "window.close();";
				echo '</script>';

				//include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
				die;
 			} else {
  	           array_push($errors, $LNG->FORM_ISSUE_CREATE_ERROR_MESSAGE." ".$issuenode->message);
			}
		}
    }

	include_once($HUB_FLM->getCodeDirPath("ui/popuplib.php"));

    /**********************************************************************************/
?>


<script type="text/javascript">
	function init() {
		$('dialogheader').insert('<?php echo $LNG->FORM_ISSUE_TITLE_ADD; ?>');
		initialisePhaseDates();
	}

	function checkForm() {
		var checkname = ($('issue').value).trim();
		if (checkname == ""){
		alert("<?php echo $LNG->FORM_ISSUE_ENTER_SUMMARY_ERROR; ?>");
		return false;
		}

		//check dates
		var startdate = ($('startdate').value).trim();
		var enddate = ($('enddate').value).trim();
		var votingstart = ($('votingstart').value).trim();

		if (startdate != "" && enddate != "") {
			var sdate = Date.parse(startdate);
			var edate = Date.parse(enddate);

			if (sdate >= edate) {
				alert("<?php echo $LNG->FORM_ISSUE_START_END_DATE_ERROR; ?>");
				return false;
			}

			if (votingstart != "") {
				var vdate = Date.parse(votingstart);
				if (vdate > edate || vdate < sdate) {
					alert("<?php echo $LNG->FORM_ISSUE_VOTE_START_DATE_ERROR; ?>");
					return false;
				}
			}
		}

		$('issueform').style.cursor = 'wait';
		return true;
	}
	window.onload = init;
</script>

<div class="container-fluid popups">
	<div class="row p-4 justify-content-center">	
		<div class="col">
			<?php
				if(!empty($errors)){ ?>
					<div class="alert alert-info">
						<?php echo $LNG->FORM_ERROR_MESSAGE; ?>
						<ul>
							<?php
								foreach ($errors as $error){
									echo "<li>".$error."</li>";
								}
							?>
						</ul>
					</div>
			<?php } ?>
			<?php insertFormHeaderMessage(); ?>

			<form id="issueform" name="issueform" action="" enctype="multipart/form-data" method="post" onsubmit="return checkForm();">
				<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">

				<div class="mb-3 row">
					<label for="image" class="col-sm-3 col-form-label">
						<?php echo $LNG->DEBATE_IMAGE_LABEL; ?>
						<a class="active" onMouseOver="showFormHint('IssuePhoto', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
							<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
							<span class="sr-only">More info</span>
						</a>
						<span class="required">*</span>
					</label>
					<div class="col-sm-9">
						<input type="file" class="form-control" id="image" name="image" />
						<small><?php echo $LNG->GROUP_FORM_PHOTO_HELP; ?></small>
					</div>
				</div>				
				<div class="mb-3 row">
					<label for="issue" class="col-sm-3 col-form-label">
						<?php echo $LNG->FORM_ISSUE_LABEL_SUMMARY; ?>
						<a class="active" onMouseOver="showFormHint('IssueSummary', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)">
							<i class="far fa-question-circle fa-lg me-2" aria-hidden="true" ></i> 
							<span class="sr-only">More info</span>
						</a>
					</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="issue" name="issue" value="<?php echo( $issue ); ?>" />
					</div>
				</div>

				<?php insertDescription('IssueDesc'); ?>
				<?php insertIssuePhases(); ?>

				<div class="d-grid gap-2 d-md-flex justify-content-md-center mb-3">
					<input class="btn btn-secondary" type="button" value="<?php echo $LNG->FORM_BUTTON_CANCEL; ?>" onclick="window.close();"/>
					<input class="btn btn-primary" type="submit" value="<?php echo $LNG->FORM_BUTTON_SAVE; ?>" id="addissue" name="addissue">
				</div>
			</form>
		</div>
	</div>
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
?>