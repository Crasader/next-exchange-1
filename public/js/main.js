function loadJs(loadUrl,callBackFun){
    $(".langs").remove()
    var loadScript=document.createElement('script');
    loadScript.setAttribute("type","text/javascript");
    loadScript.setAttribute('src',loadUrl);
    loadScript.setAttribute('class','langs');
    document.getElementsByTagName("head")[0].appendChild(loadScript);

    loadScript.onload=function(){
        loadScript.onload = null;
        loadScript = null;
        callBackFun();
    }
}
function callBackFun(){
    $("[data-lang-id]").each(function(){
        $(this).html(data_lang[$(this).attr('data-lang-id')])
    })
}
function callBackFunJson(data_lang){
    $("[data-lang-id]").each(function(){
        $(this).html(data_lang['localization'][$(this).attr('data-lang-id')])
    })
}
function setLang(lang){
    _lang = lang
    try {
        $('#search').val('');
        setFaqLang(lang);
    } catch(error) {
    }
    try {
        setPpLang(lang);
    } catch(error) {
    }
    cookies.setItem("lang",lang, 3600*24*30);
    switch(lang){
        case 'zh':
            loadJs(lang_zh,callBackFun);
            break;
        case 'en':
            loadJs(lang_en,callBackFun);
            break;
        default:
            loadJs(lang_en,callBackFun);
            break;
    }
    $(".lang").each(function(){
        lang == 'zh' ? $(this).html($(this).data('zh')):$(this).html($(this).data('en'));
    })
    $("#mobile_img").prop('src', 'static/img/phone-mobi-'+lang+'.png')
    $(".social").addClass('hidden')
    $(".social."+lang).removeClass('hidden')
    if (lang == 'en') {
        $(".lang-list").html("<a id='lang_btn' data-lang='zh'>中文</a>");
        $(".current-lang").text('English');
        // $('#mobi_video').prop('src','https://www.youtube.com/watch?v=2PV7nFKW0us');
    }else{
        $(".lang-list").html("<a id='lang_btn' data-lang='en'>English</a>");
        $(".current-lang").text('中文');
        // $('#mobi_video').prop('src','https://video.mobiapp.cn/Mobi_trailer_V12_JAN_29_EN%20CMP.mov?sign=Js79z7CTQKuCFZ0ffpnIs2EhZNZhPTEyNTI0MTQ3NjMmaz1BS0lEcVZYbnNGbkFDMGZZaE5qd3ozV1R2d1lkR2ZIUk1OeGQmZT0xNDg4NzA0MTU1JnQ9MTQ4NjExMjE1NSZyPTIwNTkxMTkwNCZmPS9Nb2JpX3RyYWlsZXJfVjEyX0pBTl8yOV9FTiUyMENNUC5tb3YmYj1tb2Jp')
    }
}
function setPpLang(lang){
    var data_src = (lang == 'zh') ? datapp_zh : datapp_en;
    $.getJSON(data_src, function(data){
        callBackFunJson(data);
        console.log(data);
    });
}
function setFaqLang(lang,text){
    var text = text ? text : '';
    var lang = lang ? lang : _lang;
    var faq_list = [];
    var faqerror = '';
    var faqhtml = '<ul>';
    var faqres = text ? '<h3>'+data_lang['mobi_faq_search_results']+':"'+text+'"</h3>' :'';
    switch(lang){
        case 'zh':
            faq_list = faq_zh;
            break;
        case 'en':
            faq_list = faq_en;
            break;
        default:
            faq_list = faq_en;
            break;
    }
    var r = 0;
    for (var i = 0; i < faq_list.length; i++) {
        var className="";
        if (text) {
            var q = faq_list[i].question.toLowerCase();
            var a = faq_list[i].answer.toLowerCase();
            var str = ''
            if (q.indexOf(text.toLowerCase()) > -1 || a.indexOf(text.toLowerCase()) > -1) {
                r += 1;
                if (r == 1) {
                    className = "class='active'";
                };
                faqhtml += '<li '+ className +'><span class="title"><span>'+faq_list[i].question+'</span><i></i></span><div class="content">'+faq_list[i].answer+'</div></li>';
            };
        }else{
            if (i == 0) {
                className = "class='active'";
            };
            faqhtml += '<li '+className+'><span class="title" data-category="'+faq_list[i].category+'"><span>'+faq_list[i].question+'</span><i></i></span><div class="content">'+faq_list[i].answer+'</div></li>';
        }
    };
    faqhtml += '</ul>';
    if (text && r == 0) {
        faqres = '';
        faqerror = '<div class="res"><h3>'+data_lang['mobi_faq_search_results_error_title']+'</h3><p>'+data_lang['mobi_faq_search_results_error_content']+'</p></div>'
    };
    r = 0
    $('#faq_body').html(faqres+faqhtml+faqerror);
}
$(function(){
    $('body').on('click', '.lang-select', function(){
        if($(this).attr('class').indexOf('selected')>0){
            $(this).removeClass('selected');
        }else{
            $(this).addClass('selected');
        }
    });
    $('body').on('click', '.lang-select #lang_btn', function(){
        var lang = $(this).data('lang');
        setLang(lang);
    });
})

