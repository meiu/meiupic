$(function(){
    $(window).scroll(function () {
        if ($(document).height() - $(this).scrollTop() - $(this).height() < 200 ) {
            ajax_load_data();
        }
    });
});

var isLoading = false;
function ajax_load_data(callback) {
    if (!isLoading) {
        var next_p = $('.pageset').find('.next-page');
        if (next_p.length > 0) {
            isLoading = true;
            var next_page_url = next_p.attr('href');
            $.ajax({
                url: next_page_url,
                dataType: 'json',
                type: 'get',
                beforeSend: function () {
                    $('.loadingbar').show();
                },
                error: function(){
                    $('.loadingbar').hide();
                    isLoading = false;
                },
                success: function (data) {
                    try{
                        if (data.status != undefined && data.status == 'ok') {
                            $('.listCont').append(data.html);
                            if(typeof(callback) == 'function'){
                                callback();
                            }
                            if($("#grid-gallery").length > 0){
                                $("#grid-gallery").justifiedGallery('norewind').on('jg.complete', function (e) {
                                    $('.loadingbar').hide();
                                    isLoading = false;
                                });
                            }else{
                                $('.loadingbar').hide();
                                isLoading = false;
                            }
                            $('.pageset').html(data.pagehtml);
                        }
                    }catch (ex){

                    }
                }
            });
        }else{
            $('.loadingbar label').html('').addClass('nomore');
            $('.loadingbar').show();
        }
    }
}