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

global $HUB_FLM;

$ref = "http" . ((!empty($_SERVER["HTTPS"])) ? "s" : "") . "://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

?>

<script type='text/javascript'>
var rightTimers = {};
var leftTimers = {};
var timerStep = 3;
var timerDuration = 16;

function scrollDivLeft(divname, leftbuttonname, rightbuttonname, barname) {
	timerLeft = setInterval(function() {
	  	if ($(divname).scrollLeft == 0) {
	  		$(leftbuttonname).src = "<?php echo $HUB_FLM->getImagePath('back-arrow-grey.png'); ?>";
			stopScrollLeft(divname);
	  	} else {
			$(divname).scrollLeft -= timerStep;
			if ($(divname).scrollLeft < ($(barname).offsetWidth-$(divname).offsetWidth)) {
				$(rightbuttonname).src = "<?php echo $HUB_FLM->getImagePath('forward-arrow.png'); ?>";
			}
		}
	},
	timerDuration);
	leftTimers[divname] = timerLeft;
}

function scrollDivRight(divname, leftbuttonname, rightbuttonname, barname) {
	timerRight = setInterval(function() {
		if ($(divname).scrollLeft >= ($(barname).offsetWidth-$(divname).offsetWidth)) {
			$(rightbuttonname).src = "<?php echo $HUB_FLM->getImagePath('forward-arrow-grey.png'); ?>";
			stopScrollRight(divname);
		} else {
			$(divname).scrollLeft += timerStep;
			if ($(divname).scrollLeft > 0) {
				$(leftbuttonname).src = "<?php echo $HUB_FLM->getImagePath('back-arrow.png'); ?>";
			}
		}
	}, timerDuration);
	rightTimers[divname] = timerRight;
}

function stopScrollLeft(divname) {
	var timerLeft = leftTimers[divname];
	if (timerLeft) {
		clearInterval(timerLeft);
	}
}

function stopScrollRight(divname) {
	var timerRight = rightTimers[divname];
	if (timerRight) {
		clearInterval(timerRight);
	}
}

Event.observe(window, 'load', function() {
	if (USER && USER != "") {
		loadmygroups();
		loadmyissues();
	} else {
		loadrecentgroups();
		loadrecentissues();
	}
});

/**
 *	load my groups
 */
function loadmygroups(){

	var container = $('mygroupsBar');
	container.update(getLoading("<?php echo $LNG->LOADING_GROUPS; ?>"));
	var reqUrl = SERVICE_ROOT + "&method=getmygroups&start=0&max=-1&orderby=date&sort=DESC";

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
				loadrecentgroups();
				return;
			}
			if(json.error){
				console.log(json.error[0].message);
				loadrecentgroups();
				return;
			}

			container.update("");
			if(json.groupset[0].groups.length > 0){
				container.style.width = (json.groupset[0].groups.length*450)+'px';
				displayGroups(container,json.groupset[0].groups,0, 428,180, false, true);
				$("tab-content-home-recent-mygroup-div").style.display = 'block';
			} else {
				loadrecentgroups();
			}
		}
	});
}

/**
 *	load my issues
 */
function loadmyissues(){

	var container = $('myissuesBar');
	container.update(getLoading("<?php echo $LNG->LOADING_ISSUES; ?>"));
	var reqUrl = SERVICE_ROOT + "&method=getnodesbyuser&start=0&max=-1&orderby=date&sort=DESC&filternodetypes=Issue&userid="+USER;

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
				loadrecentissues();
				return;
			}
			if(json.error){
				console.log(json.error[0].message);
				loadrecentissues();
				return;
			}

			//display nodes
			container.update("");
			if(json.nodeset[0].nodes.length > 0){
				container.style.width = (json.nodeset[0].nodes.length*450)+'px';
				displayIssueNodes(428, 160, container,json.nodeset[0].nodes,0, false,'recentissues','inactive', false, false, true);
				$("tab-content-home-recent-mydebate-div").style.display = 'block';
			} else {
				loadrecentissues();
			}
		}
	});
}

/**
 *	load latest groups
 */
function loadrecentgroups(){

	var container = $('groupsBar');
	container.update(getLoading("<?php echo $LNG->LOADING_GROUPS; ?>"));
	var reqUrl = SERVICE_ROOT + "&method=getgroupsbyglobal&start=0&max=4&orderby=date&sort=DESC";

	$("tab-content-home-recent-group-div").style.display = 'block';

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
			}
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			container.update("");
			if(json.groupset[0].groups.length > 0){
				//alert((json.groupset[0].groups.length*450));
				container.style.width = (json.groupset[0].groups.length*450)+'px';
				displayGroups(container,json.groupset[0].groups,0, 428,180, false, true);
			}
		}
	});
}

