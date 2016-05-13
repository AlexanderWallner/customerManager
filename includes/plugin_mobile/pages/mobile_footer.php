</div>
</div>
</div>
        <?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */ /* <div data-role="footer">
		<h4>Footer content</h4>
	</div><!-- /footer --> */ ?>

</div><!-- /page -->
<?php if(module_config::c('mobile_content_scroll',1) && module_security::is_logged_in()){ ?>
<script type="text/javascript">
    var contentscroll = [];
    var content = null;
    window.addEventListener("resize", function() {
        // Get screen size (inner/outerWidth, inner/outerHeight)
//        var headerheight = 20;
//        $('div[data-role="header"]').each(function(){
//            headerheight+= $(this).height();
//        });
//        if(content != null)content.width(window.innerWidth-10).height(window.innerHeight-headerheight);
//        if(contentscroll != null)contentscroll.refresh();
        for (var i in contentscroll){
            if(typeof contentscroll[i] == 'object'){
                contentscroll[i].refresh();
            }
        }
    }, false);

    $(function(){
        /*if(content == null){
            content = $('#mobile_content');
        }
        contentscroll = new iScroll(content[0] ,{
            onBeforeScrollStart: null,
            onTouchEnd: null,
            vScroll:true,
            hScroll:true,
            bounce: false,
            momentum: true,
            hScrollbar: false,
            vScrollbar: false
        });*/
        $('.iscroll').each(function(){
            contentscroll.push( new iScroll(this ,{
                onBeforeScrollStart: null,
                onTouchEnd: null,
                vScroll:true,
                hScroll:true,
                bounce: false,
                momentum: true,
                hScrollbar: false,
                vScrollbar: false
            }) );
        });
    });
</script>
<?php } ?>
</body>
</html>