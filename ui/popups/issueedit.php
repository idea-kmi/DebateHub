<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 The Open University UK                                   *
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

 	$nodeid = required_param("nodeid",PARAM_ALPHANUMEXT);
	$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);

	$handler = optional_param("handler","", PARAM_TEXT);
	//convert any possible brackets
	$handler = parseToJSON($handler);

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

    if( isset($_POST["editissue"]) ) {
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

			if ($votingend != "") {
				$vdate = strtotime($votingend);
				if ($vdate > $edate || $vdate < $sdate) {
	            	array_push($errors, $LNG->FORM_ISSUE_VOTE_END_DATE_ERROR);
				}
			}

		}

        if(empty($errors)){
			$issuenode = getNode($nodeid);
			if (!$issuenode instanceof Error) {
				$filename = "";
				if (isset($issuenode->filename)) {
					$filename = $issuenode->filename;
				}
				if ($issue != $issuenode->name || $desc != $issuenode->description) {
					$r = getRoleByName("Issue");
					$roleIssue = $r->roleid;
					$issuenode = $issuenode->edit($issue, $desc, 'N', $roleIssue, $filename, '');
				}

				// ISSUE DATES
				if ((isset($issuenode->startdatetime) && $issuenode->startdatetime != $utcstarttime)
						|| !isset($issuenode->startdatetime)){
		        	$issuenode->updateStartDate($utcstarttime);
		        }
		        if ((isset($issuenode->enddatetime) && $issuenode->enddatetime != $utcendtime)
		        	|| !isset($issuenode->enddatetime)) {
			        $issuenode->updateEndDate($utcendtime);
				}

				// DISCUSSION
				if ($utcdiscussionendtime != 0) {
					if ($issuenode->getNodeProperty('discussionstart') != $utcstarttime) {
		        		$issuenode->updateNodeProperty('discussionstart', $utcstarttime);
		        	}
		        } else {
					if ($issuenode->getNodeProperty('discussionstart') != 0) {
			        	$issuenode->updateNodeProperty('discussionstart', 0);
			        }
		        }

				if ($issuenode->getNodeProperty('discussionend') != $utcdiscussionendtime) {
			        $issuenode->updateNodeProperty('discussionend', $utcdiscussionendtime);
			    }

				// LEMONING
				if ($issuenode->getNodeProperty('lemoningon') != $lemoningon) {
			        $issuenode->updateNodeProperty('lemoningon', $lemoningon);
			    }
				if ($issuenode->getNodeProperty('lemoningstart') != $utclemoningstarttime) {
			        $issuenode->updateNodeProperty('lemoningstart', $utclemoningstarttime);
			    }
				if ($issuenode->getNodeProperty('lemoningend') != $utclemoningendtime) {
			        $issuenode->updateNodeProperty('lemoningend', $utclemoningendtime);
			    }

				// VOTING
				if ($issuenode->getNodeProperty('votingon') != $votingon) {
			        $issuenode->updateNodeProperty('votingon', $votingon);
			    }
				if ($issuenode->getNodeProperty('votingstart') != $utcvotingstarttime) {
			        $issuenode->updateNodeProperty('votingstart', $utcvotingstarttime);
			    }
				if ($issuenode->getNodeProperty('votingend') != $utcvotingendtime) {
			        $issuenode->updateNodeProperty('votingend', $utcvotingendtime);
			    }

			    if ($_FILES['image']['error'] == 0) {
					$imagedir = $HUB_FLM->getUploadsNodeDir($issuenode->nodeid);

					$photofilename = uploadImageToFit('image',$errors,$imagedir);
					if($photofilename == ""){
						$photofilename = $CFG->DEFAULT_ISSUE_PHOTO;
					}
					$issuenode->updateImage($photofilename);
				}
			} else {
				error_log("ERROR");
			}

			echo "<script type='text/javascript'>";
			echo "try { ";
				echo 'window.opener.location.reload(true);';
			echo "}";
			echo "catch(err) {";
			echo "}";

			echo "window.close();";
			echo "</script>";

			include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
			die;
        }
    } else if ($nodeid != "") {
    	$node = new CNode($nodeid);
    	$node = $node->load();
		$issue = $node->name;
		$desc = $node->description;

        $utcstarttime = 0;
        if (isset($node->startdatetime)) {
        	$utcstarttime = $node->startdatetime;
        }
        $utcendtime = 0;
        if (isset($node->enddatetime)) {
        	$utcendtime = $node->enddatetime;
        }

	    $utcdiscussionstarttime = 0;
		if (isset($node->properties['discussionstart']) && $node->properties['discussionstart'] != "") {
        	$utcdiscussionstarttime = doubleval( $node->properties['discussionstart'] );
        }

	    $utcdiscussionendtime = 0;
		if (isset($node->properties['discussionend']) && $node->properties['discussionend'] != "") {
        	$utcdiscussionendtime = doubleval( $node->properties['discussionend'] );
        }

	    $lemoningon = 'N';
		if (isset($node->properties['lemoningon'])) {
        	$lemoningon = $node->properties['lemoningon'];
        }

	    $utclemoningstarttime = 0;
		if (isset($node->properties['lemoningstart']) && $node->properties['lemoningstart'] != "") {
	        $utclemoningstarttime = doubleval( $node->properties['lemoningstart'] );
	    }
	    $utclemoningendtime = 0;
		if (isset($node->properties['lemoningend']) && $node->properties['lemoningend'] != "") {
	        $utclemoningendtime = doubleval( $node->properties['lemoningend'] );
	    }

	    $votingon = 'N';
		if (isset($node->properties['votingon'])) {
        	$votingon = $node->properties['votingon'];
        }

	    $utcvotingstarttime = 0;
		if (isset($node->properties['votingstart']) && $node->properties['votingstart'] != "") {
	        $utcvotingstarttime = doubleval( $node->properties['votingstart'] );
	    }
	    $utcvotingendtime = 0;
		if (isset($node->properties['votingend']) && $node->properties['votingend'] != "") {
        	$utcvotingendtime = doubleval( $node->properties['votingend'] );
		}
    } else {
		echo "<script type='text/javascript'>";
		echo "alert('".$LNG->FORM_ISSUE_NOT_FOUND."');";
		echo "window.close();";
		echo "</script>";
		die;
    }

    /**********************************************************************************/
