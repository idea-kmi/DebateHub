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
	<div class="row p-4 pt-0">
		<div class="col">

			<h1 class="mb-3"><?php echo $LNG->ADMIN_TITLE; ?></h1>

			<div class="d-flex">
				<div class="w-100 p-4 ps-0">

				<?php
						/***** TOTAL USERS ****/
						$time = 'months';
						$startdate = $CFG->START_DATE;
						$startdate = strtotime( 'first day of ' , $startdate);

						$dates = new DateTime();
						$dates->setTimestamp($startdate);
						$interval = date_create('now')->diff( $dates );

						$count = $interval->m;
						$years = $interval->y;
						if (isset($years) && $years > 0) {
							$count += ($interval->y * 12);
						}
						$count = $count+1; //(to get it to this month too);
						$grandtotal = 0;
						for ($i=0; $i<$count; $i++) {

							if ($i < 1) {
								$mintime= $startdate;
							} else {
								$mintime= $maxtime;
							}

							$maxtime = strtotime( '+1 month', $mintime);

							$monthlytotal = getRegisteredUserCount($mintime, $maxtime);
							$grandtotal += $monthlytotal;
						}


						echo '<div class="my-3">';
						echo '<p>'.$LNG->USERS_NAME.' = '.$grandtotal.'</p>';
						echo '</div>';

						$allGroups = getGroupsByGlobal(0,-1,'date','ASC');
						echo '<div class="my-3">';

						$countgroups = 0;
						if (is_countable($allGroups->groups)) {
							$countgroups = count($allGroups->groups);
						}

						echo '<p>'.$LNG->GROUPS_NAME.' = '.$countgroups.'</p>';
						echo '</div>';

						$grandtotal1 = 0;
						$categoryArray = array();

						$icount = getNodeCreationCount("Issue",$startdate);
						$categoryArray[$LNG->ISSUES_NAME] = $icount;
						$grandtotal1 += $icount;

						$icount = getNodeCreationCount('Solution',$startdate);
						$categoryArray[$LNG->SOLUTIONS_NAME] = $icount;
						$grandtotal1 += $icount;

						$icount = getNodeCreationCount('Pro',$startdate);
						$categoryArray[$LNG->PROS_NAME] = $icount;
						$grandtotal1 += $icount;

						$icount = getNodeCreationCount('Con',$startdate);
						$categoryArray[$LNG->CONS_NAME] = $icount;
						$grandtotal1 += $icount;

						echo '<div class="mt-3">';
						echo '<h4 class="fw-bold">'.$LNG->ADMIN_STATS_TAB_IDEAS.'</h4>';
						echo '<table cellpadding="3" class="table table-sm table-borderless">';

						foreach( $categoryArray as $key => $value) {
							echo '<tr><td><span>'.$key.'</span></td><td class="text-end"><span>'.$value.'</span</td></tr>';
						}

						echo '<tr><td colspan="2"><hr class="hrline" /></td></tr>';
						echo '<tr><td><span class="hometext">'.$LNG->ADMIN_STATS_IDEAS_TOTAL_LABEL.'</span></td><td class="text-end"><span class="hometext">'.$grandtotal1.'</span</td></tr>';
						echo '</table></div>';

					?>				

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

