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
//if (isset($_SESSION['embedded']) && $_SESSION['embedded']) {
//    include_once($HUB_FLM->getCodeDirPath("ui/headerembed.php"));
//    return;
//}
?>
<!DOCTYPE html>
<html lang="<?php echo $CFG->language; ?>">
	<head>
		<?php
			if ($CFG->GOOGLE_ANALYTICS_ON) {
				include_once($HUB_FLM->getCodeDirPath("ui/analyticstracking.php"));
			}
		?>
        <meta http-equiv="Content-Type" content="text/html" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<title><?php echo $CFG->SITE_TITLE; ?></title>

		<link rel="icon" href="<?php echo $HUB_FLM->getImagePath("favicon.ico"); ?>" type="images/x-icon" />

        <link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("bootstrap.css"); ?>" type="text/css" />
        <link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("all.css"); ?>" type="text/css" />
		<link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("style.css"); ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("tabber.css"); ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("node.css"); ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("dialogstyle.css"); ?>" type="text/css" media="screen" />
		<link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("stylecustom.css"); ?>" type="text/css" media="screen" />


		<script src="<?php echo $HUB_FLM->getCodeWebPath('ui/util.js.php'); ?>" type="text/javascript"></script>
		<script src="<?php echo $HUB_FLM->getCodeWebPath('ui/popuputil.js.php'); ?>" type="text/javascript"></script>
		<script src="<?php echo $HUB_FLM->getCodeWebPath('ui/node.js.php'); ?>" type="text/javascript"></script>

		<script src="<?php echo $CFG->homeAddress; ?>ui/lib/ckeditor/ckeditor.js" type="text/javascript"></script>
		<script src="<?php echo $CFG->homeAddress; ?>ui/lib/prototype.js" type="text/javascript"></script>
		<script src="<?php echo $CFG->homeAddress; ?>ui/lib/dateformat.js" type="text/javascript"></script>

        <script src="<?php echo $HUB_FLM->getCodeWebPath('ui/lib/bootstrap/bootstrap.bundle.min.js'); ?>" type="text/javascript"></script>

		<script type="text/javascript">
			function init(){
				document.getElementById('cookie-policy-link').focus();
			}
			window.onload = init;
		</script>

		<?php
			$custom = $HUB_FLM->getCodeDirPath("ui/headerloginCustom.php");
			if (file_exists($custom)) {
				include_once($custom);
			}
			global $HEADER,$BODY_ATT, $CFG;
			if(is_array($HEADER)){
				foreach($HEADER as $header){
					echo $header;
				}
			}
		?>
	</head>
	<body>
		<div class="alert alert-dark alert-dismissible fade show m-0 fixed-bottom" role="alert" id="cookieConsent" style="display: none;">
			<div style="display: flex; align-items: center; flex-direction: column;">
				We use essential cookies to handle sessions and logins, and Google Analytics cookies to gather data on how you use this site.<br/>
				<div>This data is extremely valuable for our research and helps us improve our analysis.</div>				
				<a id="cookie-policy-link" style="margin-top:5px;" href="<?php print($CFG->homeAddress);?>ui/pages/cookies.php">Read our cookie policy</a>
				<div>
					Are you happy to help with our research by allowing Google Analytics cookies? 
					<button type="button" class="cookieConsentButton" data-bs-dismiss="alert" aria-label="Yes" id="acceptAnlyticsCookies">Yes</button>
					<button type="button" class="cookieConsentButton" data-bs-dismiss="alert" aria-label="No" id="declineAnlyticsCookies">No</button>
				</div>
				<br/>
			</div>
		</div>
        <header class="py-3 mb-0 border-bottom" id="header">
			<div class="container-fluid d-flex flex-wrap justify-content-center">
				<div id="dialoglogo" class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto text-dark text-decoration-none">
					<a href="<?php print($CFG->homeAddress);?>" title="<?php echo $LNG->HEADER_LOGO_HINT; ?>" class="text-decoration-none">
						<img alt="<?php echo $LNG->HEADER_LOGO_ALT; ?>" src="<?php echo $HUB_FLM->getImagePath('evidence-hub-logo-header.png'); ?>" />
					</a>
				</div>
			</div>
		</header>
		<div id="message" class="messagediv"></div>
		<div id="prompttext" class="prompttext"></div>
		<div id="hgrhint" class="hintRollover">
			<span id="globalMessage"></span>
		</div>

        <div id="main" class="main">
			<div id="contentwrapper" class="contentwrapper">
				<div id="content" class="content">
					<div class="c_innertube">