// $(window).scroll(function(){
//   var top = $(document).scrollTop();
//   if (top > 80 && $('header').attr('class') === 'transparent') {
//       $('header').attr('class', 'default');
//   }
//   if (top <= 80 && $('header').attr('class') === 'default' && !$('header').attr('id')) {
//       $('header').attr('class', 'transparent');
//   }
// });
$('body').on('click', '.menu-icon', function(){
    if ($(this).attr('class').indexOf('open') > 0) {
        $(this).removeClass('open');
        $('#index_header').removeClass('open');
    }else{
        $(this).addClass('open');
        $('#index_header').addClass('open');
    }
})
$('body').on('click', '.show-item', function(){
    if ($(this).parent().attr('class').indexOf('open') > 0) {
        $(this).parent().removeClass('open');
    }else{
        $(this).parent().addClass('open');
    }
})

// if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
//   var t=0;
//   var p=0;
//   $(window).scroll(function(){
//     var p = $(document).scrollTop();
//     if(t<=p){
//         $('.mobile-tip').addClass('open');
//     }else{
//        $('.mobile-tip').removeClass('open');
//     }
//     setTimeout(function(){t = p;},0);
//   });
// }

/* Dashboard JS */
// Initializes search overlay plugin.
// Replace onSearchSubmit() and onKeyEnter() with
// your logic to perform a search and display results
/*$('[data-pages="search"]').search({
    // Bind elements that are included inside search overlay
    searchField: '#overlay-search',
    closeButton: '.overlay-close',
    suggestions: '#overlay-suggestions',
    brand: '.brand',

    // Callback that will be run when you hit ENTER button on search box
    onSearchSubmit: function (searchString) {

    },

    // Callback that will be run whenever you enter a key into search box.
    // Perform any live search here.
    onKeyEnter: function (searchString) {
        console.log("Live search for: " + searchString);

        var searchField = $('#overlay-search');
        var searchResults = $('.search-results');
        var resultsContainer = searchResults.find('.results-container');

        // hide previously returned results until server returns new results
        resultsContainer.html('');

        $.ajax({
            url: '/ajax/backend-search.php',
            type: 'POST',
            datatype: 'html',
            data: {
                search: searchString
            },
            success: function (data) {
                // successful request; do something with the data

                searchResults.html(data);
                //searchResults.fadeIn("fast"); // reveal updated results
            },
            error:function(){
                // failed request; give feedback to user

            }
        });
        return false;
    }
});
*/
function copyToClipboard(elementId) {

    // Create a "hidden" input
    var aux = document.createElement("input");

    // Assign it the value of the specified element
    aux.setAttribute("value", document.getElementById(elementId).innerHTML);

    // Append it to the body
    document.body.appendChild(aux);

    // Highlight its content
    aux.select();

    // Copy the highlighted text
    document.execCommand("copy");

    // Remove it from the body
    document.body.removeChild(aux);

}

