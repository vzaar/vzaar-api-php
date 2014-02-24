package  
{
	import org.aswing.table.PropertyTranslator;
	
	/**
	 * FileSizeTranslator
	 * Translates numeric value into a string
	 * @author Skitsanos.com
	 */
	public class FileSizeTranslator implements PropertyTranslator
	{
		
		public function translate(info:*, key:String):*
		{
			return formatFileSize(info[key]);
		}
		
		// Called to format number to file size
        private function formatFileSize(numSize:Number):String 
		{
			var strReturn:String;
            numSize = Number(numSize / 1000);
            strReturn = String(numSize.toFixed(1) + " KB");
            if (numSize > 1000) {
                numSize = numSize / 1000;
                strReturn = String(numSize.toFixed(1) + " MB");
                if (numSize > 1000) {
                    numSize = numSize / 1000;
                    strReturn = String(numSize.toFixed(1) + " GB");
                }
            }                
            return strReturn;
        }
	}
	
}