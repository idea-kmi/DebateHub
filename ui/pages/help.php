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
    include_once("../../config.php");

    $me = substr($_SERVER["PHP_SELF"], 1); // remove initial '/'
    if ($HUB_FLM->hasCustomVersion($me)) {
    	$path = $HUB_FLM->getCodeDirPath($me);
    	include_once($path);
		die;
	}

    include_once($HUB_FLM->getCodeDirPath("ui/header.php"));
?>
<div class="mt-4 px-4 ">
	<h1><?php echo $LNG->PAGE_HELP_TITLE; ?></h1>
	<div class="row">
		<div class="col text-center p-3">
			<div class="h5 movietitle"><?php echo $LNG->HELP_MOVIES_TITLE_INTRO;?><span class="text-secondary fs-6 ms-2">(1m 48s)</span></div>
			<video poster="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-intro.png" style="border:2px solid #E8E8E8" width="480px" height="360px" autobuffer="autobuffer" controls="controls">
			<source src="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-intro.mp4" type="video/mp4;" codecs="avc1.42E01E, mp4a.40.2">
			</video>
		</div>
		<div class="col text-center p-3">
			<div class="h5 movietitle"><?php echo $LNG->HELP_MOVIES_TITLE_GROUPS;?><span class="text-secondary fs-6 ms-2">(2m 57s)</span></div>
			<video poster="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-groups.png" style="border:2px solid #E8E8E8" width="480px" height="360px" autobuffer="autobuffer" controls="controls">
			<source src="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-groups.mp4" type="video/mp4;" codecs="avc1.42E01E, mp4a.40.2">
			</video>
		</div>
		<div class="col text-center p-3">
			<div class="h5 movietitle"><?php echo $LNG->HELP_MOVIES_TITLE_DEBATES;?><span class="text-secondary fs-6 ms-2">(15m 53s)</span></div>
			<video poster="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-debates.png" style="border:2px solid #E8E8E8" width="480px" height="360px" autobuffer="autobuffer" controls="controls">
			<source src="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-debates.mp4" type="video/mp4;" codecs="avc1.42E01E, mp4a.40.2">
			</video>
		</div>
		<div class="col text-center p-3">
			<div class="h5 movietitle"><?php echo $LNG->HELP_MOVIES_TITLE_MYHUB;?><span class="text-secondary fs-6 ms-2">(6m 19s)</span></div>
			<video poster="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-myhub.png" style="border:2px solid #E8E8E8" width="480px" height="360px" autobuffer="autobuffer" controls="controls">
			<source src="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-myhub.mp4" type=video/mp4;" codecs="avc1.42E01E, mp4a.40.2">
			</video>
		</div>
		<div class="col text-center p-3">
			<div class="h5 movietitle"><?php echo $LNG->HELP_MOVIES_TITLE_SEARCHES;?><span class="text-secondary fs-6 ms-2">(2m 03s)</span></div>
			<video poster="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-searching.png" style="border:2px solid #E8E8E8" width="480px" height="360px" autobuffer="autobuffer" controls="controls">
			<source src="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-searching.mp4" type="video/mp4;" codecs="avc1.42E01E, mp4a.40.2">
			</video>
		</div>
		<div class="col text-center p-3">
			<div class="h5 movietitle"><?php echo $LNG->HELP_MOVIES_TITLE_DASHBOARD;?><span class="text-secondary fs-6 ms-2">(18m 42s)</span></div>
			<video poster="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-dashboards.png" style="border:2px solid #E8E8E8" width="480px" height="360px" autobuffer="autobuffer" controls="controls">
			<source src="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-dashboards.mp4" type="video/mp4;" codecs="avc1.42E01E, mp4a.40.2">
			</video>
		</div>
		<div class="col text-center p-3">
			<div class="h5 movietitle"><?php echo $LNG->HELP_MOVIES_TITLE_MODERATORS;?><span class="text-secondary fs-6 ms-2">(11m 55s)</span></div>
			<video poster="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-moderators.png" style="border:2px solid #E8E8E8" width="480px" height="360px" autobuffer="autobuffer" controls="controls">
			<source src="<?php echo $CFG->homeAddress; ?>ui/movies/debatehub-moderators.mp4" type="video/mp4;" codecs="avc1.42E01E, mp4a.40.2">
			</video>
		</div>
	</div>
</div>

<?php
  	include_once($HUB_FLM->getCodeDirPath("ui/footer.php"));
?>