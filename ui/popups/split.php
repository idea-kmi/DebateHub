<?php
/********************************************************************************
 *                                                                              *
 *  (c) Copyright 2015 - 2025 The Open University UK                            *
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

 	$debateid = required_param("debateid",PARAM_ALPHANUMEXT);
 	$nodeid = required_param("nodeid",PARAM_ALPHANUMEXT);
 	$groupid = optional_param("groupid","",PARAM_ALPHANUMEXT);

	$node = getNode($nodeid);
	$text="";
	if (!$node instanceof Hub_Error) {
		if ($node->description == "") {
			$text = $node->name;
		} else {
			$text = $node->name."\n\n".$node->description;
		}
	}

 	$ideanamearray = optional_param("ideanamearray","",PARAM_TEXT);
	$ideadescarray = optional_param("ideadescarray","",PARAM_HTML);

    if( isset($_POST["publish"]) ) {
		$countideas = 0;
		if (is_countable($ideanamearray)) {
			$countideas = count($ideanamearray);
		}
        if ($countideas <= 1 || ($ideanamearray[0] == "" || $ideanamearray[1] == "") ){
            array_push($errors,$LNG->$LNG->FORM_SPLIT_IDEA_ERROR);
        }

        if(empty($errors)){
			$currentUser = $USER;

			$orinode = getNode($nodeid);
			$r = getRoleByName($orinode->role->name);
			$roleid = $r->roleid;

			$i = 0;
			$count = 0;
			if (is_countable($ideanamearray)) {
				$count = count($ideanamearray);
			}
			for($i=0; $i<$count;$i++) {
				$name = $ideanamearray[$i];
				$desc = $ideadescarray[$i];

				$newconn = addNodeAndConnect($name,$desc,'Solution',$debateid,$CFG->LINK_SOLUTION_ISSUE,'from',$groupid="",'N');
				$newnode = $newconn->from;

				// CONNECT NEW NODE TO SELECT NODES
				$lt2 = getLinkTypeByLabel($CFG->LINK_BUILT_FROM);
				$linkTypeBuiltFrom = $lt2->linktypeid;

				$connection = addConnection($newnode->nodeid, $newnode->role->roleid, $linkTypeBuiltFrom, $orinode->nodeid, $roleid, "N", "");
				// add to group
				if (!$connection instanceof Hub_Error && isset($groupid) && $groupid != "") {
					addGroupToConnection($connection->connid,$groupid);
				}
			}

			// need to become the owner of the node you are editing the status of
			//$USER = $orinode->users[0];
			$orinode->updateStatus($CFG->STATUS_RETIRED);

			echo '<script type=\'text/javascript\'>';
			//echo 'window.opener.location.href = "'.$CFG->homeAddress.'user.php?id='.$USER->userid.'";';
			echo "	  window.opener.location.reload(true);";

			echo 'window.close();';
			echo '</script>';
			include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
			die;

		}
    } else {
    	// want to start with two ideas minimum for a split.
    	$ideanamearray[0] = "";
    	$ideanamearray[1] = "";
    	$ideadescarray[0] = "";
    	$ideadescarray[1] = "";
    }

	include_once($HUB_FLM->getCodeDirPath("ui/popuplib.php"));

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

var noIdeas = <?php $count = 0; if (is_countable($ideanamearray)) { $count = count($ideanamearray); } echo $count; ?>;

function init() {
   	$('dialogheader').insert("Split this idea into two or more ideas.");
}

function checkForm() {

	var idea0 = ($('ideaname-0').value).trim();
	var idea1 = ($('ideaname-1').value).trim();

	if (idea0 == "" ||  idea1 == ""){
		alert("<?php echo $LNG->FORM_SPLIT_IDEA_ERROR; ?>");
		return false;
	}

    $('issueform').style.cursor = 'wait';

	return true;
}

window.onload = init;

</script>

<table style="width:100%">
	<tr>
		<td>
			<h2 style="padding-left:10px;">Idea to Split</h2>
			<div class="" id="textareadiv" style="padding:5px;border:1px solid #E8E8E8;clear:both;float:left;width:700px;height:padding:5px;margin-left:5px;min-height:50px;max-height:300px;overflow-y:auto;">
				<?php echo( $text ); ?>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<form style="padding:5px;padding-top:0px; margin-top:0px;" id="ideaform" name="ideaform" action="" enctype="multipart/form-data" method="post" onsubmit="return checkForm();">

				<input type="hidden" id="nodeid" name="nodeid" value="<?php echo $nodeid; ?>" />
				<input type="hidden" id="debateid" name="debateid" value="<?php echo $debateid; ?>" />
				<input type="hidden" id="groupid" name="groupid" value="<?php echo $groupid; ?>" />

				<div>
					<?php insertIdeas(); ?>

					<div class="hgrformrow">
						<input style="float:left;margin-right:10px;" class="submit" type="submit" value="<?php echo $LNG->FORM_BUTTON_PUBLISH; ?>" id="publish" name="publish">
						<input style="float:left; margin-left:5px;" type="button" value="<?php echo $LNG->FORM_BUTTON_CANCEL; ?>" onclick="window.close();"/>
					</div>
				</div>
			</form>


		</td>
	</tr>
</table>

<?php
    include_once($HUB_FLM->getCodeDirPath("ui/footerdialog.php"));
?>