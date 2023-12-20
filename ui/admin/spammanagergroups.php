<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2013-2023 The Open University UK                              *
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

    if($USER == null || $USER->getIsAdmin() == "N"){
        echo "<div class='errors'>.".$LNG->ADMIN_NOT_ADMINISTRATOR_MESSAGE."</div>";
        include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
        die;
	}

    $errors = array();

    if(isset($_POST["deletegroup"])){
		//$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);
    	//if ($groupid != "") {
			//$group = getGroup($groupid);
			// delete group and any upload folder
			//if (!adminDeleteUser($groupid)) {
			//	echo $LNG->ADMIN_MANAGE_USERS_DELETE_ERROR." ".$groupid;
			//}
    	//} else {
        //    array_push($errors,$LNG->SPAM_GROUP_ADMIN_ID_ERROR);
    	//}
    } else if(isset($_POST["archivegroup"])){
		$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);
   	if ($groupid != "") {
			archiveGroupAndChildren($groupid);
     	} else {
            array_push($errors,$LNG->SPAM_GROUP_ADMIN_ID_ERROR);
    	}
    } /*else if(isset($_POST["suspendgroup"])){
		$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);
    	if ($groupid != "") {
 			$group = getGroup($groupid);
	   		$group->updateStatus($CFG->USER_STATUS_SUSPENDED);
    	} else {
            array_push($errors,$LNG->SPAM_GROUP_ADMIN_ID_ERROR);
    	}
    }*/ 
	else if(isset($_POST["restoregroup"])){
		$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);
    	if ($groupid != "") {
			$group = getGroup($groupid);
	   		$group->updateStatus($CFG->USER_STATUS_ACTIVE);
    	} else {
            array_push($errors,$LNG->SPAM_GROUP_ADMIN_ID_ERROR);
    	}
    }
	else if(isset($_POST["restorearchivedgroup"])){
		$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);
    	if ($groupid != "") {
			restoreArchivedGroupAndChildren($groupid);
    	} else {
            array_push($errors,$LNG->SPAM_GROUP_ADMIN_ID_ERROR);
    	}
    }

	$allGroups = array();

	$gs = getGroupsByStatus($CFG->USER_STATUS_REPORTED, 0, -1, 'name', 'ASC','long');
    $groups = $gs->groups;
 
	$count = (is_countable($groups)) ? count($groups) : 0;
    for ($i=0; $i<$count;$i++) {
    	$group = $groups[$i];
		$group->children = loadGroupChildDebates($group->groupid, $CFG->STATUS_ACTIVE);
		$reporterid = getSpamReporter($group->groupid);
		if ($reporterid != false) {
    		$reporter = new User($reporterid);
    		$reporter = $reporter->load();
    		$group->reporter = $reporter;
			$group->istop = true;	// only top if it was the reported item
    	}
		$allGroups[$group->groupid] = $group;
    }

	// groups are really user record entries
	$gs2 = getGroupsByStatus($CFG->USER_STATUS_ARCHIVED, 0, -1, 'name', 'ASC','long');
    $groupssarchivedinitial = $gs2->groups;
	$archivedgroups  = [];

	$count2 = (is_countable($groupssarchivedinitial)) ? count($groupssarchivedinitial) : 0;
    for ($i=0; $i<$count2;$i++) {
    	$group = $groupssarchivedinitial[$i];
    	$reporterid = getSpamReporter($group->groupid);
    	if ($reporterid != false) {
    		$reporter = new User($reporterid);
    		$reporter = $reporter->load();
    		$group->reporter = $reporter;
			$group->children = loadGroupChildDebates($group->groupid, $CFG->STATUS_ARCHIVED);
			$group->istop = true; // only top if it was the reported item
			array_push($archivedgroups, $group);
   		}
		$allGroups[$group->groupid] = $group;
    }
?>

