function ajaxsubmit(url,data,gotourl)
{
	$.ajax({
		   type: "POST",
		   url: url,
		   data: data,
		   success: function(msg){
			   //var messageobj = eval('('+msg+')');
			   if(msg.code ==1){
				   $.dialog({
					    lock: true,
					    title: false,
			            //cancel: false,
			            fixed: true,
			            resize: true,
			            max: false, 
			            min: false,
			            width: '300px', 
			            height: 140,
			            icon:'success.gif',
					    time: 2,
					    content: msg.message ,
					    close: function(){
                            window.parent.location.href = gotourl;
					    }
					});
			   }else{				  
				   $.dialog({
					    lock: true,
					    title: false,
			            //cancel: false,
			            fixed: true,
			            resize: true,
			            max: false, 
			            min: false,
			            width: '300px', 
			            height: 140,
			            icon:'error.gif',
					    time: 2,
					    content: msg.message
					});
			   }
			   
		   }
		});
}

function iframeajaxsubmit(url,data,gotourl)
{
    $.ajax({
        type: "POST",
        url: url,
        data: data,
        success: function(msg){
            //var messageobj = eval('('+msg+')');
            if(msg.code ==1){
                $.dialog({
                    lock: true,
                    title: false,
                    //cancel: false,
                    fixed: true,
                    resize: true,
                    max: false,
                    min: false,
                    width: '300px',
                    height: 140,
                    icon:'success.gif',
                    time: 2,
                    content: msg.message ,
                    close: function(){
                        location.href = gotourl;
                    }
                });
            }else{
                $.dialog({
                    lock: true,
                    title: false,
                    //cancel: false,
                    fixed: true,
                    resize: true,
                    max: false,
                    min: false,
                    width: '300px',
                    height: 140,
                    icon:'error.gif',
                    time: 2,
                    content: msg.message
                });
            }

        }
    });
}

function ajaxsubmit2(url,data,gotourl)
{
    $.ajax({
        type: "GET",
        url: url,
        data: data,
        success: function(msg){
            var messageobj = eval('('+msg+')');
            //alert(messageobj);
            if(messageobj.code ==1){
                $.dialog({
                    lock: true,
                    title: false,
                    //cancel: false,
                    fixed: true,
                    resize: true,
                    max: false,
                    min: false,
                    width: '300px',
                    height: 140,
                    icon:'success.gif',
                    time: 2,
                    content: messageobj.message ,
                    close: function(){
                        window.parent.location.href = gotourl;
                    }
                });
            }else{
                $.dialog({
                    lock: true,
                    title: false,
                    //cancel: false,
                    fixed: true,
                    resize: true,
                    max: false,
                    min: false,
                    width: '300px',
                    height: 140,
                    icon:'error.gif',
                    time: 2,
                    content: messageobj.message
                });
            }

        }
    });
}
