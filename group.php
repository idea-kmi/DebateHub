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
include_once("config.php");

$me = substr($_SERVER["PHP_SELF"], 1); // remove initial '/'
if ($HUB_FLM->hasCustomVersion($me)) {
	$path = $HUB_FLM->getCodeDirPath($me);
	include_once($path);
	die;
}

array_push($HEADER,'<script src="'.$HUB_FLM->getCodeWebPath('ui/grouplib.js.php').'" type="text/javascript"></script>');

include_once($HUB_FLM->getCodeDirPath("ui/popuplib.php"));

global $USER;

$groupid = required_param("groupid",PARAM_ALPHANUMEXT);
$group = getGroup($groupid);
if($group instanceof Hub_Error){
	// Check if Users table has OriginalID field and if so check if this groupid is an old ID and adjust.
	$params = array();
	$resArray = $DB->select($HUB_SQL->AUDIT_USER_CHECK_ORIGINALID_EXISTS, $params);
	if ($resArray !== false) {

		$rescount = 0;
		if (is_countable($resArray)) {
			$rescount = sizeof($resArray);
		}

		if ($rescount > 0) {
			$array = $resArray[0];
			if (isset($array['OriginalID'])) {
				$params = array();
				$params[0] = $groupid;
				$resArray2 = $DB->select($HUB_SQL->AUDIT_USER_SELECT_ORIGINALID, $params);
				if ($resArray2 !== false) {

					$rescount2 = 0;
					if (is_countable($resArray2)) {
						$rescount2 = sizeof($resArray2);
					}

					if ($rescount2 > 0) {
						$array2 = $resArray2[0];
						$groupid = $array2['UserID'];
						header("Location: ".$CFG->homeAddress."group.php?groupid=".$groupid);
						die;
					}
				}
			}
		}
	}
}

if($group instanceof Hub_Error){
	include_once($HUB_FLM->getCodeDirPath("ui/header.php")); ?>
	<div class="container-fluid">
		<h1 class="text-center m-4 p-4">Group not found</h1>
	</div>
	<?php include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
	die;
} else {
	// reported groups are still visible - in case maliciously reported. Which would take out too much content
	// even admins can't see archived groups - have to look at the content through the reported groups admin screen
	if (($group->status != $CFG->USER_STATUS_ACTIVE && $group->status != $CFG->USER_STATUS_REPORTED) 
				&& (!isset($USER->userid) || $USER->getIsAdmin() == "N" || $group->status == $CFG->STATUS_ARCHIVED)) {
		include_once($HUB_FLM->getCodeDirPath("ui/headerdialog.php"));
		echo "<div class='errors'>".$LNG->ITEM_NOT_AVAILABLE_ERROR."</div>";
		include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
		die;
	} 

	$userid = "";
	if (isset($USER->userid)) {
		$userid = $USER->userid;
	}
	auditGroupView($userid, $groupid, 'group');
}

$ref = "http" . ((!empty($_SERVER["HTTPS"])) ? "s" : "") . "://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

// default parameters
$start = optional_param("start",0,PARAM_INT);
$max = optional_param("max",20,PARAM_INT);
$orderby = optional_param("orderby","",PARAM_ALPHA);
$sort = optional_param("sort","DESC",PARAM_ALPHA);

// if coming from a search
$query = stripslashes(optional_param("q","",PARAM_TEXT));

$userset = $group->members;
$members = $userset->users;

$memberscount = 0;
if (is_countable($members)) {
	$memberscount = sizeof($members);
}

$isCurrentUserGroupAdmin = false;
if (isset($USER->userid) && $USER->userid != "") {
	$isCurrentUserGroupAdmin = $group->isgroupadmin($USER->userid);
}

$context = $CFG->GROUP_CONTEXT;

