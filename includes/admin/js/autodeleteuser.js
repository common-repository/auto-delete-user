jQuery(document).ready( function() {
jQuery('#hssaveuser').click(function(){
	//var getuserrole = jQuery('option:selected', '#hsselectrole').attr('data-hsrolename');
	var getuserrole = jQuery('#hsselectrole').val();
	var getdays = jQuery('#hsdays').val();
   // var getuserid= jQuery('#hsselectrole').val();
	if(getuserrole =='Select User Role' || getdays=='' ){
		jQuery( "#hssaveuser" ).after( "<div class='hssuccess-message'>Please Enter Values !!!!</div>" ).fadeIn();
		jQuery('.hssuccess-message').delay(2000).fadeOut('slow');
		exit();
	}
	if(isNaN(getdays)){
		jQuery( "#hssaveuser" ).after( "<div class='hssuccess-message'>Please Enter Integer !!!!</div>" ).fadeIn();
		jQuery('.hssuccess-message').delay(2000).fadeOut('slow');
		exit();
	}
	jQuery.ajax({
		url : ajaxurl,
		type : 'POST',
		data : {
			action : 'hsautodeleteuser',
			getuserrole : getuserrole,
			getdays : getdays
		},
		beforeSend: function(){ 
        jQuery('#hsajaxauto_load').css('display','block');
		},
		
		success : function( data ) {
			jQuery( "#hssaveuser" ).after( "<div class='hssuccess-message'>"+data+"</div>" ).fadeIn();
		jQuery('.hssuccess-message').delay(2000).fadeOut('slow');
			
			jQuery('.hsshowuser').load(document.URL +  ' .hsshowuser');

			
		},
      error: function(errorThrown){
          //alert(errorThrown);
      },
	  complete: function(data){
        jQuery('#hsajaxauto_load').css('display','none');
		jQuery('.hsshowuser').load(document.URL +  ' .hsshowuser');
		}
	});
});

jQuery('.hsdeleteuserbtn').live("click", function(){
var getdeluser = jQuery(this).data('user');
	jQuery.ajax({
		url : ajaxurl,
		type : 'POST',
		data : {
			action : 'hsdeleteuser',
			getdeluser : getdeluser
		},
		beforeSend: function(){ 
        jQuery('#hsajaxauto_load').css('display','block');
		},
		
		success : function( data ) {
			jQuery( "#hssaveuser" ).after( "<div class='hssuccess-message'>"+data+"</div>" ).fadeIn();
			jQuery('.hssuccess-message').delay(2000).fadeOut('slow');
			jQuery('.hsshowuser').load(document.URL +  ' .hsshowuser');


		},
      error: function(errorThrown){
          //alert(errorThrown);
      },
	  complete: function(data){
        jQuery('#hsajaxauto_load').css('display','none');
		jQuery('.hsshowuser').load(document.URL +  ' .hsshowuser');


		}
	});
});	

	
});		
