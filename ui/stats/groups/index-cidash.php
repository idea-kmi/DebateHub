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
	checkDashboardAccess('GROUP');
	include_once($HUB_FLM->getCodeDirPath("core/formats/cipher.php"));

	$groupid = required_param("groupid",PARAM_ALPHANUMEXT);
	$group = getGroup($groupid);

	if($group instanceof Hub_Error){
		include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
		echo "<h1>Group not found</h1>";
		include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
		die;
	}

	$dashboardService = "https://cidashboard.net/ui/visualisations/index.php?";
	//5,6 = scatterplots
	//12 Attention Map - broken at present
	$vises = "11,1,2,3,4,7,8,9,10";
	$lang = $CFG->language;
	$dashboardtitle = '';

	$cipher;
	$salt = openssl_random_pseudo_bytes(32);
	$cipher = new Cipher($salt);
	$obfuscationkey = $cipher->getKey();
	$obfuscationiv = $cipher->getIV();

	$dataurl = $CFG->homeAddress.'api/conversations/'.$groupid;
	$reply = createObfuscationEntry($obfuscationkey, $obfuscationiv, $dataurl);
	$finaldataurl = $dataurl.'/?id='.$reply['dataid'];
	$userurl = $CFG->homeAddress.'api/unobfuscatedusers/?id='.$reply['obfuscationid'];

	$dashboardurl = $dashboardService."&timeout=300&width=1000&height=1000&vis=".$vises."&lang=".$lang."&title=".$dashboardtitle."&url=".$finaldataurl."&userurl=".$userurl;

	include_once($HUB_FLM->getCodeDirPath("ui/headerstatsexternal.php"));
?>

<div class="d-flex flex-column">
	<h1 class="text-center">
		<?php echo $LNG->STATS_GROUP_TITLE.$group->name; ?>	
		<a href="<?php echo $CFG->homeAddress.'group.php?groupid='.$groupid; ?>" class="fw-normal fs-6">[Go back]</a>
	</h1>

	<iframe width="1000px;" height="1000px;" src="<?php echo $dashboardurl; ?>" style="overflow-y:auto;overflow-x:hidden" scrolling="no" frameborder="0"></iframe>

</div>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>