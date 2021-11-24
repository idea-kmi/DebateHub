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

array_push($HEADER,'<script src="'.$HUB_FLM->getCodeWebPath('ui/grouplib.js.php').'" type="text/javascript"></script>');

//include_once($HUB_FLM->getCodeDirPath("ui/grouppagelib.php"));
include_once($HUB_FLM->getCodeDirPath("ui/popuplib.php"));

global $USER;
$groupid = required_param("groupid",PARAM_ALPHANUM);

$ref = "http" . ((!empty($_SERVER["HTTPS"])) ? "s" : "") . "://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

$embed = optional_param("embed",false,PARAM_TEXT);
if ($embed) {
	$_SESSION['embedded'] = $embed;
}

// default parameters
$start = optional_param("start",0,PARAM_INT);
$max = optional_param("max",20,PARAM_INT);
$orderby = optional_param("orderby","",PARAM_ALPHA);
$sort = optional_param("sort","DESC",PARAM_ALPHA);

// filter parameters
$filtergroup = optional_param("filtergroup","",PARAM_TEXT);
$filterlist = optional_param("filterlist","",PARAM_TEXT);
$filternodetypes = optional_param("filternodetypes","",PARAM_TEXT);
$filterusers = optional_param("filterusers","",PARAM_TEXT);
$filterthemes = optional_param("filterthemes","",PARAM_TEXT);
$filterbyconnection = optional_param("filterbyconnection","",PARAM_TEXT);

// if coming from a search
$query = stripslashes(optional_param("q","",PARAM_TEXT));

$group = getGroup($groupid);
//getGroup does not return group properties apart from its members

if($group instanceof Error){
	include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
	echo "<h1>Group not found</h1>";
	include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
	die;
} else {
	$userid = "";
	if (isset($USER->userid)) {
		$userid = $USER->userid;
	}
	auditGroupView($userid, $groupid, 'group');
}

