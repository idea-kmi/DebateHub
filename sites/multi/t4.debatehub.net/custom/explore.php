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

include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/ui/stats/debates/visdata.php');

global $USER;

$ref = "http" . ((!empty($_SERVER["HTTPS"])) ? "s" : "") . "://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

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

$nodeid = required_param("id",PARAM_TEXT);
$focusid = optional_param("focusid","",PARAM_TEXT);
if ($focusid != "") {
	$selectednodeid = $focusid;
} else {
	$selectednodeid = $nodeid;
}

$searchid = optional_param("sid","",PARAM_TEXT);
if ($searchid != "" && isset($USER->userid)) {
	auditSearchResult($searchid, $USER->userid, $nodeid, 'N');
}

$node = getNode($nodeid, 'long');
if($node instanceof Error){
	echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
	include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
	die;
} else {
	$userid = "";
	if (isset($USER->userid)) {
		$userid = $USER->userid;
	}
	auditView($userid, $nodeid, 'explore');
}

$issueClosed = false;
$issueNotStarted = false;
if (isset($node->startdatetime) && isset($node->enddatetime)) {
	$now = time();
	if ($now < $node->startdatetime) {
		$issueNotStarted = true;
	} else if ($now >= $node->startdatetime && $now <= $node->enddatetime) {
		$issueClosed = false;
		$issueNotStarted = false;
	} else if ($now > $node->enddatetime) {
		$issueClosed = true;
		$issueNotStarted = false;
	}
}
$votingStatus = 'off';
if (isset($node->properties['votingstart'])) {
	$now = time();
	$votingstart = 	doubleval( $node->properties['votingstart'] );
	if ($now >= $votingstart) {
		$votingStatus = 'on';
	}
} else {
	$votingStatus = 'on';
}

$nodetype = $node->role->name;
if ($nodetype != "Issue") {
	//get the Issue for this node.
	if ($nodetype == "Solution") {
		$selectednodeid = $nodeid;
		$connSet = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all','','Issue');
		$con = $connSet->connections[0];
		$node = $con->to;
		if($node instanceof Error){
			echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
			include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
			die;
		} else {
			$nodeid = $node->nodeid;
			$node = getNode($nodeid);
			if($node instanceof Error){
				echo "<h1>".$LNG->ISSUE_NAME." ".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
				include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
				die;
			}
		}
	} else if ($nodetype == "Pro" || $nodetype == "Con" || $nodetype == "Comment"){
		$selectednodeid = $nodeid;
		$conSetSol = getConnectionsByNode($node->nodeid,0,1,'date','ASC', 'all', '', 'Solution');
		$consol = $conSetSol->connections[0];
		$nodesol = $consol->to;
		$consSet = getConnectionsByNode($nodesol->nodeid,0,1,'date','ASC', 'all', '', 'Issue');
		$con = $consSet->connections[0];
		$localnode = $con->to;
		if($node instanceof Error){
			echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
			include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
			die;
		} else {
			$nodeid = $localnode->nodeid;
			$node = getNode($nodeid);
			if($node instanceof Error){
				echo "<h1>".$LNG->ITEM_NOT_FOUND_ERROR."</h1>";
				include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
				die;
			}
		}
	}
}

$tags = "";
if (isset($node->tags)) {
	$nodetags = $node->tags;
	$i=0;
	foreach ($nodetags as $tag) {
		if ($i = 0) {
			$tags .= $tag->name;
		} else {
			$tags .= ",".$tag->name;
		}
		$i++;
	}
}

$groupid = optional_param("groupid", "", PARAM_ALPHANUM);
// try and get the groupid from the node
if ($groupid == "") {
	if (isset($node->groups)) {
		$groups = $node->groups;
		// there should only be one group per node.
		$count = 0;
		if (is_countable($groups)) {
			$count = count($groups);
		}
		if ($count > 0) {
			$groupid = $groups[0]->groupid;
		}
	}
}

if (isset($groupid) && $groupid != "") {
	$group = getGroup($groupid);
	//getGroup does not return group properties apart from its members
	if($group instanceof Error){
		echo "<h1>Group not found</h1>";
		include_once("includes/footer.php");
		die;
	} else {
		//$userset = $group->members;
		//$members = $userset->users;
		//$memberscount = 0;
		//if (is_countable($members)) {
		//	$memberscount = count($members);
		//}
	}
}

$errors = array();

