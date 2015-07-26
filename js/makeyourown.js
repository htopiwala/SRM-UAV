$(document).ready(function() {
    $("#submit-button").click(function() { 
       
        var proceed = true;
        //simple validation at client's end
        //loop through each field and we simply change border color to red for invalid fields       
        $("#contact_form input[required=true], #contact_form textarea[required=true]").each(function(){
            $(this).css('border-color',''); 
            if(!$.trim($(this).val())){ //if this field is empty 
                $(this).css('border-color','red'); //change border color to red   
                proceed = false; //set do not proceed flag
            }
            //check invalid email
            var email_reg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/; 
            if($(this).attr("type")=="email" && !email_reg.test($.trim($(this).val()))){
                $(this).css('border-color','red'); //change border color to red   
                proceed = false; //set do not proceed flag              
            }   
        });
       
        if(proceed) //everything looks good! proceed...
        {
            //get input field values data to be sent to server
            post_data = {
				'type'			: $('input[name=type]').val(),
				'range'			: $('input[name=range]').val(),
				'application'	: $('select[name=application]').val(),
				'camera'		: $('input[name=camera]').val(),
				'flyingtime'	: $('input[name=flyingtime]').val(),
				'altitude'		: $('input[name=altitude]').val(),
				'controller'	: $('select[name=controller]').val(),
				'budget'		: $('input[name=budget]').val(),
                'user_name'     : $('input[name=name]').val(), 
                'user_email'    : $('input[name=email]').val()
            };
            
            //Ajax post data to server
            $.post('makeown.php', post_data, function(response){  
                if(response.type == 'error'){ //load json data from server and output message     
                    output = '<div class="error"><i class="fa fa-times fa-2x pull-left"></i>'+response.text+'</div>';
                }else{
                    output = '<div class="success"><i class="fa fa-check fa-2x pull-left"></i>'+response.text+'</div>';
                    //reset previously set border colors and hide all message on .keyup()
                    $("#contact_form  input[required=true], #contact_form textarea[required=true]").val(''); 
                    $("#contact_form #contact_body").slideUp(); //hide form after success
                }
                $("#contact_form #contact_results").hide().html(output).slideDown();
            }, 'json');
        }
    });
    
    //reset previously set border colors and hide all message on .keyup()
    $("#contact_form  input[required=true], #contact_form textarea[required=true]").keyup(function() { 
        $(this).css('border-color',''); 
        $("#result").slideUp();
    });
});