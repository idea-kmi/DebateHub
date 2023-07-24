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

$page = optional_param("page","home",PARAM_ALPHANUM);
$nodeid = required_param("nodeid",PARAM_ALPHANUMEXT);
$node = getNode($nodeid);

if($node instanceof Error){
	include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
	echo "<h1>Issue not found</h1>";
	include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
	die;
}

$dashboardService = "http://cidashboard.net/ui/dashboard/index.php?";
//5,6 = scatterplots
$vises = "11,1,2,3,4,7,8,9,10,12";
$lang = $CFG->language;
$dashboardtitle = '';
$dataurl=rawurlencode($CFG->homeAddress.'api/views/'.$nodeid);

$dashboardurl = $dashboardService."&page=".rawurlencode($page)."&width=1000&height=1000&vis=".$vises."&lang=".$lang."&title=".$dashboardtitle."&url=".$dataurl;

include_once($HUB_FLM->getCodeDirPath("ui/headerstatsexternal.php"));
?>
<center>
	<h1 style="padding-top:0px;margin-top:0px;"><?php echo $LNG->STATS_DEBATE_TITLE.$node->name; ?><a href="<?php echo $CFG->homeAddress.'explore.php?id='.$nodeid; ?>"><img src="<?php echo $HUB_FLM->getImagePath('arrow-up2.png'); ?>" style="padding-left:3px;vertical-align:middle" border="0" /></a></h1>
</center>

<iframe width="1000px;" height="1000px;" src="<?php echo $dashboardurl; ?>" style="overflow-y:auto;overflow-x:hidden" scrolling="no" frameborder="0"></iframe>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footerstats.php"));
?>