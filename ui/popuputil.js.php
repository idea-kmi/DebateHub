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
header('Content-Type: text/javascript;');
include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
?>

/**
 * Display the form hint for the given field type.
 * Returns true if the hint was found and displayed else false.
 */
function showFormHint(type, evt, panelName, extra) {

 	var event = evt || window.event;

	const resourcemessage = document.getElementById("resourceMessage");
	resourcemessage.innerHTML="";

	// Issue Forms
	if (type == "IssueSummary") {
		resourcemessage.innerHTML = '<?php echo $LNG->ISSUE_SUMMARY_FORM_HINT; ?>';
	} else if (type == "IssueDesc") {
		resourcemessage.innerHTML = '<?php echo $LNG->ISSUE_DESC_FORM_HINT; ?>';
 	} else if (type == "IssuePhoto") {
		resourcemessage.innerHTML = '<?php echo $LNG->ISSUE_PHOTO_FORM_HINT; ?>';
	} else if (type == "IssueDates") {
		resourcemessage.innerHTML = '<?php echo $LNG->FORM_LABEL_DEBATE_DATES_HINT; ?>';
 	} else if (type == "IssueDiscussionDates") {
		resourcemessage.innerHTML = '<?php echo $LNG->FORM_LABEL_DISCUSSION_DATES_HINT; ?>';
 	} else if (type == "IssueReducingDates") {
		resourcemessage.innerHTML = '<?php echo $LNG->FORM_LABEL_LEMONING_DATES_HINT; ?>';
 	} else if (type == "IssueVotingDates") {
		resourcemessage.innerHTML = '<?php echo $LNG->FORM_LABEL_VOTING_DATES_HINT; ?>';

	// Group Forms
 	} else if (type == "GroupSummary") {
		resourcemessage.innerHTML = '<?php echo $LNG->GROUP_NAME_FORM_HINT; ?>';
 	} else if (type == "GroupDesc") {
		resourcemessage.innerHTML = '<?php echo $LNG->GROUP_DESC_FORM_HINT; ?>';
 	} else if (type == "GroupWebsite") {
		resourcemessage.innerHTML = '<?php echo $LNG->GROUP_WEBSITE_FORM_HINT; ?>';
 	} else if (type == "GroupPhoto") {
		resourcemessage.innerHTML = '<?php echo $LNG->GROUP_PHOTO_FORM_HINT; ?>';

	} else {
		return false;
	}

	showHint(event, panelName, 10, -10);

	return true;
}

/**
 * Remove the given multiple for the given type at the given index
 */
function removeMultiple(key, i) {
	var answer = confirm("<?php echo $LNG->FORM_REMOVE_MULTI; ?>");
    if(answer){
		if (document.getElementById(key+'form') && document.getElementById(key+'field'+i)) {
		    if(	document.getElementById(key+'form').childElements()[0].nodeName.toUpperCase() != "HR"){
			    document.getElementById(key+'field'+i).remove();
			    try {
			        document.getElementById(key+'hr'+ i).remove();
			    } catch (err) {
			        // do nowt
			    }
			    if(document.getElementById(key+'form').childElements()[0] && document.getElementById(key+'form').childElements()[0].nodeName.toUpperCase() == "HR"){
			        document.getElementById(key+'form').childElements()[0].remove();
			    }
		    }
		}
    }
    return;
}

/**
 * Add another idea block - used in Split form.
 */
function addIdea(noIdeas) {

	var newitem = '<div id="ideafield'+noIdeas+'" class="formrow">';

	newitem += '<div class="formrowsm">';
	newitem += '<input placeholder="<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>" class="forminput hgrwide" id="ideaname-'+noIdeas+'" name="ideanamearray[]" value="">';
	newitem += '</div>';

	newitem += '<div class="formrowsm">';
	newitem += '<textarea rows="3" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>" class="forminput hgrwide" id="ideadesc-'+noIdeas+'" name="ideadescarray[]"></textarea>';
	newitem += '</div>';

	newitem += '<a id="idearemovebutton-'+noIdeas+'" href="javascript:void(0)" onclick="javascript:removeMultiple(\'idea\', \''+noIdeas+'\')" class="form" style="clear:both;float:right"><?php echo $LNG->FORM_BUTTON_REMOVE; ?></a><br>';
	newitem += '</div>';

	document.getElementById('ideaformdiv').innerHTML = newitem;

	noIdeas++;
	return noIdeas;
}

var initialising = false;