/**
 *	load latest issues
 */
function loadrecentissues(){

	var container = $('issuesBar');
	container.update(getLoading("<?php echo $LNG->LOADING_ISSUES; ?>"));
	var reqUrl = SERVICE_ROOT + "&method=getnodesbyglobal&start=0&max=4&orderby=date&sort=DESC&filternodetypes=Issue";

	$("tab-content-home-recent-debate-div").style.display = 'block';

	new Ajax.Request(reqUrl, { method:'get',
		onSuccess: function(transport){
			try {
				var json = transport.responseText.evalJSON();
			} catch(err) {
				console.log(err);
			}
			if(json.error){
				alert(json.error[0].message);
				return;
			}

			//display nodes
			container.update("");
			if(json.nodeset[0].nodes.length > 0){
				container.style.width = (json.nodeset[0].nodes.length*450)+'px';
				displayIssueNodes(428, 160, container,json.nodeset[0].nodes,0, false,'recentissues','inactive', false, false, true);
			}
		}
	});
}
</script>

<div style="float:left;height:100%; width:100%;">
	<div style="float:left;width: 99%;font-weight:normal;margin-top:0px;">
		<div style="float:left;">
			<div style="float:left;font-size:11pt;">
				<h1><?php echo $LNG->HOMEPAGE_TITLE; ?></h1>
				<p><?php echo $LNG->HOMEPAGE_FIRST_PARA; ?><br>
				<span id="homeintrobutton" class="active" style="font-weight:normal;text-decoration:underline" onclick="if ($('homeintromore').style.display == 'none') { $('homeintromore').style.display = 'block'; $('homeintrobutton').innerHTML = '<?php echo $LNG->HOMEPAGE_READ_LESS; ?>'; } else { $('homeintromore').style.display = 'none';  $('homeintrobutton').innerHTML = '<?php echo $LNG->HOMEPAGE_KEEP_READING; ?>';}"><?php echo $LNG->HOMEPAGE_KEEP_READING; ?></span>
				</p>

				<div id="homeintromore" style="float:left;clear:both;width:100%;display:none;margin:0px;padding:0px">
					<?php echo $LNG->HOMEPAGE_SECOND_PARA_PART2; ?>
				</div>
			</div>
		</div>

		<!-- LOAD MY STUFF ID LOGGED IN -->

		<!-- MY GROUPS BAR AREA -->
		<div id="tab-content-home-recent-mygroup-div" style="clear:both;float:left;width:100%;padding-bottom:10px;display:none">
			<h2 style="font-size:14pt;padding:5px;padding-right:0px;background: linear-gradient(to bottom, #FFFFFF, #E8E8E8) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100%;height:25px"><?php echo $LNG->HOME_MY_GROUPS_TITLE; ?>
				<a style="margin-left:10px;font-size:10pt" href="user.php?userid=<?php echo $USER->userid; ?>#group"><?php echo $LNG->HOME_MY_GROUPS_AREA_LINK; ?></a>

				<?php if ($CFG->groupCreationPublic || (!$CFG->groupCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>
					<span style="margin-left:20px;font-size:10pt">(
						<?php if (isset($USER->userid)) { ?>
							<span class="active" onclick="javascript:loadDialog('creategroup','<?php print($CFG->homeAddress);?>ui/popups/groupadd.php', 720, 700);"><?php echo $LNG->GROUP_CREATE_TITLE; ?></span>
						<?php } else {
							if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
								echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registeropen.php">'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'</a> '.$LNG->GROUP_CREATE_LOGGED_OUT_OPEN;
							} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
								echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">'.$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT.'</a> '.$LNG->GROUP_CREATE_LOGGED_OUT_REQUEST;
							} else {
								echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> '.$LNG->GROUP_CREATE_LOGGED_OUT_CLOSED;
							}
						} ?>
					)</span>

				<?php } ?>
			</h2>

			<div id="mygroupsbarcontent" style="padding:0px; margin:0px;float:left;clear:both;margin-top:3px;width:100%">
				<div style="clear:both;float:left;height:200px">
					<img style="margin-top:80px;padding-right:5px;" id="mygroupsBarLeftButton" src="<?php echo $HUB_FLM->getImagePath('back-arrow-grey.png'); ?>" onmouseover="scrollDivLeft('mygroupsBarDiv', 'mygroupsBarLeftButton', 'mygroupsBarRightButton', 'mygroupsBar')" onmouseout="stopScrollLeft('mygroupsBarDiv')" />
				</div>

				<div id="mygroupsBarDiv" class="plainborder bodyback" style="width:900px;overflow-x:hidden;overflow-y:hidden;height:200px;float:left;">
					<div id="mygroupsBar" style="float:left;"></div>
				</div>

				<div style="float:left;height:200px">
					<img style="margin-top:80px;padding-left:5px;" id="mygroupsBarRightButton" src="<?php echo $HUB_FLM->getImagePath('forward-arrow.png'); ?>" onmouseover="scrollDivRight('mygroupsBarDiv', 'mygroupsBarLeftButton', 'mygroupsBarRightButton', 'mygroupsBar')" onmouseout="stopScrollRight('mygroupsBarDiv')" />
				</div>
			</div>
		</div>

		<!-- MY ISSUES BAR AREA -->
		<div id="tab-content-home-recent-mydebate-div" style="clear:both;float:left;width:100%;padding:5px;padding-bottom:10px;margin-top:20px;display:none">
			<h2 style="font-size:14pt;width:100%;padding:5px;padding-right:0px;background: linear-gradient(to bottom, #FFFFFF, #E8E8E8) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100%;height:25px"><?php echo $LNG->HOME_MY_DEBATES_TITLE; ?>
				<a style="margin-left:10px;font-size:10pt" href="user.php?userid=<?php echo $USER->userid; ?>#data-issue"><?php echo $LNG->HOME_MY_DEBATES_AREA_LINK; ?></a>

				<?php if ($CFG->issueCreationPublic || (!$CFG->issueCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>
					<span style="margin-left:20px;font-size:10pt">(
						<?php if (isset($USER->userid)) { ?>
							<span class="active" onclick="javascript:loadDialog('createdebate','<?php print($CFG->homeAddress);?>ui/popups/issueadd.php', 780, 600);"><?php echo $LNG->GROUP_DEBATE_CREATE_BUTTON; ?></span>
						<?php } else {
							if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
								echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registeropen.php">'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_OPEN;
							} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
								echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">'.$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_REQUEST;
							} else {
								echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_CLOSED;
							}
						} ?>
					)</span>

				<?php } ?>

			</h2>

			<div id="myissuesbarcontent" style="padding:0px; margin:0px;float:left;clear:both;margin-top:3px;width:100%">
				<div style="clear:both;float:left;height:200px">
					<img style="margin-top:80px;padding-right:5px;" id="myissuesBarLeftButton" src="<?php echo $HUB_FLM->getImagePath('back-arrow-grey.png'); ?>" onmouseover="scrollDivLeft('myissuesBarDiv', 'myissuesBarLeftButton', 'myissuesBarRightButton', 'myissuesBar')" onmouseout="stopScrollLeft('myissuesBarDiv')" />
				</div>

				<div id="myissuesBarDiv" class="plainborder bodyback" style="width:900px;overflow-x:hidden;overflow-y:hidden;height:200px;float:left;">
					<div id="myissuesBar" style="float:left;"></div>
				</div>

				<div style="float:left;height:200px">
					<img style="margin-top:80px;padding-left:5px;" id="myissuesBarRightButton" src="<?php echo $HUB_FLM->getImagePath('forward-arrow.png'); ?>" onmouseover="scrollDivRight('myissuesBarDiv', 'myissuesBarLeftButton', 'myissuesBarRightButton', 'myissuesBar')" onmouseout="stopScrollRight('myissuesBarDiv')" />
				</div>
			</div>
		</div>

		<!-- RECENT GROUPS BAR AREA -->
		<div id="tab-content-home-recent-group-div" style="clear:both;float:left;width:100%;margin-top:10px;display:none">
			<h2 style="font-size:14pt;padding:5px;padding-right:0px;background: linear-gradient(to bottom, #FFFFFF, #E8E8E8) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100%;height:25px"><?php echo $LNG->HOME_MOST_RECENT_GROUPS_TITLE ?>
			<a style="margin-left:10px;font-size:10pt" href="index.php#group-list"><?php echo $LNG->HOMEPAGE_VIEW_ALL; ?></a>

			<?php if ($CFG->groupCreationPublic || (!$CFG->groupCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>

				<span style="margin-left:20px;font-size:10pt">(
					<?php if (isset($USER->userid)) { ?>
						<span class="active" onclick="javascript:loadDialog('creategroup','<?php print($CFG->homeAddress);?>ui/popups/groupadd.php', 720, 700);"><?php echo $LNG->GROUP_CREATE_TITLE; ?></span>
					<?php } else {
						if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registeropen.php">'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'</a> '.$LNG->GROUP_CREATE_LOGGED_OUT_OPEN;
						} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">'.$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT.'</a> '.$LNG->GROUP_CREATE_LOGGED_OUT_REQUEST;
						} else {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> '.$LNG->GROUP_CREATE_LOGGED_OUT_CLOSED;
						}
					} ?>
				)</span>

			<?php } ?>

			</h2>

			<!-- GROUPS BAR AREA -->
			<div style="padding:0px; margin:0px;float:left;clear:both;margin-top:3px;width:100%" id="groupsbarcontent">

			<div style="clear:both;float:left;height:200px">
				<img style="margin-top:80px;padding-right:5px;" id="groupsBarLeftButton" src="<?php echo $HUB_FLM->getImagePath('back-arrow-grey.png'); ?>" onmouseover="scrollDivLeft('groupsBarDiv', 'groupsBarLeftButton', 'groupsBarRightButton', 'groupsBar')" onmouseout="stopScrollLeft('groupsBarDiv')" />
			</div>

			<div id="groupsBarDiv" class="plainborder" style="width:900px;overflow-x:hidden;overflow-y:hidden;height:200px;float:left;">
				<div id="groupsBar" style="float:left;"></div>
			</div>

			<div style="float:left;height:200px">
				<img style="margin-top:80px;padding-left:5px;" id="groupsBarRightButton" src="<?php echo $HUB_FLM->getImagePath('forward-arrow.png'); ?>" onmouseover="scrollDivRight('groupsBarDiv', 'groupsBarLeftButton', 'groupsBarRightButton', 'groupsBar')" onmouseout="stopScrollRight('groupsBarDiv')" />
			</div>

			</div>

		</div>

		<!-- RECENT ISSUES BAR AREA -->
		<div id="tab-content-home-recent-debate-div" style="float:left;width:100%;margin-top:20px;display:none">
			<h2 style="font-size:14pt;padding:5px;padding-right:0px;width:100%;background: linear-gradient(to bottom, #FFFFFF, #E8E8E8) repeat scroll 0 0 rgba(0, 0, 0, 0);width:100%;height:25px"><?php echo $LNG->HOME_MOST_RECENT_DEBATES_TITLE ?>
			<a style="margin-left:10px;font-size:10pt" href="index.php#issue-list"><?php echo $LNG->HOMEPAGE_VIEW_ALL; ?></a>

			<?php if ($CFG->issueCreationPublic || (!$CFG->issueCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>

				<span style="margin-left:20px;font-size:10pt">(
					<?php if (isset($USER->userid)) { ?>
						<span class="active" onclick="javascript:loadDialog('createdebate','<?php print($CFG->homeAddress);?>ui/popups/issueadd.php', 780, 600);"><?php echo $LNG->GROUP_DEBATE_CREATE_BUTTON; ?></span>
					<?php } else {
						if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registeropen.php">'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_OPEN;
						} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">'.$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_REQUEST;
						} else {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_CLOSED;
						}
					} ?>
				)</span>

			<?php } ?>

			</h2>

			<!-- ISSUES BAR AREA -->
			<div id="issuesbarcontent" style="padding:0px; margin:0px;float:left;clear:both;margin-top:3px;width:100%">

			<div style="clear:both;float:left;height:200px">
				<img style="margin-top:80px;padding-right:5px;" id="issuesBarLeftButton" src="<?php echo $HUB_FLM->getImagePath('back-arrow-grey.png'); ?>" onmouseover="scrollDivLeft('issuesBarDiv', 'issuesBarLeftButton', 'issuesBarRightButton', 'issuesBar')" onmouseout="stopScrollLeft('issuesBarDiv')" />
			</div>

			<div id="issuesBarDiv" class="plainborder" style="width:900px;overflow-x:hidden;overflow-y:hidden;height:200px;float:left;">
				<div id="issuesBar" style="float:left;"></div>
			</div>

			<div style="float:left;height:200px">
				<img style="margin-top:80px;padding-left:5px;" id="issuesBarRightButton" src="<?php echo $HUB_FLM->getImagePath('forward-arrow.png'); ?>" onmouseover="scrollDivRight('issuesBarDiv', 'issuesBarLeftButton', 'issuesBarRightButton', 'issuesBar')" onmouseout="stopScrollRight('issuesBarDiv')" />
			</div>

			</div>
		</div>

		<div style="float:left;width:100%;margin-top:10px;margin-left:5px;">
		</div>

	</div>
</div>
