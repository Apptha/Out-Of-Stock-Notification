/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Out-Of-Stock-Notification
 * @version     1.7
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 * */

function stockNotifyValidate(pId,id,test)
{ 
    
    	var clientEmailId = document.getElementById('outofstock_email'+id).value;
        var success = 1;	
     clientEmailId = clientEmailId.trim();
  	  if(validateEmail(clientEmailId)){ //if success given mail is ok
			  
			  document.getElementById('notify_error'+id).style.display = 'none';
			  document.getElementById('ajaximageshowing'+id).style.display = 'block';
			  success = storeProductDetailsToDB(pId,clientEmailId,id,test);
			  document.getElementById('padding_div'+id).removeAttribute("style");
		  }
		  else{ //is not a email so ask correct email id
			  
			  document.getElementById('outofstock_notify_error'+id).style.display = 'block';
			  document.getElementById('outofstock_email'+id).value = '';
			  document.getElementById('outofstock_email'+id).focus();
			  success = 0;
		  }
		  if(success){
			  return true;
		  }
		  else{
			  return false;
		  } 
} //function end

function storeProductDetailsToDB(pId,clientEmailId,id,test)
{
  
	var xmlhttp;

	  if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	  else
	  {// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	  {
		      var responce = xmlhttp.responseText;
		      responce = responce.trim();
                      
		      if(responce == 'okay')
		      {
                          var Already_Notified = Translator.translate('AlreadyNotified').stripTags();
		    	  document.getElementById('outofstock_notify_error'+id).innerHTML = Already_Notified;
		    	  document.getElementById('outofstock_notify_error'+id).style.display = 'block';
		    	  document.getElementById('outofstock_notify_error'+id).style.color = 'red';
		    	  document.getElementById('ajaximageshowing'+id).style.display = 'none';
		    	  document.getElementById('outofstock_email'+id).value = '';
			  document.getElementById('outofstock_email'+id).focus();
		    	  return 0;
		      }
		      else{ 
                          
		    	  document.getElementById('notify_message'+id).innerHTML='';
		    	  document.getElementById('responceMesPlace'+id).style.height='auto';
                          document.getElementById('ajaximageshowing'+id).style.display = 'none';
                           document.getElementById('outofstock_email'+id).value='';
                           document.getElementById('notify_submit'+id).style.display = "none";
		    	  document.getElementById('padding_div'+id).innerHTML=xmlhttp.responseText;
                          
                          if(test=='gr'){
                              document.getElementById('padding_div'+id).style.display = 'none';
                              document.getElementById('groupedthanks').innerHTML=xmlhttp.responseText;   	  
                          }
                          
		    	  
		    	return 1;
		      }
		     
	   }
  }
	xmlhttp.open("GET",crtlPageUrl+'?pId='+pId+'&email='+clientEmailId,true);
	xmlhttp.send();

}

function validateEmail(clientEmailId){  //validate email
	   var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;   
	   return emailPattern.test(clientEmailId);
} 