function initialisePhaseDates() {
	initialising = true;

	// Debate dates
	var time = document.getElementById('utcstarttime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		document.getElementById('startdate').value = fomatedDate;
	}

	if (time > 0) {
		var time = document.getElementById('utcendtime').value;
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		document.getElementById('enddate').value = fomatedDate;
	}

	if (document.getElementById('utcstarttime').value != 0 && document.getElementById('utcendtime').value != 0) {
		turnOnPhasing();
	}

	//Discussion Phase date
	var time = document.getElementById('utcdiscussionendtime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		document.getElementById('discussionend').value = fomatedDate;
	}

	//Lemoning Phase dates
	var time = document.getElementById('utclemoningstarttime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		document.getElementById('lemoningstart').value = fomatedDate;
	}

	if (time > 0) {
		var time = document.getElementById('utclemoningendtime').value;
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		document.getElementById('lemoningend').value = fomatedDate;
	}

	//Voting Phase dates
	var time = document.getElementById('utcvotingstarttime').value;
	if (time > 0) {
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		document.getElementById('votingstart').value = fomatedDate;
	}

	if (time > 0) {
		var time = document.getElementById('utcvotingendtime').value;
		var localDate = convertUTCTimeToLocalDate(time);
		var fomatedDate = localDate.format(DATE_FORMAT_PHASE);
		document.getElementById('votingend').value = fomatedDate;
	}

	initialising = false;
}

function issueStartDateChanged(obj) {

	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var issuestartdate = Date.parse($document.getElementById('startdate').value);
	var issueenddate = Date.parse(document.getElementById('enddate').value);
	var discussionenddate = Date.parse(document.getElementById('discussionend').value);
	var lemoningstartdate = Date.parse(document.getElementById('lemoningstart').value);
	var lemoningenddate = Date.parse(document.getElementById('lemoningend').value);
	var votingstartdate = Date.parse(document.getElementById('votingstart').value);

	if (
		(document.getElementById('startdate').value != "" && document.getElementById('enddate').value != ""
			&& issueenddate < issuestartdate)
	) {
		alert('<?php echo $LNG->FORM_ISSUE_START_END_DATE_ERROR; ?>');
		document.getElementById('startdate').value = "";
		document.getElementById('utcstarttime').value = 0;
		document.getElementById('startdate').style.background = 'LightYellow ';
		setTimeout((function() { document.getElementById('startdate').focus() }), 0);
	} else {
		var utctime = 0;
		if (document.getElementById('startdate').value.trim() == "") {
			document.getElementById('utcstarttime').value = 0;
		} else {
			var localtime = Date.parse(obj.value);
			var newDate = new Date(localtime);
			utctime = convertLocalDateToUTCTime(newDate);
			$document.getElementById('utcstarttime').value = utctime;
		}

		if (document.getElementById('startdate').value.trim() != "" && (document.getElementById('enddate').value).trim() != "") {
			turnOnPhasing();

			if (issuestartdate > discussionenddate) {
				document.getElementById('discussionend').value = "";
				document.getElementById('utcdiscussionendtime').value = 0;
			}
			if (issuestartdate > lemoningstartdate) {
				document.getElementById('lemoningstart').value = "";
				document.getElementById('utclemoningstarttime').value = 0;
			}
			if (issuestartdate > lemoningenddate) {
				document.getElementById('lemoningend').value = "";
				document.getElementById('utclemoningendtime').value = 0;
			}
			if (issuestartdate > votingstartdate) {
				document.getElementById('votingstart').value = "";
				document.getElementById('utcvotingstarttime').value = 0;
			}
		} else {
			turnOffPhasing();

			// highlight next field
			if (document.getElementById('enddate').value == "") {
				document.getElementById('enddate').style.background = 'LightYellow ';
				setTimeout((function() { document.getElementById('enddate').focus() }), 0);
			}
		}
	}
}