<script type="text/javascript">

	const allgroups = <?php echo json_encode($allGroups); ?>;

	function init() {
		$('dialogheader').insert('<?php echo $LNG->SPAM_GROUP_ADMIN_TITLE; ?>');
	}

	function getParentWindowHeight(){
		var viewportHeight = 900;
		if (window.opener.innerHeight) {
			viewportHeight = window.opener.innerHeight;
		} else if (window.opener.document.documentElement && document.documentElement.clientHeight) {
			viewportHeight = window.opener.document.documentElement.clientHeight;
		} else if (window.opener.document.body)  {
			viewportHeight = window.opener.document.body.clientHeight;
		}
		return viewportHeight;
	}

	function getParentWindowWidth(){
		var viewportWidth = 700;
		if (window.opener.innerHeight) {
			viewportWidth = window.opener.innerWidth;
		} else if (window.opener.document.documentElement && document.documentElement.clientHeight) {
			viewportWidth = window.opener.document.documentElement.clientWidth;
		} else if (window.opener.document.body)  {
			viewportWidth = window.opener.document.body.clientWidth;
		}
		return viewportWidth;
	}

	function viewSpamUserDetails(groupid) {
		var width = getParentWindowWidth()-20;
		var height = getParentWindowHeight()-20;

		loadDialog('user', URL_ROOT+"user.php?groupid="+groupid, width, height);
	}

	function viewSpamGroupDetails(groupid) {
		var width = getParentWindowWidth()-20;
		var height = getParentWindowHeight()-20;

		loadDialog('group', URL_ROOT+"group.php?groupid="+groupid, width, height);
	}

	function checkFormRestore(name) {
		var ans = confirm("<?php echo $LNG->SPAM_GROUP_ADMIN_RESTORE_CHECK_MESSAGE_PART1; ?>\n\n"+name+"?\n\n<?php echo $LNG->SPAM_GROUP_ADMIN_RESTORE_CHECK_MESSAGE_PART2; ?>\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function checkFormDelete(name) {
		var ans = confirm("<?php echo $LNG->SPAM_GROUP_ADMIN_DELETE_CHECK_MESSAGE_PART1; ?>\n\n"+name+"?\n\n<?php echo $LNG->SPAM_GROUP_ADMIN_DELETE_CHECK_MESSAGE_PART2; ?>\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function checkFormSuspend(name) {
		var ans = confirm("<?php echo $LNG->SPAM_GROUP_ADMIN_ARCHIVE_CHECK_MESSAGE; ?>\n\n"+name+"?\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function viewGroupTree(groupid, containerid, rootname) {

		// close any opened divs
		const divsArray = document.getElementsByName(rootname);
		for (let i=0; i<divsArray.length; i++) {
			if (divsArray[i].id !== containerid) {
				divsArray[i].style.display = 'none';
			}
		}

		var group = allgroups[groupid];

		const containerObj = document.getElementById(containerid);
		if (containerObj.style.display == 'block') {
			containerObj.style.display = 'none';
		} else {
			containerObj.style.display = 'block';
		}
		
		if (containerObj.innerHTML == "&nbsp;") {
			containerObj.innerHTML = "";

			if (group && group.children.length > 0) {			
				displayConnectionNodes(containerObj, group.children, parseInt(0), true, groupid+"tree");
			}					
		}
	}	

	window.onload = init;

</script>

<?php
if(!empty($errors)){
    echo "<div class='errors'>".$LNG->FORM_ERROR_MESSAGE.":<ul>";
    foreach ($errors as $error){
        echo "<li>".$error."</li>";
    }
    echo "</ul></div>";
}
?>

<div id="spamdiv" style="margin-left:10px;">

	<h2 style="margin-left:10px;"><?php echo $LNG->SPAM_GROUP_ADMIN_SPAM_TITLE; ?></h2>

    <div class="formrow">
        <div id="groups" class="forminput">
        <?php

			$count = (is_countable($groups)) ? count($groups) : 0;
        	if ($count == 0) {
				echo "<p>".$LNG->SPAM_GROUP_ADMIN_NONE_MESSAGE."</p>";
        	} else {
				echo "<table width='700' class='table' cellspacing='0' cellpadding='3' border='0' style='margin: 0px;'>";
				echo "<tr>";
				echo "<th width='50%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING1."</th>";
				echo "<th width='10%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='20%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING0."</th>";

				echo "</tr>";
				foreach($groups as $group){
					echo '<tr>';
					echo '<td style="font-size:11pt">';
					echo $group->name;
					echo '</td>';

					echo '<td>';
					echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_VIEW_HINT.'" class="active" style="font-size:10pt;" onclick="viewSpamGroupDetails(\''.$group->groupid.'\');">'.$LNG->SPAM_GROUP_ADMIN_VIEW_BUTTON.'</span>';
					//echo '<span class="active" style="font-size:10pt;" onclick="viewGroupTree(\''.$group->groupid.'\', \''.$group->groupid.'treediv1\', \'treediv1\');">'.$LNG->SPAM_GROUP_ADMIN_VIEW_BUTTON.'</span>';
					echo '</td>';

					echo '<td>';
					echo '<form id="second-'.$group->groupid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormRestore(\''.htmlspecialchars($group->name).'\');">';
					echo '<input type="hidden" id="groupid" name="groupid" value="'.$group->groupid.'" />';
					echo '<input type="hidden" id="restoregroup" name="restoregroup" value="" />';
					echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_RESTORE_HINT.'" class="active" onclick="if (checkFormRestore(\''.htmlspecialchars($group->name).'\')){ $(\'second-'.$group->groupid.'\').submit(); }" id="restorenode" name="restorenode">'.$LNG->SPAM_GROUP_ADMIN_RESTORE_BUTTON.'</a>';
					echo '</form>';
					echo '</td>';

					echo '<td>';
					echo '<form id="fourth-'.$group->groupid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormSuspend(\''.htmlspecialchars($group->name).'\');">';
					echo '<input type="hidden" id="groupid" name="groupid" value="'.$group->groupid.'" />';
					echo '<input type="hidden" id="archivegroup" name="archivegroup" value="" />';
					echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_ARCHIVE_HINT.'" class="active" onclick="if (checkFormSuspend(\''.htmlspecialchars($group->name).'\')) { $(\'fourth-'.$group->groupid.'\').submit(); }" id="archivegroup" name="archivegroup">'.$LNG->SPAM_GROUP_ADMIN_ARCHIVE_BUTTON.'</a>';
					echo '</form>';
					echo '</td>';

					echo '<td>';
					if (isset($group->reporter)) {
						echo '<span title="'.$LNG->SPAM_USER_ADMIN_VIEW_HINT.'" class="active" style="font-size:10pt;" onclick="viewSpamUserDetails(\''.$group->reporter->userid.'\');">'.$group->reporter->name.'</span>';
					} else {
						echo $LNG->CORE_UNKNOWN_USER_ERROR;
					}
					echo '</td>';

					// add the tree display area row
					echo '<tr><td colspan="6">';
					echo '<div id="'.$group->groupid.'treediv1" name="treediv1" style="display:none">&nbsp;</div>';
					echo '</td></tr>';
					
					echo '</tr>';
				}
				echo "</table>";
			}
        ?>
        </div>
   </div>

	<h2 style="margin-left:10px;margin-top:20px;"><?php echo $LNG->SPAM_GROUP_ADMIN_ARCHIVED_TITLE; ?></h2>

    <div class="formrow">
        <div id="archivedgroups" class="forminput">

        <?php

			$countu = 0;
			if (is_countable($archivedgroups)) {
				$countu = count($archivedgroups);
			}
        	if ($countu == 0) {
				echo "<p>".$LNG->SPAM_GROUP_ADMIN_NONE_ARCHIVED_MESSAGE."</p>";
        	} else {
				echo "<table width='700' class='table' cellspacing='0' cellpadding='3' border='0' style='margin: 0px;'>";
				echo "<tr>";
				echo "<th width='50%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING1."</th>";
				echo "<th width='10%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='20%'>".$LNG->SPAM_GROUP_ADMIN_TABLE_HEADING0."</th>";

				echo "</tr>";
				foreach($archivedgroups as $group){
					echo '<tr>';

					echo '<td style="font-size:11pt">';
					echo $group->name;
					echo '</td>';

					echo '<td>';
					//echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_VIEW_HINT.'" class="active" style="font-size:10pt;" onclick="viewSpamGroupDetails(\''.$group->groupid.'\');">'.$LNG->SPAM_GROUP_ADMIN_VIEW_BUTTON.'</a>';
					echo '<span class="active" style="font-size:10pt;" onclick="viewGroupTree(\''.$group->groupid.'\', \''.$group->groupid.'treediv\', \'treediv\');">'.$LNG->SPAM_GROUP_ADMIN_VIEW_BUTTON.'</span>';
					echo '</td>';

					echo '<td>';
					echo '<form id="second-'.$group->groupid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormRestore(\''.htmlspecialchars($group->name).'\');">';
					echo '<input type="hidden" id="groupid" name="groupid" value="'.$group->groupid.'" />';
					echo '<input type="hidden" id="restorearchivedgroup" name="restorearchivedgroup" value="" />';
					echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_RESTORE_HINT.'" class="active" onclick="if (checkFormRestore(\''.htmlspecialchars($group->name).'\')){ $(\'second-'.$group->groupid.'\').submit(); }" id="restorearchivedgroup" name="restorearchivedgroup">'.$LNG->SPAM_GROUP_ADMIN_RESTORE_BUTTON.'</a>';
					echo '</form>';
					echo '</td>';

					echo '<td>';
					echo $LNG->SPAM_GROUP_ADMIN_DELETE_BUTTON;
					//echo '<form id="third-'.$group->groupid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormDelete(\''.htmlspecialchars($group->name).'\');">';
					//echo '<input type="hidden" id="groupid" name="groupid" value="'.$group->groupid.'" />';
					//echo '<input type="hidden" id="deletegroup" name="deletegroup" value="" />';
					//echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_DELETE_HINT.'" class="active" onclick="if (checkFormDelete(\''.htmlspecialchars($group->name).'\')) { $(\'third-'.$group->groupid.'\').submit(); }" id="deletenode" name="deletenode">'.$LNG->SPAM_GROUP_ADMIN_DELETE_BUTTON.'</a>';
					//echo '</form>';
					echo '</td>';

					echo '<td>';
					if (isset($group->reporter)) {
						echo '<span title="'.$LNG->SPAM_USER_ADMIN_VIEW_HINT.'" class="active" style="font-size:10pt;" onclick="viewSpamUserDetails(\''.$group->reporter->userid.'\');">'.$group->reporter->name.'</span>';
					} else {
						echo $LNG->CORE_UNKNOWN_USER_ERROR;
					}
					echo '</td>';

					// add the tree display area row
					echo '<tr><td colspan="6">';
					echo '<div id="'.$group->groupid.'treediv" name="treediv" style="display:none">&nbsp;</div>';
					echo '</td></tr>';

					echo '</tr>';
				}
				echo "</table>";
			}
        ?>
        </div>
   </div>

    <div class="formrow" style="margin-top:20px;">
	<input class="btn btn-secondary" type="button" value="<?php echo $LNG->FORM_BUTTON_CLOSE; ?>" onclick="window.close();"/>
    </div>

</div>


<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
?>
