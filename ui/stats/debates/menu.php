<?php
	/********************************************************************************
	 *                                                                              *
	 *  (c) Copyright 2015 - 2024 The Open University UK                            *
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

	$page = optional_param("page","home",PARAM_ALPHANUM);
	$nodeid = required_param("nodeid",PARAM_ALPHANUMEXT);
	$node = getNode($nodeid);
	$pageindex = 0;

	$userid = "";
	if (isset($USER->userid)) {
		$userid = $USER->userid;
	}
	auditDashboardView($userid, $nodeid, $page);

	include_once('visdata.php');
?>

<h1 class="text-center">
	<?php
		if (isset($node->name)) {
			echo $LNG->STATS_DEBATE_TITLE.$node->name;
		} else {
			echo $LNG->STATS_DEBATE_TITLE."Error";
		}
	?>
	<a class="fw-normal fs-6" href="<?php echo $CFG->homeAddress.'explore.php?id='.$nodeid; ?>">[<?php echo $LNG->STATS_GO_BACK; ?>]</a>
</h1>

<div id="tabber" class="tabber-user my-4">
	<ul id="tabs" class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link <?php if ($page == "home") { echo 'active'; } else { echo 'unselected'; } ?>" href="index.php?page=home&nodeid=<?php echo $nodeid; ?>"><span class="nav-item"><?php echo $LNG->TAB_HOME; ?></span></a>
		</li>

		<?php
			$count = 0;
			if (is_countable($sequence)) {
				$count = count($sequence);
			}
			for ($i=0; $i<$count; $i++) {
				$next = $sequence[$i];
				$nextitem = $dashboarddata[$next-1];
				$nextpage = $nextitem[6];

				echo '<li class="nav-item">';
				echo '<a class="nav-link';
				if ($page === $nextpage) {
					$pageindex = $next-1;
					echo ' active';
				} else {
					echo ' unselected';
				}
				echo '" href="'.$nextitem[4].'page='.$nextpage.'&nodeid='.$nodeid.'"><span class="nav-item">'.$nextitem[0].'</span></a>';
				echo '</li>';
			}
		?>
	</ul>
</div>