function issueEndDateChanged(obj) {
	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var issuestartdate = Date.parse(document.getElementById('startdate').value);
	var issueenddate = Date.parse(document.getElementById('enddate').value);
	var discussionenddate = Date.parse(document.getElementById('discussionend').value);
	var lemoningstartdate = Date.parse(document.getElementById('lemoningstart').value);
	var lemoningenddate = Date.parse(document.getElementById('lemoningend').value);
	var votingstartdate = Date.parse(document.getElementById('votingstart').value);
	if (
		($document.getElementById('startdate').value != "" && document.getElementById('enddate').value != ""
			&& issueenddate < issuestartdate)
	) {
		alert('<?php echo $LNG->FORM_ISSUE_START_END_DATE_ERROR; ?>');
		document.getElementById('enddate').value = "";
		document.getElementById('utcendtime').value = 0;
		document.getElementById('enddate').style.background = 'LightYellow ';
		setTimeout((function() { document.getElementById('enddate').focus() }), 0);
	} else {
		var utctime = 0;
		if (document.getElementById('enddate').value.trim() == "") {
			document.getElementById('utcendtime').value = 0;
		} else {
			var localtime = Date.parse(obj.value);
			var newDate = new Date(localtime);
			utctime = convertLocalDateToUTCTime(newDate);
			document.getElementById('utcendtime').value = utctime;
		}

		if (document.getElementById('startdate').value.trim() != "" && (document.getElementById('enddate').value).trim() != "") {
			turnOnPhasing();
			if (document.getElementById('votingon').checked) {
				document.getElementById('votingend').value = $document.getElementById('enddate').value;
				document.getElementById('utcvotingendtime').value = utctime;
			}
		} else {
			turnOffPhasing();
			if ( ($document.getElementById('startdate').value).trim() == "") {
				document.getElementById('startdate').style.background = 'LightYellow ';
				setTimeout((function() { document.getElementById('startdate').focus() }), 0);
			}
		}

		if (issueenddate < discussionenddate) {
			document.getElementById('discussionend').value = "";
			document.getElementById('utcdiscussionendtime').value = 0;
		}
		if (issueenddate < lemoningstartdate) {
			document.getElementById('lemoningstart').value = "";
			document.getElementById('utclemoningstarttime').value = 0;
		}
		if (issueenddate < lemoningenddate) {
			document.getElementById('lemoningend').value = "";
			document.getElementById('utclemoningendtime').value = 0;
		}
		if (issueenddate < votingstartdate) {
			document.getElementById('votingstart').value = "";
			document.getElementById('utcvotingstarttime').value = 0;
		}
	}
}

function discussionEndDateChanged(obj) {
	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var startdate = Date.parse(document.getElementById('startdate').value);
	var discussionenddate = Date.parse(document.getElementById('discussionend').value);
	var issueenddate = Date.parse(document.getElementById('enddate').value);
	var lemoningenddate = Date.parse(document.getElementById('lemoningend').value);
	if (
		(document.getElementById('discussionend').value != "" && discussionenddate <= startdate) ||
		(document.getElementById('enddate').value != "" && discussionenddate > issueenddate)
	) {
		alert('<?php echo $LNG->FORM_ISSUE_DISCUSSION_END_DATE_ERROR; ?>');
		document.getElementById('discussionend').value = "";
		document.getElementById('utcdiscussionendtime').value = 0;
		document.getElementById('discussionend').style.background = 'LightYellow ';
		setTimeout((function() { document.getElementById('discussionend').focus() }), 0);
	} else {
		var localtime = Date.parse(obj.value);
		var newDate = new Date(localtime);
		var utctime = convertLocalDateToUTCTime(newDate);
		document.getElementById('utcdiscussionendtime').value = utctime;

		if (document.getElementById('lemoningon').checked) {
			document.getElementById('lemoningstart').value = document.getElementById('discussionend').value;
			document.getElementById('utclemoningstarttime').value = document.getElementById('utcdiscussionendtime').value;

			if (lemoningenddate <= discussionenddate) {
				document.getElementById('lemoningend').value = "";
				document.getElementById('utclemoningendtime').value = 0;
			}

			// highlight next field if empty
			if (document.getElementById('lemoningend').value == ""){
				document.getElementById('lemoningend').style.background = 'LightYellow ';
				setTimeout((function() { document.getElementById('lemoningend').focus() }), 0);
			}
		} else if (document.getElementById('votingon').checked) {
			document.getElementById('votingstart').value = document.getElementById('discussionend').value;
			document.getElementById('utcvotingstarttime').value = document.getElementById('utcdiscussionendtime').value;
		}
	}
}

function lemoningEndDateChanged(obj) {
	if (initialising) {return;}

	clearIssuePhaseBackgrounds();

	var lemonstartdate = Date.parse(document.getElementById('lemoningstart').value);
	var lemonenddate = Date.parse(document.getElementById('lemoningend').value);
	var discussionenddate = Date.parse(document.getElementById('discussionend').value);
	var issueenddate = Date.parse(document.getElementById('enddate').value);
	var votingstartdate = Date.parse(document.getElementById('votingstart').value);

	if (document.getElementById('lemoningstart').value != "" && document.getElementById('lemoningend').value != "" && lemonenddate <= lemonstartdate ) ||
		(document.getElementById('enddate').value != "" && document.getElementById('discussionend').value != "" && lemonenddate <= discussionenddate) ||
		(document.getElementById('enddate').value != "" && document.getElementById('lemoningend').value != "" && lemonenddate > issueenddate)
		) {
		alert('<?php echo $LNG->FORM_ISSUE_LEMONING_END_DATE_ERROR; ?>');
		document.getElementById('lemoningend').value = "";
		document.getElementById('utclemoningendtime').value = 0;
		document.getElementById('lemoningend').style.background = 'LightYellow ';
		setTimeout((function() { document.getElementById('lemoningend').focus() }), 0);
	} else {
		var localtime = Date.parse(obj.value);
		var newDate = new Date(localtime);
		var utctime = convertLocalDateToUTCTime(newDate);
		document.getElementById('utclemoningendtime').value = utctime;

		if (document.getElementById('votingon').checked) {
			document.getElementById('votingstart').value = document.getElementById('lemoningend').value;
			document.getElementById('utcvotingstarttime').value = document.getElementById('utclemoningendtime').value;
		}
	}
}

