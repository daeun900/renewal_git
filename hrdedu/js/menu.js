$(document).ready(function() {

  var on_off=false;  //false(오버아웃)  true(오버)


     $(window).on('scroll',function(){
          var scroll = $(window).scrollTop(); 
          var height = $(document).height();

          if(scroll>150){
            if(height>1300){
              $('.navArea').addClass('fixed')}
            }else{
              $('.navArea').removeClass('fixed')     
          }
      
       ;
});
  
  //2depth 열기/닫기
   $('ul.dropdownmenu').hover(
      function() { 
         $('ul.dropdownmenu li.menu ul').css('height','400px'); 
         $('.nav_back').css('height','400px'); //모든 서브를 다 열어라
      },function() {
         $('ul.dropdownmenu li.menu ul').css('height','0px');
         $('.nav_back').css('height','0px'); //모든 서브를 다 닫아라
    });
   
    //1depth 효과
    $('ul.dropdownmenu li.menu').hover(
      function() { 
          $(this).find('a:first').css('color','#4185E1');
          $(this).find('ul').css('background-color','#fafafa');
      },function() {
          $(this).find('a:first').css('color','#333');
          $(this).find('ul').css('background-color','#fff');
    });


    //2depth효과
    $('ul.dropdownmenu li.menu ul li a').hover(
      function() { 
          $(this).css('color','#4185E1');
      },function() {
         $(this).css('color','#666');
    });
  }
  );
