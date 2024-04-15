define(['jquery', 'core/config'], function(){
   return{
       course_filter:function(){
           $(document).ready(function(){
               $('.course_filter').change(function(){
                   var courseid = $(this).val();
                   var ajaxurl = 'ajax/report_content.php'
                   $.ajax({
                        type: 'POST',
                        data: {'courseid': courseid},
                        url: ajaxurl,
                        success: function (data) {
                            $('.reports_content_container').html(data);
                        },
                        error: function (data) {
                            alert('Something went wrong');
                        }
                    });
                });
           });
       }
   } 
});