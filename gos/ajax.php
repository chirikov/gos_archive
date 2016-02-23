<script language="javascript" type="text/javascript">
	<!--//
	function getHTTPRequestObject() {
		var xmlHttpRequest;
		/*@cc_on
		@if (@_jscript_version >= 5)
		try {
			xmlHttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (exception1) {
			try {
				xmlHttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (exception2) {
				xmlHttpRequest = false;
			}
		}
		@else
		xmlhttpRequest = false;
		@end @*/
		
		if (!xmlHttpRequest && typeof XMLHttpRequest != "undefined") {
			try {
				xmlHttpRequest = new XMLHttpRequest();
			} catch (exception) {
				xmlHttpRequest = false;
			}
		}
		return xmlHttpRequest;
	}
	
	function getRequestBody(oForm) { 
        var aParams = new Array();
        for(var i = 0; i < oForm.elements.length; i++) {
          var sParam = encodeURIComponent(oForm.elements[i].name);
          sParam += "=";
          sParam += encodeURIComponent(oForm.elements[i].value);
          aParams.push(sParam);
        }
        return aParams.join("&");
      }
      
      function sendRequest(fid) {
        var oForm = document.getElementById(fid);
        var sBody = getRequestBody(oForm);
        var oXmlHttp = getHTTPRequestObject();
        
        oXmlHttp.open("POST", oForm.action, true);
        oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        oXmlHttp.send(sBody);
      }

	//-->
	</script>