function lemoningCheckedbox() {
	clearIssuePhaseBackgrounds();
	if (document.getElementById('lemoningon').checked) {
		document.getElementById('lemoningstart').disabled = false;
		document.getElementById('lemoningend').disabled = false;

		if (document.getElementById('discussionend').value != "" && document.getElementById('discussionend').value != document.getElementById('enddate').value) {
			document.getElementById('lemoningstart').value = document.getElementById('discussionend').value;
			document.getElementById('utclemoningstarttime').value = document.getElementById('utcdiscussionendtime').value;
		} else {
			document.getElementById('discussionend').value = "";
			document.getElementById('utcdiscussionendtime').value = 0;
			document.getElementById('discussionend').style.background = 'LightYellow ';
			setTimeout((function() { document.getElementById('discussionend').focus() }), 0);
		}
	} else {
		document.getElementById('lemoningstart').value = "";
		document.getElementById('lemoningend').value = "";
		document.getElementById('utclemoningstarttime').value = 0;
		document.getElementById('utclemoningendtime').value = 0;
		document.getElementById('lemoningstart').style.background = null;
		document.getElementById('lemoningend').style.background = null;
		document.getElementById('lemoningstart').disabled = true;
		document.getElementById('lemoningend').disabled = true;

		if (document.getElementById('votingon').checked) {
			document.getElementById('votingstart').value = document.getElementById('discussionend').value;
			document.getElementById('utcvotingstarttime').value = document.getElementById('utcdiscussionendtime').value;
		}
	}
}

function votingCheckedbox() {
	clearIssuePhaseBackgrounds();

	if (document.getElementById('votingon').checked) {
		document.getElementById('votingend').value = document.getElementById('enddate').value;
		document.getElementById('utcvotingendtime').value = document.getElementById('utcendtime').value;
		if (document.getElementById('lemoningon').checked && document.getElementById('lemoningend').value != "") {
			document.getElementById('votingstart').value = document.getElementById('lemoningend').value;
			document.getElementById('utcvotingstarttime').value = document.getElementById('utclemoningendtime').value;
		} else if (document.getElementById('discussionend').value != "") {
			document.getElementById('votingstart').value = document.getElementById('discussionend').value;
			document.getElementById('utcvotingstarttime').value = document.getElementById('utcdiscussionendtime').value;
		}
	} else {
		document.getElementById('votingstart').value = "";
		document.getElementById('votingend').value = "";
		document.getElementById('utcvotingstarttime').value = 0;
		document.getElementById('utcvotingendtime').value = 0;
	}
}

function clearIssuePhaseBackgrounds() {
	document.getElementById('startdate').style.background = 'white';
	document.getElementById('enddate').style.background = 'white';
	if (document.getElementById('discussionend').disabled == false) {
		document.getElementById('discussionend').style.background = 'white';
	}
	if (document.getElementById('lemoningend').disabled == false) {
		document.getElementById('lemoningend').style.background = 'white';
	}
}

function showCalendar(url, field, format, type, time, hours) {
	if (document.getElementById('utcendtime').value != 0 && document.getElementById('utcstarttime').value != 0) {
		NewCssCal(url, field, format, type, time, hours);
	}
}

function turnOnPhasing() {
	document.getElementById('discussionend').disabled = false;
	document.getElementById('lemoningon').disabled = false;
	document.getElementById('votingon').disabled = false;
 	return;
}

function turnOffPhasing() {
	document.getElementById('discussionend').disabled = true;

	document.getElementById('discussionend').value = "";
	document.getElementById('utcdiscussionendtime').value = 0;

	// clear and turn off lemongin
	document.getElementById('lemoningon').checked = false;
	document.getElementById('lemoningon').disabled = true;
	document.getElementById('lemoningend').disabled = true;

	document.getElementById('lemoningstart').value = "";
	document.getElementById('lemoningend').value = "";
	document.getElementById('utclemoningstarttime').value = 0;
	document.getElementById('utclemoningendtime').value = 0;

	// clear and turn off voting phase
	document.getElementById('votingon').checked = false;
	document.getElementById('votingon').disabled = true;

	document.getElementById('votingstart').value = "";
	document.getElementById('votingend').value = "";
	document.getElementById('utcvotingstarttime').value = 0;
	document.getElementById('utcvotingendtime').value = 0;
}