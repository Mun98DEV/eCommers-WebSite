$(function(){
     $('.toggle-info').click(function(){
          $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(1500);
          if($(this).hasClass('selected')){
               $(this).html('<i class="fa fa-plus fa-larg selected"></i>');
          }else{
               $(this).html('<i class="fa fa-minus fa-lg"></i>');
          }
     });
     $('[placeholder]').focus(function(){
          $(this).attr('data-text', $(this).attr('placeholder'));
          $(this).attr('placeholder','');
     }).blur(function(){
          $(this).attr('placeholder', $(this).attr('data-text'));
     });

    
     $('.show-pass').hover(function(){
          //alert('fy');
          $('.password').attr('type','text');
     },function(){
          $('.password').attr('type','password');
     });

     $('.confirm').click(function(){
          return confirm('Are You Sure You Need To Delete This Member?');
     });

     $('.cats h3').click(function(){
          $(this).next('.full-view').fadeToggle(500);
     });

     $('.panel-heading .ordering .classic').click(function(){
          $('.full-view').fadeOut(500);
     });

     $('.panel-heading .ordering .full').click(function(){
          $('.full-view').fadeIn(500);
     });

    
});