$userset = $group->members;
$members = $userset->users;
$memberscount = 0;
if (is_countable($members)) {
	$memberscount = count($members);
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
				$memberscount = count($members);
			}
		} else if ($group->isopenjoining == 'N') {
			$reply = $group->joinrequest($USER->userid);
			if (!$reply instanceof error) {
				// loop through group members and send all admins and email about the join request.
				$count = 0;
				if (is_countable($members)) {
					$count = count($members);
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

//$referer = $_SERVER['HTTP_REFERER'];
//if (isset($referer) && strpos($referer, $CFG->homeAddress) === FALSE) {
//if ($_SESSION['embedded']) {
//	include_once($HUB_FLM->getCodeDirPath("ui/headerembed.php"));
//} else {
	include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
//}

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

$args["filtergroup"] = $filtergroup;
$args["filterlist"] = $filterlist;
$args["filternodetypes"] = $filternodetypes;
$args["filterusers"] = $filterusers;
$args["filterthemes"] = $filterthemes;
$args["filterbyconnection"] = $filterbyconnection;

$args["q"] = $query;

//$args["scope"] = $scope; //not used in inner searches
//$args["tagsonly"] = $tagsonly; //not used in inner searches

$args["title"] = $group->name;

 // now trigger the js to load data
 $argsStr = "{";
 $keys = array_keys($args);
$count = 0;
if (is_countable($keys)) {
	$count = count($keys);
}
for($i=0;$i< $count; $i++){
	 $argsStr .= '"'.$keys[$i].'":"'.$args[$keys[$i]].'"';
	 if ($i != ($count-1)){
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
	echo 'Caught exception: ',  $e->getMessage(), "<br>";
}

echo "var groupObj = ";
echo $jsonnode;
echo ";";

echo "</script>";
?>

<div style="clear:both;border-top:2px solid #E8E8E8;"></div>

<div style="clear:both;float: left; width:100%;height:100%;">

	<!-- LEFT COLUMN -->
	<div style="width 100%;">
		<div style="float:left;width: 100%;font-weight:normal;margin-top:10px;font-size:11pt;">

			<div style="clear:both;float: left;">
				<div style="clear:both;float: left;" id="maingroupdiv" style="clear:both;float:left;width:100%;margin:0px;padding:0px;margin-bottom:20px;margin-top:10px;margin-right:210px;"></div>
				<div style="float: left;margin-left:20px;width:200px;">
					<fieldset class="plainborder" style="clear:both;margin-bottom:10px;padding-bottom:5px;">
					<legend><h2 style="padding:0px;margin:0px;"><?php echo $LNG->GROUP_MEMBERS_LABEL; ?></h2></legend>

						<div style="clear:both;float:left;width:100%;margin:0px;padding:0px;height:100px;overflow-y:auto">
						<?php
							foreach($members as $u){
								echo "<a href='user.php?userid=".$u->userid."' title='".$u->name."'><img style='padding:5px;' border='0' src='".$u->thumb."'/></a>";
							}
						?>
						</div>

						<div style="float:left;width:100%;margin:0px;padding:0px;margin-top:10px;margin-bottom:0px;">
							<?php if (isset($USER->userid) && isGroupPendingMember($groupid,$USER->userid)) { ?>
								<img style="margin-right:5px;" src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" onmouseover="showGlobalHint('PendingMember', event, 'hgrhint')" onmouseout="hideHints()" border="0" /><span style="font-size:10pt;"><?php echo $LNG->GROUP_JOIN_PENDING_MESSAGE; ?></span>
							<?php } else if (isset($USER->userid) && !isGroupMember($groupid,$USER->userid)) {
								if ($group->isopenjoining == 'Y') { ?>
									<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
										<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
										<input class="submitleft" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup">
									</form>
								<?php } else if ($group->isopenjoining == 'N' && !isGroupRejectedMember($groupid,$USER->userid) && !isGroupReportedMember($groupid,$USER->userid)) {  ?>
									<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
										<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
										<input class="submitleft" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP_CLOSED; ?>" id="joingroup" name="joingroup">
									</form>
								<?php } ?>
							<?php } ?>
						</div>
					</fieldset>
				</div>
			</div>

			<!-- div id="pageseparatorbar" class="modeback3" style="clear:both;height:5px; width:100%"></div -->

			<div id="addnewissuearea" style="clear:both; float:left;margin-bottom:10px;">
				<div style="float:left;width:100%;margin-top:15px;margin-left:5px;margin-bottom:5px;font-size:12pt">
					<?php if ($CFG->issueCreationPublic || (!$CFG->issueCreationPublic && $USER->getIsAdmin() == "Y" )) { ?>

						<?php if (isset($USER->userid) && isGroupMember($groupid,$USER->userid)) { ?>
							<span class="active" onclick="javascript:loadDialog('createdebate','<?php print($CFG->homeAddress);?>ui/popups/issueadd.php?groupid=<?php echo $groupid; ?>', 780, 600);"><img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="vertical-align:bottom;padding-right:3px;" /><?php echo $LNG->GROUP_DEBATE_CREATE_BUTTON; ?></span><br>
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
								<span><img style="margin-right:5px;" src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" onmouseover="showGlobalHint('PendingMember', event, 'hgrhint')" onmouseout="hideHints()" border="0" /><?php echo $LNG->GROUP_JOIN_PENDING_MESSAGE; ?></span>
							<?php } else if ($group->isopenjoining == 'Y') {?>
								<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
									<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
									<input class="submitleft" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup">
								</form>
							<?php } else if ($group->isopenjoining == 'N' && !isGroupMember($groupid,$USER->userid) && !isGroupRejectedMember($groupid,$USER->userid) && !isGroupReportedMember($groupid,$USER->userid)) {  ?>
								<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
									<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
									<input class="mainfont active submitleft" style="white-space: normal;font-size:12pt; border: none; background: transparent" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP_CLOSED; ?>" id="joingroup" name="joingroup"><?php echo $LNG->GROUP_JOIN_GROUP; ?></input>
								</form>
							<?php }
						}
					}?>
				</div>
			</div>

			<div id='tab-content-toolbar-issue' style='clear:both; float:left; width: 100%;'>
				<div id='tab-content-issue-list' class='tabcontentinner'></div>
			</div>
		</div>
	</div>

	<!-- RIGHT COLUMN -->
	<!-- div style="float: right; height:100%;width:195px; margin-left: -210px; padding: 5px;">
		<div style="float:left;height:100%;">
			<fieldset class="plainborder" style="clear:both;margin-bottom:35px;margin-top:5px;">
			<legend><h2><?php echo $LNG->GROUP_MEMBERS_LABEL; ?></h2></legend>
				<div style="clear:both;float:left;width:100%;margin:0px;padding:0px;">
				<?php
					foreach($members as $u){
						echo "<a href='user.php?userid=".$u->userid."' title='".$u->name."'><img style='padding:5px;' border='0' src='".$u->thumb."'/></a>";
					}
				?>
				<div>
			<div style="float:left;width:100%;margin:0px;padding:px;margin-top:5px;margin-bottom:10px;">
				<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
					<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
					<input <?php
						if (!isset($USER->userid) || ( isset($USER->userid) && isset($group) && $group->ismember($USER->userid) ) ) { echo 'disabled'; } ?> class="submitleft" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup">
				</form>
			</div>
			</fieldset>

			<fieldset class="plainborder">
				<div id="radiobuttonsum" class="groupbutton modeback3 modeborder3">
					<div class="groupbuttoninner"><a style="color:white" href="<?php echo $CFG->homeAddress; ?>ui/stats/groups/index.php?groupid=<?php echo $groupid; ?>"><?php echo $LNG->PAGE_BUTTON_DASHBOARD; ?></a></div>
				</div>
				<!-- div id="radiobuttonshare" class="groupbutton modeback2 modeborder2" onclick="alert('This button will eventually allow you to share content off site.');">
					<div class="groupbuttoninner"><?php echo $LNG->PAGE_BUTTON_SHARE; ?></div>
				</div -->
			<!-- /fieldset>
		</div>
	</div -->
</div>

<script type='text/javascript'>
	function updateUserFollow() {
		$('followupdate').submit()
	}
</script>

<?php
include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>
