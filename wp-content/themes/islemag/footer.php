<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package islemag
 */

?>

		</div><!-- #content -->

		<footer id="footer" class="footer-inverse" role="contentinfo">
			<div id="footer-inner">
				<div class="container">
					<div class="row">

						

						<?php if ( is_active_sidebar( 'islemag-first-footer-area' ) ) {  ?>
								<div itemscope itemtype="http://schema.org/WPSideBar" class="col-md-6 col-sm-12" id="sidebar-widgets-area-1" aria-label="<?php esc_html_e( 'Widgets Area 1','islemag' ); ?>">
									<?php dynamic_sidebar( 'islemag-first-footer-area' ); ?>
								</div>
						<?php }

if ( is_active_sidebar( 'islemag-second-footer-area' ) ) {  ?>
								<div itemscope itemtype="http://schema.org/WPSideBar" role="complementary" id="sidebar-widgets-area-2" class="col-md-6 col-sm-12" aria-label="<?php esc_html_e( 'Widgets Area 2','islemag' ); ?>">
									<?php dynamic_sidebar( 'islemag-second-footer-area' ); ?>
								</div>
						<?php }

if ( is_active_sidebar( 'islemag-third-footer-area' ) ) {  ?>
								<div itemscope itemtype="http://schema.org/WPSideBar" role="complementary" id="sidebar-widgets-area-3" class="col-md-4 col-sm-12" aria-label="<?php esc_html_e( 'Widgets Area 3','islemag' ); ?>">
									<?php dynamic_sidebar( 'islemag-third-footer-area' ); ?>
								</div>
						<?php
}
						?>

					</div><!-- End .row -->
				</div><!-- End .container -->
			</div><!-- End #footer-inner -->
			<div id="footer-bottom" class="no-bg">
				<div class="islemag-footer-container">
					<?php
					islemag_footer_container_head();

					islemag_footer_content();

	                islemag_footer_container_bottom();?>

				</div><!-- End .row -->
			</div><!-- End #footer-bottom -->
		</footer><!-- End #footer -->
	</div><!-- #page -->
</div><!-- End #wrapper -->
<?php wp_footer(); ?>

<script type="text/javascript"> 
// window.onload = function(){
// 	document.getElementById('clock').innerText(currentTime)
//        // $('#clock').html(currentTime);
//         var displayTime = window.setInterval(function(){
//          //$('#clock').html(currentTime)
//          document.getElementById('#clock').innerText(currentTime)
//         },1000);
// };

    $(function() {
    	//console.log('dddddddddddd')
        $('#clock').html(currentTime);
        var displayTime = window.setInterval(function(){
         $('#clock').html(currentTime)
        },1000);
    });
    function currentTime(){
    	var today = new Array('周日','周一','周二','周三','周四','周五','周六');  
        var d = new Date(),str1 = '',str2 = '',str3 = '';
         str1 += d.getFullYear()+'-';
         if(d.getMonth().length < 2){
         	str1  += d.getMonth() + 1 + '-';
         	str1 = '0' + str1
         }else{
         	str1  += d.getMonth() + 1 + '-';
         }
         
         if(d.getDate().length < 2){
         	str1  += d.getDate()+' ';
         	str1 = '0' + str1
         }else{
         	str1  += d.getDate()+' ';
         }
        

         str2 = today[d.getDay()] + ' ';


         str3 += d.getHours()+':';

         if(d.getMinutes().toString().length < 2){
         //	console.log('dddd')
         	str3  += '0' + d.getMinutes() + ':';
         	//str3 = '0' + str3
         }else{
         //	console.log('dfdaf')
         	str3  += d.getMinutes()+':';
         }
         
         
         if(d.getSeconds().toString().length < 2){
         	str3 += '0' + d.getSeconds()+'';
         }else{
         	str3 += d.getSeconds()+'';
         }
        return str1 + str2 + str3;
    }
// var documentHeight = 0;   
// var topPadding = 150;   
// $(function() {   
//     var offset = $(".sidebar").offset();   
//     console.log('offset-->',offset)
//     documentHeight = $(document).height();   
//     console.log('documentHeight-->',documentHeight)
//     $(window).scroll(function() {   
//         var sideBarHeight = $(".sidebar").height();   
//         console.log('sideBarHeight-->',sideBarHeight)
//         if ($(window).scrollTop() > offset.top) {   
//             var newPosition = ($(window).scrollTop() - offset.top) + topPadding;   
//             var maxPosition = documentHeight - (sideBarHeight + 500);   
//             console.log('newPosition-->',newPosition)
//             console.log('maxPosition-->',maxPosition)
//             if (newPosition > maxPosition) {   
//                 newPosition = maxPosition;   
//             }   
//             $(".sidebar").stop().animate({   
//                 marginTop: newPosition   
//             });   
//         } else {   
//             $(".sidebar").stop().animate({   
//                 marginTop: 0   
//             });   
//         };   
//     });   
// });   
</script>  

</body>
<?php include_once("baidu_js_push.php") ?>
</html>