?>

<?php
if(!empty($errors)){
    echo "<div class='errors'>".$LNG->FORM_ERROR_MESSAGE.":<ul>";
    foreach ($errors as $error){
        echo "<li>".$error."</li>";
    }
    echo "</ul></div>";
}
?>

<script type="text/javascript">

function init() {
	$('dialogheader').insert('<?php echo $LNG->FORM_ISSUE_TITLE_EDIT; ?>');
   	initialisePhaseDates();
}

function checkForm() {
	var checkname = ($('issue').value).trim();
	if (checkname == ""){
	   alert("<?php echo $LNG->FORM_ISSUE_ENTER_SUMMARY_ERROR; ?>");
	   return false;
    }

    $('issueform').style.cursor = 'wait';

    return true;
}

window.onload = init;

</script>

<?php
include("../popuplib.php");
?>

<form id="issueform" name="issueform" action="" enctype="multipart/form-data" method="post" onsubmit="return checkForm();">
	<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>" />
	<input type="hidden" id="handler" name="handler" value="<?php echo $handler; ?>" />
	<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">

    <div class="hgrformrow">
		<label class="formlabelbig"><?php echo $LNG->DEBATE_IMAGE_LABEL; ?></label>
		<div style="margin-left:5px;padding:0px;position:relative;overflow:hidden;border:1px solid gray;width:150px;height:100;max-width:150px;max-height:100px;min-width:150px;min-height:100px;">
			<img style="position:absolute; top:0px left:0px;cursor:move;" id="dragableElement" border="0" src="<?php print $node->image; ?>"/>
		</div>
    </div>
    <div class="hgrformrow">
		<label class="formlabelbig" for="image"><?php echo $LNG->PROFILE_PHOTO_REPLACE_LABEL; ?></label>
		<input class="forminput" type="file" id="image" name="image" size="40">
    </div>
	<div class="formrow">
		<label class="formlabelbig">&nbsp;</label>
		<span class="forminput"><?php echo $LNG->GROUP_FORM_PHOTO_HELP; ?></span>
	</div>

    <div class="hgrformrow">
		<label  class="formlabelbig" for="url"><span style="vertical-align:top"><?php echo $LNG->FORM_ISSUE_LABEL_SUMMARY; ?></span>
			<span class="active" onMouseOver="showFormHint('IssueSummary', event, 'hgrhint'); return false;" onMouseOut="hideHints(); return false;" onClick="hideHints(); return false;" onkeypress="enterKeyPressed(event)"><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="margin-top: 2px; margin-left: 5px; margin-right: 2px;" /></span>
		</label>
		<input class="forminputmust hgrinput hgrwide" id="issue" name="issue" value="<?php echo $issue; ?>" />
	</div>

	<?php insertDescription('IssueDesc'); ?>

	<?php insertIssuePhases(); ?>

    <br>
    <div class="hgrformrow">
        <input class="submit" type="submit" value="<?php echo $LNG->FORM_BUTTON_SAVE; ?>" id="editissue" name="editissue">
        <input type="button" value="<?php echo $LNG->FORM_BUTTON_CANCEL; ?>" onclick="window.close();"/>
    </div>
</form>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
?>