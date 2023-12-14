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

    include_once($HUB_FLM->getCodeDirPath("ui/headeradmin.php"));

    if($USER == null || $USER->getIsAdmin() == "N"){
        //reject user
        echo $LNG->ADMIN_NOT_ADMINISTRATOR_MESSAGE;
        include_once($HUB_FLM->getCodeDirPath("ui/footeradmin.php"));
        die;
    }
?>

<div class="container-fluid">
	<div class="row p-4">		
		<div class="col">
			<div class="d-flex flex-wrap w-100 gap-2 border-bottom mb-3 pb-4">
				<a href="<?= $CFG->homeAddress ?>ui/admin/index.php" class="btn btn-admin active">Admin Dashboard</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/stats" class="btn btn-admin">Analytics</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/userregistration.php" class="btn btn-admin">Users</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/registrationmanager.php" class="btn btn-admin">Registration requests</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/spammanagergroups.php" class="btn btn-admin">Reported groups</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/spammanager.php" class="btn btn-admin">Reported items</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/spammanagerusers.php" class="btn btn-admin">Reported users</a>
				<a href="<?= $CFG->homeAddress ?>ui/admin/newsmanager.php" class="btn btn-admin">Manage news</a>
			</div>

			<h1 class="mb-3"><?php echo $LNG->ADMIN_TITLE; ?></h1>

			<div class="d-flex">
				<div class="w-100 p-4 ps-0">
					sdf
				</div>
			</div>


			

			<div class="d-none" style="margin-right:10px;" onclick="" onmouseover="" onmouseout="" title="">
				<div class="" onclick="" onmouseover="" onmouseout="">
					<table style="text-align:center;font-weight:bold;width:100%;height:100%" class="themebutton">
						<tr>
							<td valign="middle">TEST BUTTON</td>
						</tr>
					</table>
				</div>
			</div>

			<?php
				include($HUB_FLM->getCodeDirPath('ui/admin/menulist.php'));
			?>


		</div>
	</div>
</div>

<?php
	include_once($HUB_FLM->getCodeDirPath("ui/footeradmin.php"));
?>

