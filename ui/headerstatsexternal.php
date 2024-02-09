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
if ($CFG->privateSite) {
	checklogin();
}
?>
<!DOCTYPE html>
<html lang="<?php echo $CFG->language; ?>">
<head>
<?php
	if ($CFG->GOOGLE_ANALYTICS_ON) {
		include_once($HUB_FLM->getCodeDirPath("ui/analyticstracking.php"));
	}
?>

<meta charset="UTF-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php echo $CFG->SITE_TITLE; ?></title>

<link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("style.css"); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $HUB_FLM->getStylePath("stylecustom.css"); ?>" type="text/css" media="screen" />

<link rel="icon" href="<?php echo $HUB_FLM->getImagePath("favicon.ico"); ?>" type="images/x-icon" />
</head>

<body>
<div id="maincenter" style="margin:0 auto;width:1024px; max-width:1024px;">

<div id="header" class="headerback" style="margin-bottom:0px;padding-bottom:0px">
    <div style="float:left;margin:5px;">
    	<a title="<?php echo $LNG->HEADER_LOGO_HINT; ?>" href="<?php print($CFG->homeAddress);?>" style="font-size: 10pt; margin-bottom:3px;">
        <img border="0" alt="<?php echo $LNG->HEADER_LOGO_ALT; ?>" src="<?php echo $HUB_FLM->getImagePath('evidence-hub-logo-header.png'); ?>" />
        </a>
    </div>
</div>

<div style="float:left;width:100%;clear:both;padding:10px;padding-left:10px;padding-top:0px;">
