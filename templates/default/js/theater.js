var theater = {
    listState:{
        title:document.title,
        url: location.href,
        type: 'list'
    },
    inited : false,
    theater_type: 'album',
    containerId: 'list-body',
    currentUrl : '',
    checkState:function(){
        var currentState = window.history.state;
        if(!currentState){
            window.history.go(-1)
            return false;
        }
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
    init:function(container,type){
        if(type){
            this.theater_type = type;
        }
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
            var title = $(this).find('img').attr('alt')+' - '+site_title;

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

        this.inited = true;
        bind_keys();
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
            if(that.theater_type=='album' && commentInit)
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

            photo_detail_click();
        },'html');
    },
    goNext: function(callback){
        var that = this;
        var pos=$(this.containerId).find('a.photo_link[href="'+this.currentUrl+'"]').parent().index()
        var length = $(this.containerId).find('a.photo_link').length;
        
        if(pos>=(length-1)){//先加载更多数据
            ajax_load_data(function(){
                if(pos < $(this.containerId).find('a.photo_link').length-1){
                    pos++;
                    $(that.containerId).find('a.photo_link').eq(pos).click();
                }else{
                    if(typeof callback == 'function'){
                        callback();
                    }
                }
            },function(){
                if(typeof callback == 'function'){
                    callback();
                }
            });
        }else{
            pos++;
            $(this.containerId).find('a.photo_link').eq(pos).click();
        }
    }
}