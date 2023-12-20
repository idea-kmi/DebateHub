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

	function encodeURIComponent($str) {
		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
		return strtr(rawurlencode($str), $revert);
	}

    if(isset($_POST["deletenode"])){
		$nodeid = optional_param("nodeid","",PARAM_ALPHANUMEXT);
    	if ($nodeid != "") {
    		$node = new CNode($nodeid);
	   		$node = $node->delete();
    	} else {
            array_push($errors,$LNG->SPAM_ADMIN_ID_ERROR);
    	}
	} else if(isset($_POST["archivenode"])){
		$nodeid = optional_param("nodeid","",PARAM_ALPHANUMEXT);
		//echo "ARCHIVING: ".$nodeid;
		archiveNodeAndChildren($nodeid);
 
    } else if(isset($_POST["restorenode"])){
		$nodeid = optional_param("nodeid","",PARAM_ALPHANUMEXT);
    	if ($nodeid != "") {
    		$node = new CNode($nodeid);
	   		$node = $node->updateStatus($CFG->STATUS_ACTIVE);
    	} else {
            array_push($errors,$LNG->SPAM_ADMIN_ID_ERROR);
    	}   
	} else if(isset($_POST["restorenodearchive"])){
		$nodeid = optional_param("nodeid","",PARAM_ALPHANUMEXT);
		//echo "RESTORING: ".$nodeid;
		restoreArchivedNodeAndChildren($nodeid);
	}

	$allNodes = array();

	$ns = getNodesByStatus($CFG->STATUS_REPORTED, 0,-1,'name','ASC','long');
    $nodes = $ns->nodes;

	$count = (is_countable($nodes)) ? count($nodes) : 0;
	$childnodes = [];	
    for ($i=0; $i<$count;$i++) {
    	$node = $nodes[$i];
		$node->children = loadDebateChildNodes($node, $CFG->STATUS_ACTIVE, $childnodes);
	   	$reporterid = getSpamReporter($node->nodeid);
    	if ($reporterid != false) {
    		$reporter = new User($reporterid);
    		$reporter = $reporter->load();
    		$node->reporter = $reporter;
			$node->istop = true;	// only top if it was the reported item
    	}
		$allNodes[$node->nodeid] = $node;
    }

	$ns2 = getNodesByStatus($CFG->STATUS_ARCHIVED, 0,-1,'name','ASC','long');
    $nodesarchivedinitial = $ns2->nodes;

	$nodesarchived = [];
	$count2 = (is_countable($nodesarchivedinitial)) ? count($nodesarchivedinitial) : 0;
    for ($i=0; $i<$count2;$i++) {
    	$node = $nodesarchivedinitial[$i];
   		$reporterid = getSpamReporter($node->nodeid);
   		if ($reporterid != false) {
    		$reporter = new User($reporterid);
    		$reporter = $reporter->load();
    		$node->reporter = $reporter;
			$node->children = loadDebateChildNodes($node, $CFG->STATUS_ARCHIVED, $childnodes);
    	}
 		$allNodes[$node->nodeid] = $node;
    }

	// only hold top level archived nodes that have a reporter 
	// and are not children of another item also archived
	// will this cover everything?
	for ($i=0; $i<$count2;$i++) {
    	$node = $nodesarchivedinitial[$i];
		if (isset($node->reporter) && !in_array($node->nodeid, $childnodes) ) {
 			$node->istop = true; // only top if it was the reported item
			array_push($nodesarchived, $node);
    	}
    }	
?>

