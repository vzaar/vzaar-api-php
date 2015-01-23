<?php
/*
 * SWFUpload Test
 * @author skitsanos
 */
require_once '../../src/Vzaar.php';
Vzaar::$token = "qQi7utyEliiPQqiU6sH2u33Z6y80yMZoIYx684Ct9w";
Vzaar::$secret = "atletesprofessional";
Vzaar::$enableFlashSupport = true;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>SWFUpload Demos</title>
    <link href="http://demo.swfupload.org/v220/css/default.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

    <script type="text/javascript" src="swfupload.js"></script>
    <script type="text/javascript" src="swfupload.queue.js"></script>
    <script type="text/javascript" src="fileprogress.js"></script>
    <script type="text/javascript" src="handlers.js"></script>

    <script type="text/javascript">
        var vzaar_signature = <?php echo(json_encode(Vzaar::getUploadSignature())); ?>;
        var swfu;
        var s3Response = {};

        $(function ()
        {
            var settings = {
                flash_url:"swfupload.swf",
                upload_url:'http://' + vzaar_signature["vzaar-api"].bucket + '.s3.amazonaws.com/',
                post_params:{
                    "content-type":"binary/octet-stream",
                    "acl":vzaar_signature["vzaar-api"].acl,
                    "policy":vzaar_signature["vzaar-api"].policy,
                    "AWSAccessKeyId":vzaar_signature["vzaar-api"].accesskeyid,
                    "signature":vzaar_signature["vzaar-api"].signature,
                    "success_action_status":"201",
                    "key":vzaar_signature["vzaar-api"].key
                },
                use_query_string:false,
                file_post_name:'File',
                file_size_limit:0,
                file_types:"*.*",
                file_types_description:"All Files",
                file_upload_limit:10/*number of files*/,
                file_queue_limit:0,
                custom_settings:{
                    progressTarget:"fsUploadProgress",
                    cancelButtonId:"btnCancel"
                },
                debug:false,

                // Button settings
                button_width:"80",
                button_height:"32",
                button_placeholder_id:"spanButtonPlaceHolder",
                button_text:'Browse ...',
                button_text_style:".theFont { font-weight: bold; }",
                button_text_left_padding:12,
                button_text_top_padding:3,

                // The event handler functions are defined in handlers.js
                file_queued_handler:fileQueued,
                file_queue_error_handler:fileQueueError,
                file_dialog_complete_handler:fileDialogComplete,
                upload_start_handler:uploadStart,
                upload_progress_handler:uploadProgress,
                upload_error_handler:function uploadError(file, errorCode, message)
                {
                    $('#status').html(message);
                },
                upload_success_handler:function uploadSuccess(file, serverData)
                {
                    s3Response = $(serverData);

                    var arrKey = s3Response.find('key').html().split('/');
                    var guid = arrKey[arrKey.length - 2];

                    $('#status').append('GUID: ' + guid + '<br/>');
                    return;

                    //calling Process Video service
                    $.post('processVideo.php', {
                        guid:guid,
                        title:'S3_Upload Automated Sample',
                        description:''
                    }, function (data)
                    {
                        $('#status').html(data);
                    });
                },
                upload_complete_handler:uploadComplete,
                queue_complete_handler:queueComplete    // Queue plugin event
            };

            swfu = new SWFUpload(settings);
        });
    </script>

</head>
<body>
    <div id="header">
        <h1 id="logo">SWFUpload</h1>

        <div id="version">v2.5.0 (tweaked)</div>
    </div>
    <div id="content">
        <h2>SWFUpload vzaar Demo</h2>

        <p>This page demonstrates a simple usage of SWFUpload. It uses the Queue Plugin to simplify uploading or
            cancelling all queued files.</p>

        <div class="fieldset flash" id="fsUploadProgress">
            <span class="legend">Upload Queue</span>
        </div>
        <div id="divStatus">0 Files Uploaded</div>
        <div>
            <span id="spanButtonPlaceHolder"></span>
            <input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;"/>
        </div>
        <div id="status"></div>
        <p>&nbsp;</p>
        <hr size="1"/>
        <p>Related Stuff</p>
        <ul>
            <li><a href="http://code.google.com/p/vzaar/">Vzaar API</a></li>
            <li><a href="http://swfupload.org/project">Download SWF Upload</a></li>
        </ul>
    </div>
</body>
</html>