if (isset($_POST["joingroup"]) && isset($_SERVER['REQUEST_URI'])) {
	if (isset($USER->userid) && $USER->userid != "" && isset($group)) {
		if ($group->isopenjoining == 'Y') {
			$group = $group->addmember($USER->userid);
			$userset = $group->members;
			$members = $userset->users;
			$memberscount = 0;
			if (is_countable($members)) {
				$memberscount = sizeof($members);
			}
		} else if ($group->isopenjoining == 'N') {
			$reply = $group->joinrequest($USER->userid);
			if (!$reply instanceof Hub_Error) {
				// loop through group members and send all admins and email about the join request.
				$count = 0;
				if (is_countable($members)) {
					$count = sizeof($members);
				}
				for($i=0; $i<$count; $i++) {
					$member = $members[$i];
					if ($group->isgroupadmin($member->userid)) {
						$paramArray = array ($group->name,$CFG->homeAddress,$USER->userid,$USER->name,$CFG->homeAddress,$groupid);
						sendMail("groupjoinrequest",$LNG->VALIDATE_GROUP_JOIN_SUBJECT,$member->getEmail(),$paramArray);
					}
				}
			}
		}
	}
}

include_once($HUB_FLM->getCodeDirPath("ui/header.php"));

$args = array();
$args["groupid"] = $groupid;
$args["isgroupadmin"] = ($isCurrentUserGroupAdmin ? 'true' : 'false');

$args["start"] = $start;
$args["max"] = $max;
$wasEmpty = false;
if ($orderby == "") {
	$args["orderby"] = 'date';
	$wasEmpty = true;
} else {
	$args["orderby"] = $orderby;
}
$args["sort"] = $sort;
$args["q"] = $query;
$args["title"] = $group->name;

 // now trigger the js to load data
 $argsStr = "{";
 $keys = array_keys($args);

$keycount = 0;
if (is_countable($keys)) {
	$keycount = sizeof($keys);
}

 for($i=0;$i< $keycount; $i++){
	 $argsStr .= '"'.$keys[$i].'":"'.$args[$keys[$i]].'"';
	 if ($i != ($keycount-1)){
		 $argsStr .= ',';
	 }
 }
 $argsStr .= "}";

if ($wasEmpty) {
	$args["orderby"] = 'date';
}

echo "<script type='text/javascript'>";

echo "var CONTEXT = '".$context."';";
echo "var NODE_ARGS = ".$argsStr.";";
echo "var ISSUE_ARGS = ".$argsStr.";";

try {
	$jsonnode = json_encode($group, JSON_INVALID_UTF8_IGNORE);
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "<br />";
}

echo "var groupObj = ";
echo $jsonnode;
echo ";";

echo "</script>";
?>