if (isset($_POST["joingroup"])) {
	if (isset($USER->userid) && $USER->userid != "" && isset($group)) {
		if ($group->isopenjoining == 'Y') {
			$group->addmember($USER->userid);
			//$userset = $group->members;
			//$memberscount = 0;
			//if (is_countable($members)) {
			//	$memberscount = count($members);
			//}
		} else if ($group->isopenjoining == 'N') {
			$reply = $group->joinrequest($USER->userid);
			if (!$reply instanceof error) {
				// loop through group members and send all admins and email about the join request.
				$userset = $group->members;
				$members = $userset->users;
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
				//$userset = $group->members;
				//$memberscount = count($members);
				//if (is_countable($members)) {
				//	$memberscount = count($members);
				//}
			}
		}
	}
}

unset($_SESSION['HUB_CANADD']);

$canAdd = false;
$canEdit = true;
if ($issueClosed || $issueNotStarted) {
	$canAdd = false;
	$canEdit = false;
} else {
	if (isset($USER->userid) && isset($groupid) && isGroupMember($groupid,$USER->userid)) {
		$canAdd = true;
	} else if (isset($USER->userid) && (!isset($groupid) || $groupid == "")) {
		$canAdd = true;
	}
}

$can_moderate = false;
if (isset($USER->userid) &&
	($node->users[0]->userid == $USER->userid
		|| $USER->getIsAdmin() == "Y"
		|| ($group != "" && isGroupMember($groupid,$USER->userid) && $group->isgroupadmin($USER->userid))))
{
	$can_moderate = true;
}

$_SESSION['HUB_CANADD'] = $canAdd;
$_SESSION['HUB_CANEDIT'] = $canEdit;
$_SESSION['IS_MODERATOR'] = $can_moderate;

//$participants = getDebateParticipants($nodeid, 'mini');

$participationstats = getDebateParticipationStats($nodeid, 'mini');

$args = array();

$args["nodeid"] = $nodeid;
$args["selectednodeid"] = $selectednodeid;
$args["groupid"] = $groupid;
$args["nodetype"] = $nodetype;
$args["title"] = $node->name;
$args["tags"] = $tags;
$args["start"] = $start;
$args["max"] = $max;
$args["voting"] = $votingStatus;
$args["isClosed"] = ($issueClosed ? 'true' : 'false');
$args["mode"] = '';
$args["participants"] = $participationstats->peoplecount;

$wasEmpty = false;
if ($orderby == "") {
	$args["orderby"] = 'random';
	$wasEmpty = true;
} else {
	$args["orderby"] = $orderby;
}
$args["sort"] = $sort;

//$args["filtergroup"] = $filtergroup;
//$args["filterlist"] = $filterlist;
//$args["filternodetypes"] = $filternodetypes;
//$args["filterusers"] = $filterusers;
//$args["filterthemes"] = $filterthemes;
//$args["filterbyconnection"] = $filterbyconnection;

$CONTEXT = $CFG->NODE_CONTEXT;

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

array_push($HEADER,'<script src="'.$HUB_FLM->getCodeWebPath('ui/exploreutils.js.php').'" type="text/javascript"></script>');

//checkLogin();

include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
include_once($HUB_FLM->getCodeDirPath("ui/popuplib.php"));

try {
	$jsonnode = json_encode($node, JSON_INVALID_UTF8_IGNORE);
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "<br>";
}

echo "<script type='text/javascript'>";
echo "var CONTEXT = '".$CONTEXT."';";
echo "var NODE_ARGS = ".$argsStr.";";
echo "var nodeObj = ";
echo $jsonnode;
echo ";";
echo "</script>";
?>

<div style="clear:both;border-top:2px solid #E8E8E8;"></div>

