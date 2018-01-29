var theater = {
    listState:{
        title:document.title,
        url: location.href,
        type: 'list'
    },
    containerId: 'list-body',
    currentUrl : '',
    checkState:function(){
        var currentState = window.history.state;
        if(currentState.type == 'list'){
            document.title = currentState.title;
            $('body').css('overflow','auto');
            $('body .viewphoto').hide().empty();

            window.history.replaceState(this.listState, this.listState.title, this.listState.url);
        }else if(currentState.type == 'viewphoto'){
            this.viewphoto(currentState.url,currentState.title);
            document.title = currentState.title;
            window.history.replaceState(currentState, currentState.title, currentState.url);
        }
    },
    init:function(container){
        this.containerId = container;
        var that=this;
        if(window.history.pushState){
            window.history.pushState(this.listState, this.listState.title, this.listState.url);
            window.addEventListener("popstate", function() {
                that.checkState();
            });
        }
        
        $(container).delegate('a.photo_link','click',function(){
            var url = $(this).attr('href');
            var title = $(this).attr('title')+' - '+site_title;

            that.viewphoto(url,title);
            var state = {
                title: title,
                url: url,
                type:'viewphoto'
            };
            document.title = title;
            window.history.pushState(state, title, url);
            return false;
        });

        $(document).bind('keydown',
             function(e){
                 if (e.altKey) return true;
                 var target = e.target;
                 if (target && target.type) return true;
                 switch(e.keyCode) {
                     case 63235: case 39: 
                      that.goNext();
                      break;
                     case 63234: case 37:
                      that.goPrev();
                      break;
                 }
             }
        );
    },
    viewphoto: function(url,title){
        var that=this;
        this.currentUrl = url;

        $.get(url,{},function(data){
            $('body').css('overflow','hidden');
            if($('body').find('.viewphoto').length <= 0 ){
                $('body').append('<div class="viewphoto"></div>');
            }
            $('body .viewphoto').show().html(data);
            if(commentInit)
                commentInit();

            $('body .viewphoto').find('div.photo-container a.close').click(function(){
                $('body').css('overflow','auto');
                $('body .viewphoto').hide().empty();

                if(window.history.pushState){
                    document.title = that.listState.title;
                    window.history.pushState(that.listState, that.listState.title, that.listState.url);
                }
                return false;
            });
            $('body .viewphoto').find('div.photo-container a.btn-prev').click(function(){
                that.goPrev();
                return false;
            });
            $('body .viewphoto').find('div.photo-container a.btn-next').click(function(){
                that.goNext();
                return false;
            });
            photo_detail_click();
        },'html');
    },
    goPrev: function(){
        var pos=$(this.containerId).find('a.photo_link[href="'+this.currentUrl+'"]').parent().index()
        console.log('pos:'+pos)
        if(pos==0){
            return false;
        }
        pos--;
        $(this.containerId).find('a.photo_link').eq(pos).click();
    },
    goNext: function(){
        var that = this;
        var pos=$(this.containerId).find('a.photo_link[href="'+this.currentUrl+'"]').parent().index()
        var length = $(this.containerId).find('a.photo_link').length;
        
        console.log('length:'+length+',pos:'+pos)
        if(pos>=(length-1)){//先加载更多数据
            ajax_load_data(function(){
                if(pos < $(this.containerId).find('a.photo_link').length-1){
                    pos++;
                    $(that.containerId).find('a.photo_link').eq(pos).click();
                }
            });
        }else{
            pos++;
            $(this.containerId).find('a.photo_link').eq(pos).click();
        }
    }
}