<script type="text/javascript">

	const allnodes = <?php echo json_encode($allNodes); ?>;

	function init() {
		$('dialogheader').insert('<?php echo $LNG->SPAM_ADMIN_TITLE; ?>');
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

	function viewSpamUserDetails(userid) {
		var width = getParentWindowWidth()-20;
		var height = getParentWindowHeight()-20;

		loadDialog('user', URL_ROOT+"user.php?userid="+userid, width, height);
	}

	function viewSpamItemDetails(nodeid, nodetype) {
		var width = getParentWindowWidth()-20;
		var height = getParentWindowHeight()-20;

		loadDialog('details', URL_ROOT+"explore.php?id="+nodeid, width, height);
	}

	function checkFormRestore(name) {
		var ans = confirm("<?php echo $LNG->SPAM_ADMIN_RESTORE_CHECK_MESSAGE; ?>\n\n"+name+"\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function checkFormArchive(name) {
		var ans = confirm("<?php echo $LNG->SPAM_ADMIN_ARCHIVE_CHECK_MESSAGE; ?>\n\n"+name+"\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function checkFormDelete(name) {
		var ans = confirm("<?php echo $LNG->SPAM_ADMIN_DELETE_CHECK_MESSAGE; ?>\n\n"+name+"\n\n");
		if (ans){
			return true;
		} else {
			return false;
		}
	}

	function viewItemTree(nodeid, nodetype, containerid, rootname) {

		// close any opened divs
		const divsArray = document.getElementsByName(rootname);
		for (let i=0; i<divsArray.length; i++) {
			if (divsArray[i].id !== containerid) {
				divsArray[i].style.display = 'none';
			}
		}

		var node = allnodes[nodeid];

		const containerObj = document.getElementById(containerid);
		if (containerObj.style.display == 'block') {
			containerObj.style.display = 'none';
		} else {
			containerObj.style.display = 'block';
		}
		
		if (containerObj.innerHTML == "&nbsp;") {
			containerObj.innerHTML = "";
			displayConnectionNodes(containerObj, [node], parseInt(0), true, nodeid+"tree");
		}
	}
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

    <h2 style="margin-left:10px;"><?php echo $LNG->SPAM_ADMIN_SPAM_TITLE; ?></h2>

    <div class="formrow">
        <div id="nodes" class="forminput">

        <?php
			$count = 0;
			if (is_countable($nodes)) {
				$count = count($nodes);
			}
        	if ($count == 0) {
				echo "<p>".$LNG->SPAM_ADMIN_NONE_MESSAGE."</p>";
        	} else {
				echo "<table width='700' class='table' cellspacing='0' cellpadding='3' border='0' style='margin: 0px;'>";
				echo "<tr>";
				echo "<th width='40%'>".$LNG->SPAM_ADMIN_TABLE_HEADING1."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING3."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='20%'>".$LNG->SPAM_ADMIN_TABLE_HEADING0."</th>";

				echo "</tr>";
				foreach($nodes as $node){

					echo '<tr>';

					echo '<td style="font-size:11pt">';
					echo $node->name;
					echo '</td>';

					echo '<td style="font-size:11pt">';
					$nodetypename = '';
					if ($node->role->name == 'Issue') {
						$nodetypename = $LNG->DEBATE_NAME; //default for type is Issue - I want to show debate
					} else {
						$nodetypename = getNodeTypeText($node->role->name, false);
					}
					echo $nodetypename;
					echo '</td>';

					echo '<td>';
					echo '<span class="active" style="font-size:10pt;" onclick="viewSpamItemDetails(\''.$node->nodeid.'\', \''.$node->role->name.'\');">'.$LNG->SPAM_ADMIN_VIEW_BUTTON.'</span>';
					//echo '<span class="active" style="font-size:10pt;" onclick="viewItemTree(\''.$node->nodeid.'\', \''.$node->role->name.'\', \''.$node->nodeid.'treediv1\', \'treediv1\');">'.$LNG->SPAM_ADMIN_VIEW_BUTTON.'</span>';
					echo '</td>';

					echo '<td>';
					echo '<form id="second-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormRestore(\''.htmlspecialchars($node->name).'\');">';
					echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
					echo '<input type="hidden" id="restorenode" name="restorenode" value="" />';
					echo '<span class="active" onclick="if (checkFormRestore(\''.htmlspecialchars($node->name).'\')){ $(\'second-'.$node->nodeid.'\').submit(); }" id="restorenode" name="restorenode">'.$LNG->SPAM_ADMIN_RESTORE_BUTTON.'</a>';
					//echo '<input type="submit" style="font-size:10pt;border:none;padding:0px;background:transparent" class="active" id="restorenode" name="restorenode" value="'.$LNG->SPAM_ADMIN_RESTORE_BUTTON.'"/>';
					echo '</form>';
					echo '</td>';

					echo '<td>';
					echo '<form id="third-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormArchive(\''.htmlspecialchars($node->name).'\');">';
					echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
					echo '<input type="hidden" id="archivenode" name="archivenode" value="" />';
					echo '<span class="active" onclick="if (checkFormArchive(\''.htmlspecialchars($node->name).'\')) { $(\'third-'.$node->nodeid.'\').submit(); }" id="archivenode" name="archivenode">'.$LNG->SPAM_ADMIN_ARCHIVE_BUTTON.'</a>';
					echo '</form>';
					echo '</td>';

					echo '<td>';
					if (isset($node->reporter)) {
						echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_VIEW_HINT.'" class="active" style="font-size:10pt;" onclick="viewSpamUserDetails(\''.$node->reporter->userid.'\');">'.$node->reporter->name.'</span>';
					} else {
						echo $LNG->CORE_UNKNOWN_GROUP_ERROR;
					}
					echo '</td>';

					echo '</tr>';

					// add the tree display area row
					echo '<tr><td colspan="6">';
					echo '<div id="'.$node->nodeid.'treediv1" name="treediv1" style="display:none">&nbsp;</div>';
					echo '</td></tr>';

				}
				echo "</table>";
			}
        ?>
        </div>
    </div>

    <h2 style="margin-left:10px;"><?php echo $LNG->SPAM_ADMIN_ARCHIVE_TITLE; ?></h2>

	<div class="formrow">
		<div id="nodesarchived" class="forminput">

		<?php
			$count = 0;
			if (is_countable($nodesarchived)) {
				$count = count($nodesarchived);
			}
			if ($count == 0) {
				echo "<p>".$LNG->SPAM_ADMIN_NONE_ARCHIVED_MESSAGE."</p>";
			} else {
				echo "<table width='700' class='table' cellspacing='0' cellpadding='3' border='0' style='margin: 0px;'>";
				echo "<tr>";
				echo "<th width='40%'>".$LNG->SPAM_ADMIN_TABLE_HEADING1."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING3."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='10%'>".$LNG->SPAM_ADMIN_TABLE_HEADING2."</th>";
				echo "<th width='20%'>".$LNG->SPAM_ADMIN_TABLE_HEADING0."</th>";

				echo "</tr>";
				foreach($nodesarchived as $node) {
					echo '<tr>';

					echo '<td style="font-size:11pt">';
					echo $node->name;
					echo '</td>';

					echo '<td style="font-size:11pt">';
					$nodetypename = '';
					if ($node->role->name == 'Issue') {
						$nodetypename = $LNG->DEBATE_NAME; //default for type is Issue - I want to show debate
					} else {
						$nodetypename = getNodeTypeText($node->role->name, false);
					}
					echo $nodetypename;
					echo '</td>';

					echo '<td>';
					echo '<span class="active" style="font-size:10pt;" onclick="viewItemTree(\''.$node->nodeid.'\', \''.$node->role->name.'\', \''.$node->nodeid.'treediv\', \'treediv\');">'.$LNG->SPAM_ADMIN_VIEW_BUTTON.'</span>';
					echo '</td>';

					echo '<td>';
					echo '<form id="second-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormRestore(\''.htmlspecialchars($node->name).'\');">';
					echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
					echo '<input type="hidden" id="restorenodearchive" name="restorenodearchive" value="" />';
					echo '<span class="active" onclick="if (checkFormRestore(\''.htmlspecialchars($node->name).'\')){ $(\'second-'.$node->nodeid.'\').submit(); }" id="restorenode" name="restorenode">'.$LNG->SPAM_ADMIN_RESTORE_BUTTON.'</a>';
					//echo '<input type="submit" style="font-size:10pt;border:none;padding:0px;background:transparent" class="active" id="restorenode" name="restorenode" value="'.$LNG->SPAM_ADMIN_RESTORE_BUTTON.'"/>';
					echo '</form>';
					echo '</td>';

					echo '<td>';
					echo $LNG->SPAM_ADMIN_DELETE_BUTTON;
					//echo '<form id="third-'.$node->nodeid.'" action="" enctype="multipart/form-data" method="post" onsubmit="return checkFormDelete(\''.htmlspecialchars($node->name).'\');">';
					//echo '<input type="hidden" id="nodeid" name="nodeid" value="'.$node->nodeid.'" />';
					//echo '<input type="hidden" id="deletenode" name="deletenode" value="" />';
					//echo '<span class="active" onclick="if (checkFormDelete(\''.htmlspecialchars($node->name).'\')) { $(\'third-'.$node->nodeid.'\').submit(); }" id="deletenode" name="deletenode">'.$LNG->SPAM_ADMIN_DELETE_BUTTON.'</a>';
					//echo '</form>';
					echo '</td>';

					echo '<td>';
					if (isset($node->reporter)) {
						echo '<span title="'.$LNG->SPAM_GROUP_ADMIN_VIEW_HINT.'" class="active" style="font-size:10pt;" onclick="viewSpamUserDetails(\''.$node->reporter->userid.'\');">'.$node->reporter->name.'</span>';
					} else {
						echo $LNG->CORE_UNKNOWN_USER_ERROR;
					}
					echo '</td>';

					echo '</tr>';

					// add the tree display area row
					echo '<tr><td colspan="6">';
					echo '<div id="'.$node->nodeid.'treediv" name="treediv" style="display:none">&nbsp;</div>';
					echo '</td></tr>';

				}
				echo "</table>";
			}
		?>
		</div>
	</div>

    <div class="formrow" style="margin-top:10px;">
	<input class="btn btn-secondary" type="button" value="<?php echo $LNG->FORM_BUTTON_CLOSE; ?>" onclick="window.close();"/>
    </div>

</div>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
?>
