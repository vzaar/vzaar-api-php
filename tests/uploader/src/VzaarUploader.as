/**
 * as3Uploader
 * @author Skitsanos
 * @version 1.5
 */
package
{
import flash.display.Sprite;
import flash.events.DataEvent;
import flash.events.Event;
import flash.events.HTTPStatusEvent;
import flash.events.IOErrorEvent;
import flash.events.MouseEvent;
import flash.events.ProgressEvent;
import flash.external.ExternalInterface;
import flash.net.FileFilter;
import flash.net.FileReference;
import flash.net.FileReferenceList;
import flash.net.URLRequest;
import flash.net.URLRequestMethod;
import flash.net.URLVariables;
import flash.system.Security;
import flash.ui.ContextMenu;
import flash.ui.ContextMenuItem;

import org.aswing.AsWingConstants;
import org.aswing.AsWingManager;
import org.aswing.JButton;
import org.aswing.JLabel;
import org.aswing.JPanel;
import org.aswing.JScrollPane;
import org.aswing.JTable;
import org.aswing.JWindow;
import org.aswing.VectorListModel;
import org.aswing.event.SelectionEvent;
import org.aswing.table.PropertyTableModel;

[SWF (width="450", height="300", backgroundColor = "#ffffff")]
public class VzaarUploader extends Sprite
{
    private var uploadUrl:String = "";

    private var _refFiles:FileReferenceList;
    private var file:FileReference;
    private var fileQueue:Array;

    private var datalist:VectorListModel;
    private var grid:JTable;

    private var mimeTypes:Object = new Object();
    
    //shared UI controls
    private var status:JLabel;
    private var btnUpload:JButton;
    private var btnRemoveSelected:JButton;
    private var btnClearAll:JButton;

    private var ctxmenu:ContextMenu;

    private var jsConfig:Object = {};
    
    public function VzaarUploader()
    {
        Security.allowDomain("*");
        //Security.allowDomain('vz1.s3.amazonaws.com');
        //Security.loadPolicyFile('https://vz1.s3.amazonaws.com/crossdomain.xml');
       
        //mimeTypes.description = "Images (*.jpg, *.jpeg, *.gif, *.png)";
        //mimeTypes.extensions = "*.jpg; *.jpeg; *.gif; *.png";
        //Supported formats: avi, flv, m4v, mp4, mov, 3gp, wmv, mp3 
        
        mimeTypes.description = "All Files (*.*)";
        mimeTypes.extensions = "*.*";

        ctxmenu = new ContextMenu();
        ctxmenu.hideBuiltInItems();
        this.contextMenu = ctxmenu;

        var menuCopyright:ContextMenuItem = new ContextMenuItem('VzaarUploader © 2010, Skitsanos.com');
        ctxmenu.customItems.push(menuCopyright);

        fileQueue = [];

        if (stage != null)
        {
            init();
        } else {
            addEventListener(Event.ADDED_TO_STAGE, init);
        }
    }

    /**
     * Initializes the application
     * @return void
     */
    private function init():void
    {
        AsWingManager.initAsStandard(this, true);
        stage.showDefaultContextMenu = false;

        //register external interface calls
        ExternalInterface.addCallback("setUrl", function(url:String):void {
            uploadUrl = url;
        });
        
        var panel:JPanel = new JPanel();
        panel.setSizeWH(400, 300);
        AsWingManager.getRoot().addChild(panel);

        var btnBrowse:JButton = new JButton('Browse...');
        btnBrowse.setSizeWH(80, 20);
        btnBrowse.addEventListener(MouseEvent.CLICK, _button_Browse_click);
        panel.append(btnBrowse);

        btnRemoveSelected = new JButton("Remove Seleted");
        btnRemoveSelected.setEnabled(false);
        btnRemoveSelected.setX(80);
        btnRemoveSelected.setSizeWH(120, 20);
        btnRemoveSelected.addEventListener(MouseEvent.CLICK, _button_RemoveSelected_click);
        panel.append(btnRemoveSelected);

        btnClearAll = new JButton('Clear All');
        btnClearAll.setEnabled(false);
        btnClearAll.setX(200);
        btnClearAll.setSizeWH(80, 20);
        btnClearAll.addEventListener(MouseEvent.CLICK, _button_ClearAll_click);
        panel.append(btnClearAll);

        btnUpload = new JButton("Upload");
        btnUpload.setEnabled(false);
        btnUpload.setX(320);
        btnUpload.setSizeWH(80, 20);
        btnUpload.addEventListener(MouseEvent.CLICK, _button_Upload_click);
        panel.append(btnUpload);

        //prepare grid
        datalist = new VectorListModel();
        /*[
         {name: 'file.ext', size: 12400 },
         {name: 'file2 - copy.ext', size: 12220 }
         ]);
         */
        var tableModel:PropertyTableModel = new PropertyTableModel(datalist, ['File name', 'Size', 'Progress'], ['name', 'size', 'progress'], [null, new FileSizeTranslator(), null]);
        tableModel.setColumnEditable(0, false)
        tableModel.setColumnEditable(1, false);

        grid = new JTable(tableModel);
        grid.setShowVerticalLines(true);
        grid.setSizeWH(400, 200);
        grid.setY(20);

        grid.addEventListener(SelectionEvent.ROW_SELECTION_CHANGED, _row_selected);

        var frame:JWindow = new JWindow(null);
        frame.setY(20);
        frame.setSizeWH(400, 260);
        frame.setContentPane(new JScrollPane(grid));
        frame.show();

        status = new JLabel("Idle", null, AsWingConstants.LEFT);
        status.setY(280);
        status.setSizeWH(400, 20);
        panel.append(status);

        //ExternalInterface.call('as3Uploader_init');
        jsConfig = ExternalInterface.call('eval', 'VzaarUploader.config');
        if (jsConfig == null)
        {
            status.setText('Error: VzaarUploader.config missing');
        }                
    }

    private function _row_selected(e:SelectionEvent):void
    {
        btnRemoveSelected.setEnabled(true);
    }

    private function _button_Browse_click(e:MouseEvent):void
    {
        file = new FileReference();
        file.addEventListener(Event.SELECT, onSelectFile);

        var filter:FileFilter = new FileFilter(mimeTypes.description, mimeTypes.extensions);
        file.browse([filter]);
    }

    private function _button_RemoveSelected_click(e:MouseEvent):void
    {
        datalist.removeAt(grid.getSelectedRow());
        fileQueue.splice(grid.getSelectedRow(), 1);

        if (fileQueue.length < 1)
        {
            btnRemoveSelected.setEnabled(false);
            btnClearAll.setEnabled(false);
            btnUpload.setEnabled(false);
        }

        status.setText(fileQueue.length + ' files in queue');
    }

    private function _button_ClearAll_click(e:MouseEvent):void
    {
        datalist.clear();
        fileQueue = [];

        btnUpload.setEnabled(false);
        btnRemoveSelected.setEnabled(false);
        btnClearAll.setEnabled(false);
    }

    private function _button_Upload_click(e:MouseEvent):void
    {
        if (fileQueue.length > 0)
        {
            status.setText("Uploading, please wait...");
            //reread VzaarUploader.config
            jsConfig = ExternalInterface.call('eval', 'VzaarUploader.config');
            if (jsConfig != null)
            {
                uploadUrl = jsConfig.url;
            }
                        
            //process variables sent via config
            var data:URLVariables = new URLVariables();            
            var jsPostData:Object = ExternalInterface.call('eval', 'VzaarUploader.data');                 
            if (jsPostData != null)
            {
                for (var key:String in jsPostData)
                {
                    data[key] = jsPostData[key];
                }
            }
            
            data['content-type'] = 'binary/octet-stream';
            //data['file_post_name'] = 'file';
           
            var request:URLRequest = new URLRequest(uploadUrl)            
            request.data = data;
            request.method = URLRequestMethod.POST;

            for each (var f:FileReference in fileQueue)
            {                
                f.addEventListener(ProgressEvent.PROGRESS, _file_UploadProgress);
                f.addEventListener(Event.COMPLETE, _file_UploadComplete);
                f.addEventListener(IOErrorEvent.IO_ERROR, _file_UploadIoError);
                f.addEventListener(HTTPStatusEvent.HTTP_STATUS, _file_UploadHttpStatus);
                f.addEventListener(DataEvent.UPLOAD_COMPLETE_DATA, function(e:DataEvent):void{
                	trace(e.text);
                });

                f.upload(request, "File", false);
            }
        }
    }

    private function onSelectFile(e:Event):void
    {
        var ref:FileReference = FileReference(e.currentTarget);
        fileQueue.push(ref);

        datalist.append({ name: ref.name, size: ref.size, progress: '0%' });

        btnUpload.setEnabled(true);
        //btnRemoveSelected.setEnabled(false);
        btnClearAll.setEnabled(true);

        status.setText(fileQueue.length + ' files in queue');
    }
    
    private function _file_UploadComplete(e:Event):void
    {
        var ref:FileReference = FileReference(e.currentTarget);
        ref.removeEventListener(ProgressEvent.PROGRESS, _file_UploadProgress);
        ref.removeEventListener(Event.COMPLETE, _file_UploadComplete);

        for (var j:Number = 0; j < fileQueue.length; j++)
        {
            if (FileReference(fileQueue[j]).name == ref.name)
            {
                fileQueue.splice(j, 1);
                datalist.removeAt(j);
            }
        }

        if (fileQueue.length < 1)
        {
            datalist.clear();
            btnClearAll.setEnabled(false);
            btnUpload.setEnabled(false);
            btnRemoveSelected.setEnabled(false);
        }

        status.setText(fileQueue.length + ' files in queue');        
    }

    private function _file_UploadIoError(e:IOErrorEvent):void
    {
        trace(e.text);
        status.setText(e.text);
    }
	/**
	 * handle permission denied errors
	 */ 
    private function _file_UploadHttpStatus(e:HTTPStatusEvent):void
    {
        if(e.status == 403)
        {
        	var ref:FileReference = FileReference(e.currentTarget);
	        for (var j:Number = 0; j < fileQueue.length; j++)
	        {
	            if (FileReference(fileQueue[j]).name == ref.name)
	            {
	        		grid.getModel().setValueAt('ERROR (403)',j, 2);        		
	            }
	        }
        }
    }

    private function _file_UploadProgress(e:ProgressEvent):void
    {
        var numPerc:Number = Math.round((e.bytesLoaded / e.bytesTotal) * 100);        
        var ref:FileReference = FileReference(e.currentTarget);
        for (var j:Number = 0; j < fileQueue.length; j++)
        {
            if (FileReference(fileQueue[j]).name == ref.name)
            {
        		grid.getModel().setValueAt(numPerc+'%',j, 2);        		
            }
        }
        
        trace("uploaded " + numPerc + "%");
    }
}
}