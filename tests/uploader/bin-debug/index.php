<?php
/*
 * @author Skitsanos
*/
require_once '../../../Vzaar.php';


Vzaar::$secret = 'skitsanos';
Vzaar::$token = 'GETUGkPFNC84JlzXkOMSYQFTOCAixOIiroh7oUj3k';
Vzaar::$enableFlashSupport = true;

$redirect_url='http://vzaar.mywdk.com:8686/tests/uploadvideoprocess.php';

$uploadSignature=Vzaar::getUploadSignature($redirect_url);

$signature=$uploadSignature['vzaar-api'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>

        <script src="AC_OETags.js" type="text/javascript"></script>

        <style type="text/css">
            body { margin: 0px; overflow: hidden; }
        </style>

        <script type="text/javascript">
            var requiredMajorVersion = 9;
            var requiredMinorVersion = 0;
            var requiredRevision = 124;

            var VzaarUploader = {};
            VzaarUploader.config = {};
            VzaarUploader.config.url = 'http://vz1.s3.amazonaws.com/';
            VzaarUploader.data = {};
            VzaarUploader.data.acl = '<?php echo $signature['acl']; ?>';
            //VzaarUploader.data.bucket = '<?php echo $signature['bucket']; ?>';
            VzaarUploader.data.policy = '<?php echo $signature['policy']; ?>';
            VzaarUploader.data.AWSAccessKeyId = '<?php echo $signature['accesskeyid']; ?>';
            VzaarUploader.data.signature = '<?php echo $signature['signature']; ?>';
            VzaarUploader.data.success_action_status = '201';
            VzaarUploader.data.success_action_redirect = '<?php echo $redirect_url; ?>?guid=<?php echo $signature['guid']; ?>';
            VzaarUploader.data.key = '<?php echo $signature['key']; ?>';

            $(function(){

            });
        </script>

    </head>


    <body scroll="no">
        <script language="JavaScript" type="text/javascript">
            <!--
            // Version check for the Flash Player that has the ability to start Player Product Install (6.0r65)
            var hasProductInstall = DetectFlashVer(6, 0, 65);

            // Version check based upon the values defined in globals
            var hasRequestedVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);


            // Check to see if a player with Flash Product Install is available and the version does not meet the requirements for playback
            if ( hasProductInstall && !hasRequestedVersion ) {
                // MMdoctitle is the stored document.title value used by the installation process to close the window that started the process
                // This is necessary in order to close browser windows that are still utilizing the older version of the player after installation has completed
                // DO NOT MODIFY THE FOLLOWING FOUR LINES
                // Location visited after installation is complete if installation is required
                var MMPlayerType = (isIE == true) ? "ActiveX" : "PlugIn";
                var MMredirectURL = window.location;
                document.title = document.title.slice(0, 47) + " - Flash Player Installation";
                var MMdoctitle = document.title;

                AC_FL_RunContent(
                "src", "playerProductInstall",
                "FlashVars", "MMredirectURL="+MMredirectURL+'&MMplayerType='+MMPlayerType+'&MMdoctitle='+MMdoctitle+"",
                "width", "450",
                "height", "300",
                "align", "middle",
                "id", "VzaarUploader",
                "quality", "high",
                "bgcolor", "#ffffff",
                "name", "VzaarUploader",
                "allowScriptAccess","sameDomain",
                "type", "application/x-shockwave-flash",
                "pluginspage", "http://www.adobe.com/go/getflashplayer"
            );
            } else if (hasRequestedVersion) {
                // if we've detected an acceptable version
                // embed the Flash Content SWF when all tests are passed
                AC_FL_RunContent(
                "src", "VzaarUploader",
                "width", "450",
                "height", "300",
                "align", "middle",
                "id", "VzaarUploader",
                "quality", "high",
                "bgcolor", "#ffffff",
                "name", "VzaarUploader",
                "allowScriptAccess","sameDomain",
                "type", "application/x-shockwave-flash",
                "pluginspage", "http://www.adobe.com/go/getflashplayer"
            );
            } else {  // flash is too old or we can't detect the plugin
                var alternateContent = 'Alternate HTML content should be placed here. '
                    + 'This content requires the Adobe Flash Player. '
                    + '<a href=http://www.adobe.com/go/getflash/>Get Flash</a>';
                document.write(alternateContent);  // insert non-flash content
            }
            // -->
        </script>
        <noscript>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                    id="VzaarUploader" width="450" height="300"
                    codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
                <param name="movie" value="VzaarUploader.swf" />
                <param name="quality" value="high" />
                <param name="bgcolor" value="#ffffff" />
                <param name="allowScriptAccess" value="sameDomain" />
                <embed src="VzaarUploader.swf" quality="high" bgcolor="#ffffff"
                       width="450" height="300" name="VzaarUploader" align="middle"
                       play="true"
                       loop="false"
                       quality="high"
                       allowScriptAccess="sameDomain"
                       type="application/x-shockwave-flash"
                       pluginspage="http://www.adobe.com/go/getflashplayer">
                </embed>
            </object>
        </noscript>
    </body>
</html>