<div class="container-fluid">
	<div class="row">
		<!-- LEFT COLUMN -->
		<div id="maingroupdiv" class="col-md-12 col-lg-9"></div>
		
		<!-- RIGHT COLUMN -->
		<div class="col-md-12 col-lg-3">
			<fieldset class="border border-2 mx-2 my-4 p-3">
				<legend><h3><?php echo $LNG->GROUP_MEMBERS_LABEL; ?></h3></legend>
				<div class="d-flex flex-row flex-wrap">
					<?php
						foreach($members as $u) {
							if ($group->isgroupadmin($u->userid)) { ?>
								<a class="m-1" href='user.php?userid=<?php echo $u->userid; ?>' title='<?php echo $LNG->GROUP_FORM_ISADMIN_LABEL; ?>: <?php echo $u->name; ?>'>
									<img class='solutionborder solutionback p-1' src='<?php echo $u->thumb; ?>' alt='<?php echo $u->name; ?> profile picture' />
								</a>
							<?php } else { ?>
								<a class="m-1" href='user.php?userid=<?php echo $u->userid; ?>' title='<?php echo $u->name; ?>'>
									<img src='<?php echo $u->thumb; ?>' alt='<?php echo $u->name; ?> profile picture' />
								</a>
							<?php }
						}
					?>
				</div>
				<?php if (isset($USER->userid) && isGroupPendingMember($groupid,$USER->userid)) { ?>
					<div>
						<img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" onmouseover="showGlobalHint('PendingMember', event, 'hgrhint')" onmouseout="hideHints()" />
						<span><?php echo $LNG->GROUP_JOIN_PENDING_MESSAGE; ?></span>
					</div>
				<?php } else if (isset($USER->userid) && !isGroupMember($groupid,$USER->userid)) {
					if ($group->isopenjoining == 'Y') { ?>
						<div>
							<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post" class="d-grid gap-2">
								<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>" />
								<input class="btn btn-primary mt-2" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup" />
							</form>
						</div>
					<?php } else if ($group->isopenjoining == 'N' && !isGroupRejectedMember($groupid,$USER->userid) && !isGroupReportedMember($groupid,$USER->userid)) {  ?>
						<div>
							<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post" class="d-grid gap-2">
								<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>" />
								<input class="btn btn-primary mt-2" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP_CLOSED; ?>" id="joingroup" name="joingroup" />
							</form>
						</div>
					<?php } ?>
				<?php } ?>
			</fieldset>

			<?php if (($CFG->GROUP_DASHBOARD_VIEW == 'public') || (isset($USER->userid) && ($CFG->GROUP_DASHBOARD_VIEW == 'private')) ) { ?>
				<?php $nowtime = time();
					if (isset($USER) && isset($USER->userid) && $USER->getTestGroup() == 2 && $nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>
					<div id="radiobuttonsum" class="d-grid gap-2 m-2">
						<div class="btn btn-secondary text-dark fw-bold"><span onclick="return auditDashboardButton(NODE_ARGS['groupid'], this.innerHTML, '<?php echo $CFG->homeAddress; ?>ui/stats/groups/dashboard.php?groupid=<?php echo $groupid; ?>', 'group', 'dashboardButton_Group_V1')"><?php echo $LNG->TESTING_ANALYTICS_BUTTON; ?></span></div>
					</div>
				<?php } else { ?>
					<div id="radiobuttonsum" class="d-grid gap-2 m-2">
						<div class="btn btn-secondary text-dark fw-bold"><span onclick="return auditDashboardButton(NODE_ARGS['groupid'], this.innerHTML, '<?php echo $CFG->homeAddress; ?>ui/stats/groups/index.php?groupid=<?php echo $groupid; ?>', 'group', 'dashboardButton_Group_V1')"><?php echo $LNG->PAGE_BUTTON_DASHBOARD; ?></span></div>
					</div>
				<?php } ?>
			<?php } ?>

		</div>
	</div>

	<div class="row px-3" id="addnewissuearea">
		<div class="col">
			<?php if ($CFG->issueCreationPublic || (!$CFG->issueCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>

				<?php if (isset($USER->userid) && isGroupMember($groupid,$USER->userid)) { ?>
					<span class="active" onclick="javascript:loadDialog('createdebate','<?php print($CFG->homeAddress);?>ui/popups/issueadd.php?groupid=<?php echo $groupid; ?>', 780, 600);"><img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" alt="<?php echo $LNG->GROUP_DEBATE_CREATE_BUTTON; ?> button" class="me-2" /><?php echo $LNG->GROUP_DEBATE_CREATE_BUTTON; ?></span>
				<?php } else {
						if (!isset($USER->userid)) {
						if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registeropen.php">'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_OPEN;
						} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">'.$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_REQUEST;
						} else {
							echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> '.$LNG->DEBATE_CREATE_LOGGED_OUT_CLOSED;
						}
					} else if (isGroupPendingMember($groupid,$USER->userid)) { ?>
						<span><img src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" onmouseover="showGlobalHint('PendingMember', event, 'hgrhint')" onmouseout="hideHints()" /><?php echo $LNG->GROUP_JOIN_PENDING_MESSAGE; ?></span>
					<?php } else if ($group->isopenjoining == 'Y') {?>
						<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
							<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
							<input class="btn btn-primary" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup">
						</form>
					<?php } else if ($group->isopenjoining == 'N' && !isGroupMember($groupid,$USER->userid) && !isGroupRejectedMember($groupid,$USER->userid) && !isGroupReportedMember($groupid,$USER->userid)) {  ?>
						<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
							<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
							<input class="btn btn-primary" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP_CLOSED; ?>" id="joingroup" name="joingroup"><?php echo $LNG->GROUP_JOIN_GROUP; ?></input>
						</form>
					<?php }
				}
			}?>
		</div>
	</div>

	<div class="row p-3" id='tab-content-toolbar-issue'>
		<div id='tab-content-issue-list' class='issueGroups tabcontentinner'></div>
	</div>
</div>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>