<div style="clear:both;float: left; width:100%;height:100%;">
	<?php if (isset($groupid) && $groupid != "") { ?>
	<a href="<?php echo $CFG->homeAddress.'group.php?groupid='.$group->groupid;?>" style="float:left;font-weight:bold;font-size:12pt;padding-top:5px;"><?php echo $group->name; ?></a>
	<?php } ?>

	<!-- LEFT COLUMN -->
	<div style="width 100%;margin-right: 210px;">
	<!-- div style="width 100%;" -->

		<div style="float:left;width: 99%;font-weight:normal;margin-top:0px;font-size:11pt;">
		<!-- div style="float:left;width: 99%;font-weight:normal;margin-top:0px;font-size:11pt;border-right:2px solid #E8E8E8" -->
		<!-- div style="float:left;width: 99%;font-weight:normal;margin-top:0px;font-size:11pt;" -->

			<div id="mainnodediv" style="clear:both;float:left;margin:0px;padding:0px;margin-bottom:0px;margin-top:10px;margin-right: 290px;"></div>
			<div id="pageseparatorbar" class="modeback1" style="clear:both;height:5px;width:760px;padding-right:5px;"></div>

			<div id="maininner" class="" style="clear:both;float:left;margin-right:5px;padding:5px;">
				<?php if (isset($USER->userid)) {
					if ( ($groupid != "" && isGroupMember($groupid,$USER->userid)) || $groupid == "") { ?>
						<div id="addnewideaarea" style="clear:both; float:left;margin-bottom:10px;">
							<?php
							if(!empty($errors)){
								echo "<div class='errors'>".$LNG->FORM_ERROR_MESSAGE.":<ul>";
								foreach ($errors as $error){
									echo "<li>".$error."</li>";
								}
								echo "</ul></div>";
							} else {
								$idea = "";
								$ideadesc = "";
							}
							?>
							<!-- div id="newideaform">
								<h3 onclick="toggleNewIdea();return false;" class="active" style="float:left;margin-bottom:0px;padding-bottom:0px;margin-left:5px;"><img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="vertical-align:bottom;padding-right:3px;" /><?php echo $LNG->FORM_IDEA_NEW_TITLE; ?><img id="newideadivbutton" style="vertical-align:middle" class="active" border="0" src="<?php echo $HUB_FLM->getImagePath("arrow-down2.png"); ?>" /></h3>
								<div id="addformdividea" style="float:left;width:100%;display:none">
									<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
									<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>">
									<div class="formrowsm">
										<input <?php if (!$canAdd){ echo 'disabled'; } ?> class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>" id="addideaname" name="idea" value="" />
									</div>
									<div class="formrowsm">
										<textarea <?php if (!$canAdd){ echo 'disabled'; } ?> rows="3" class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>" id="addideadesc" name="ideadesc" value=""></textarea>
									</div>
									<div class="formrowsm">
										<button <?php if (!$canAdd){ echo 'disabled'; } ?> class="submitright" id="addidea" name="addidea" onclick="addIdeaNode(nodeObj, '', 'idea', 'active', true, <?php echo $CFG->STATUS_ACTIVE; ?>)"><?php echo $LNG->FORM_BUTTON_SUBMIT; ?></button>
									</div>
								</div>
							</div -->

							<div id="mergeideaform" style="display:none;margin-top:5px;">
								<button onclick="toggleMergeIdeas();return false;" style="margin-bottom:0px;padding-bottom:0px;margin-left:5px;float:left;"><h2 style="margin-bottom:0px"><?php echo $LNG->FORM_IDEA_MERGE_TITLE; ?></h2></button>
								<img title="<?php echo $LNG->FORM_IDEA_MERGE_HINT; ?>" src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" border="0" style="vertical-align:top;padding-left:5px;padding-top:3px;">
								<div id="mergeideadiv" style="float:left;display:none;clear:both">
									<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
									<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>">
									<div class="formrowsm">
										<input <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_MERGE_LABEL_TITLE; ?>" id="mergeidea" name="mergeidea" value="" />
									</div>
									<div class="formrowsm">
										<textarea <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> rows="3" class="forminput hgrwide" placeholder="<?php echo $LNG->FORM_IDEA_MERGE_LABEL_DESC; ?>" id="mergeideadesc" name="mergeideadesc" value=""></textarea>
									</div>
									<div class="formrowsm">
										<button <?php if (!isset($USER->userid) || (isset($USER->userid) && isset($groupid) && $groupid != "" && !isGroupMember($groupid,$USER->userid))){ echo 'disabled'; } ?> class="submitright" id="mergeidea" name="mergeidea" onclick="mergeSelectedNodes()"><?php echo $LNG->FORM_BUTTON_SUBMIT; ?></button>
									</div>
								</div>
							</div>
						</div>
					<?php } else { ?>
						<!-- div style="float:left;clear:both;">
							<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
								<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
								<input class="mainfont active submitleft" style="font-size:11pt; border: none; background: transparent" <?php if (!isset($USER->userid) || (isset($USER->userid) && isGroupMember($groupid,$USER->userid))){echo 'disabled'; } ?> type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup"><?php echo $LNG->ISSUE_GROUP_JOIN_GROUP; ?></input>
							</form>
						</div -->
					<?php }
				 } else {
					/*
					echo '<div style="float:left;clear:both;">';
					if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
						echo $LNG->SOLUTION_CREATE_LOGGED_OUT_OPEN;
					} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
						echo $LNG->SOLUTION_CREATE_LOGGED_OUT_REQUEST;
					} else {
						echo $LNG->SOLUTION_CREATE_LOGGED_OUT_CLOSED;
					}
					echo '</div>';
					*/
				} ?>

				<div id='tab-content-toolbar-idea' style='clear:both; float:left; width: 752px;padding-right:3px;'>
					<div id='tab-content-idea-list' class='tabcontentinner'></div>
				</div>
			</div>
		</div>
	</div>

	<!-- RIGHT COLUMN -->
	<div style="float: right; height:100%;width:210px; margin-left: -210px;margin-top:5px;">

		<?php if (!$issueClosed){ ?>

		<div style="float: right; height:100%;width:210px; padding-right:0px;">
			<div id="newideaform" class="boxborder solutionbackpale" style="float:left;width:200px;padding:3px;margin-top:5px;min-height:160px;">

			<?php if (isset($USER->userid)) {
				if (($groupid != "" && isGroupMember($groupid,$USER->userid)) || ($groupid == "")){ ?>

				<h2 style="float:left;margin-bottom:0px;padding-bottom:0px;margin-left:5px;"><img src="<?php echo $HUB_FLM->getImagePath('add.png'); ?>" border="0" style="vertical-align:bottom;padding-right:3px;" /><?php echo $LNG->FORM_IDEA_NEW_TITLE; ?></h2>
				<div id="addformdividea" style="float:left;width:100%;display:block;">
					<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
					<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>">
					<div class="formrowsm">
						<input <?php if (!$canAdd){ echo 'disabled'; } ?> style="width:193px" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_TITLE; ?>" id="addideaname" name="idea" value="" />
					</div>
					<div class="formrowsm">
						<textarea <?php if (!$canAdd){ echo 'disabled'; } ?> rows="3"  style="width:193px" placeholder="<?php echo $LNG->FORM_IDEA_LABEL_DESC; ?>" id="addideadesc" name="ideadesc" value=""></textarea>
					</div>
					<div class="formrowsm">
						<button <?php if (!$canAdd){ echo 'disabled'; } ?> class="submitright" id="addidea" name="addidea" onclick="addIdeaNode(nodeObj, '', 'idea', 'active', true, <?php echo $CFG->STATUS_ACTIVE; ?>)"><?php echo $LNG->FORM_BUTTON_SUBMIT; ?></button>
					</div>
				</div>
			<?php } else if (isGroupPendingMember($groupid,$USER->userid)) { ?>
					<span><?php echo $LNG->GROUP_JOIN_PENDING_MESSAGE; ?><img style="margin-left:7px;" src="<?php echo $HUB_FLM->getImagePath('info.png'); ?>" onmouseover="showGlobalHint('PendingMember', event, 'hgrhint')" onmouseout="hideHints()" border="0" /></span>
			<?php } else if (isset($USER->userid) && $groupid != "" && !isGroupMember($groupid,$USER->userid)) { ?>
				<div style="float:left;clear:both;margin-top:20px;width:193px">
					<?php if ($group->isopenjoining == 'Y') {?>
						<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
							<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
							<center><input class="mainfont active submitleft" style="white-space: normal;font-size:12pt; border: none; background: transparent;width:100%" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP; ?>" id="joingroup" name="joingroup"></input>
							<br><div style="clear:both;float:left;width:100%"><?php echo $LNG->GROUP_JOIN_GROUP; ?></center></div>
						</form>
					<?php } else if ($group->isopenjoining == 'N' && !isGroupRejectedMember($groupid,$USER->userid) && !isGroupReportedMember($groupid,$USER->userid)) {  ?>
						<form id="joingroupform" name="joingroupform" action="" enctype="multipart/form-data" method="post">
							<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>">
							<center><input class="mainfont active submitleft" style="white-space: normal;font-size:12pt; border: none; background: transparent;width:100%" type="submit" value="<?php echo $LNG->FORM_BUTTON_JOIN_GROUP_CLOSED; ?>" id="joingroup" name="joingroup"></input>
							<br><div style="clear:both;float:left;width:100%"><?php echo $LNG->GROUP_JOIN_GROUP; ?></center></div>
						</form>
					<?php } ?>
				</div>
			<?php }
		} else { ?>
			<h2 style="float:left;margin-bottom:0px;padding-bottom:0px;margin-left:5px;"><img src="<?php echo $HUB_FLM->getImagePath('addgrey.png'); ?>" border="0" style="vertical-align:bottom;padding-right:3px;" /><?php echo $LNG->FORM_IDEA_NEW_TITLE; ?></h2>
			<div style="float:left;clear:both;margin-top:20px;">
			<?php
			if ($CFG->signupstatus == $CFG->SIGNUP_OPEN) {
				echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registeropen.php">'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'</a> '.$LNG->SOLUTION_CREATE_LOGGED_OUT_OPEN;
			} else if ($CFG->signupstatus == $CFG->SIGNUP_REQUEST) {
				echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> | <a title="'.$LNG->HEADER_SIGNUP_OPEN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/registerrequest.php">'.$LNG->HEADER_SIGNUP_REQUEST_LINK_TEXT.'</a> '.$LNG->SOLUTION_CREATE_LOGGED_OUT_REQUEST;
			} else {
				echo '<a title="'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'" href="'.$CFG->homeAddress.'ui/pages/login.php?ref='.urlencode($ref).'">'.$LNG->HEADER_SIGN_IN_LINK_TEXT.'</a> '.$LNG->SOLUTION_CREATE_LOGGED_OUT_CLOSED;
			}
			echo '</div>';
		}  ?>
		</div>
		<?php } else  { ?>
			<div style="float: right; height:100%;width:210px;">
				<div id="newideaform" class="boxborder solutionbackpale" style="float:left;width:200;padding:3px;margin-top:5px;min-height:160px;">
				<h2 style="float:left;margin-bottom:0px;padding-bottom:0px;margin-left:5px;"><img src="<?php echo $HUB_FLM->getImagePath('addgrey.png'); ?>" border="0" style="vertical-align:bottom;padding-right:3px;" /><?php echo $LNG->FORM_IDEA_NEW_TITLE; ?></h2>
				<div style="float:left;clear:both;margin-top:20px;">
					<?php echo $LNG->ISSUE_IDEA_ADD_CLOSED; ?>
				</div>
				</div>
			</div>
		<?php } ?>

		<?php if ($_SESSION['IS_MODERATOR']){ ?>
			<div id="moderatoralerts" style="float:left;display:block;margin-top:7px;">
				<div class="boxshadowsquare" style="width:190px;float:left;clear:both;">
					<h2 style="padding-top:0px;margin-top:0px;margin-bottom:0px;padding-bottom:0px;"><?php echo $LNG->ALERTS_BOX_TITLE_MODERATOR; ?></h2>
					<div id="moderatoralerts-messagearea" style="width:100%;float:left;clear:both;padding:0px;margin:0px;"></div>
					<div id="moderatoralerts-div" style="float:left;max-height:200px;overflow:auto">
						<div id="moderatoralerts-issue-div" style="clear:both;float:left;"></div>
						<div id="moderatoralerts-user-div" style="clear:both;float:left;"></div>
					</div>
				</div>
			</div>

			<!-- div id="radiobuttonorg" class="groupbutton modeback1 modeborder1" style="margin-left:0px;width:202px;margin-top:5px;" onclick="changeExploreMode(this, 'Organize')">
				<div class="groupbuttoninner"><?php echo $LNG->DEBATE_MODE_BUTTON_ORGANIZE; ?></div>
			</div -->
		<?php } ?>

		<?php
			$nowtime = time();
			if (isset($USER) && isset($USER->userid)
					&& $nowtime >= $CFG->TEST_TRIAL_START && $nowtime < $CFG->TEST_TRIAL_END) { ?>

			<div style="clear:both;float: left; height:100%;width:210px; padding-right:0px;margin-top:10px;">
				<table cellspacing="10" style="margin: 0 auto;border-spacing:5px 5px;width:210px;">
				<?php
				$count = 0;
				if (is_countable($sequence)) {
					$count = count($sequence);
				}
				for ($i=0; $i<$count; $i++) {
					$next = $sequence[$i];
					$nextitem = $dashboarddata[$next-1];
					$nextpage = $nextitem[1];

					$url = $nextitem[4].'page='.$nextitem[6].'&nodeid='.$nodeid;
					echo '<tr><td>';
					echo '<span class="tab current" onclick="auditExploreVisButtonClick(\''.$nodeid.'\', \''.$nextitem[6].'\', \'click\', \''.$url.'\');">';
					echo '<div style="width:177px;height:180px;padding:5px; font-weight:bold;" class="plainbackgradient plainborder curvedBorder homebutton1">';
					echo '<div style="padding:0px;"><center><h2 style="font-size:12pt">'.$nextitem[0].'</h2></center></div>';
					echo '<div style="margin: 0 auto; width:'.$nextitem[7].'px;margin-bottom:5px;">';
					echo '<img src="'.$nextitem[3].'" border="0" width="'.$nextitem[7].'" />';
					echo '</div>';
					echo '</div>';
					echo '</span>';
					echo '</td></tr>';
				} ?>
				</table>
			</div>
		<?php } ?>
	</div>
</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>
