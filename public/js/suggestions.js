function updateTitleCharacterCount() {
    var titleLength = $('#title').val().length;
    var element = $('#titleCounter');
    element.html(titleLength);
    if (titleLength >= 5 && titleLength <= 100) {
        element.removeClass('invalid');
    } else {
        element.addClass('invalid');
    }
}
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var fileSize = input.files[0].size;
        if( fileSize >= 250000){
            $('#post_suggestion').prop('disabled', true)
            $('.alert-danger').removeClass('d-none').addClass('d-block');
        }else{
            $('#post_suggestion').prop('disabled', false)
            $('.alert-danger').addClass('d-none').removeClass('d-block');
        }
        reader.onload = function (e) {
            $('#removeImage').show();
            $('#imagePreview').attr('src', e.target.result);
            $('#divImagePreview').addClass('d-flex').removeClass('d-none');
        }

        reader.readAsDataURL(input.files[0]);
    }
}
function updateDescriptionCharacterCount() {
    var descriptionLength = $('#description').val().length;
    var element = $('#descriptionCounter');
    element.html(descriptionLength);
    if (descriptionLength <= 1000) {
        element.removeClass('invalid');
    } else {
        element.addClass('invalid');
    }
}
function updateSymbolCharacterCount() {
    var symbolLength = $('#symbol').val().length;
    var element = $('#symbolCounter');
    element.html(symbolLength);
    if (symbolLength <= 1000) {
        element.removeClass('invalid');
    } else {
        element.addClass('invalid');
    }
}

function updateCommentCharacterCount() {
    var formText = $('#formText');
    if (!formText.length) {
        // unapproved suggestion has no comment form
        return;
    }
    var titleLength = formText.val().length;
    var element = $('#counter');
    element.html(titleLength);
    if (titleLength >= 10 && titleLength <= 500) {
        element.removeClass('invalid');
    } else {
        element.addClass('invalid');
    }
}

function scrollToForm() {
    $('html, body').animate({
        scrollTop: $("#cForm").offset().top
    }, 800);
}

function getCount() {
    var count = $('.count-input').attr('id').split('-')[1];
    $('#selected').append('<span>(' + count + ')</span>')
    if (count == 0) {
        $('#top-sorted').append(`<div class="container d-flex justify-content-center">
        <h2>No suggestions matched your search</h2>
        </div>
        `)
    }
}

$(document).ready(function () {
   
    $('#description').on('input', updateDescriptionCharacterCount);
    $('#symbol').on('input', updateSymbolCharacterCount);
    $('#title').on('input', updateTitleCharacterCount);

    // prevent double form submission
    var $form = $("form#cForm");
    $form.submit(function () {
        $form.submit(function () {
            return false;
        });
    });

    $('.js-example-basic-single').select2();
    
    $('#formText').on('input', updateCommentCharacterCount);

    $('#changeStatusOptions').find('a').on('click', function () {
        var statusId = $(this).attr('data-status-id');
        $('#suggestionStatusId').val(statusId);
        $('#formChangeStatus').submit();
    });

    $('.dropdown-menu a').click(function () {
        $text = $('#selected').text($(this).text());
        sessionStorage.setItem('text', $text)
    });

    $('#item-all').click(function () {
        $('#top-sorted').load('/suggestions/?order=popular&filter=all #suggestionsList', () => {
            getCount();
        });
    })
    $('#item-all-except-done').click(function () {
        $('#top-sorted').load('/suggestions/?order=popular&filter=all-except-done #suggestionsList', () => {
            getCount();
        });
    })
    $('#item-underconsideration').click(function () {
        $('#top-sorted').load('/suggestions/?order=popular&filter=under-consideration #suggestionsList', () => {
            getCount();
        });
    })
    $('#item-planned').click(function () {
        $('#top-sorted').load('/suggestions/?order=popular&filter=planned #suggestionsList', () => {
            getCount();
        });
    })
    $('#item-not-planned').click(function () {
        $('#top-sorted').load('/suggestions/?order=popular&filter=not-planned #suggestionsList', () => {
            getCount();
        });
    })
    $('#item-done').click(function () {
        $('#top-sorted').load('/suggestions/?order=popular&filter=done #suggestionsList', () => {
            getCount();
        });
    })
    $('#btn-new').click(function () {
        $('#btn-popular').removeClass('active')
        $(this).addClass('active');
        $('#top-sorted').load('/suggestions/?order=newest #suggestionsList');
    })
    $('#btn-popular').click(function () {
        $('#btn-new').removeClass('active')
        $(this).addClass('active');
        $('#top-sorted').load('/suggestions/?order=top #suggestionsList');
    })

    $('#search-suggestion').click(function () {
        $query = $('.search').val()
        $('#top-sorted').load('/suggestions/?q=' + encodeURIComponent($query) + ' #suggestionsList', () => {
            var count = $('.count-input').attr('id').split('-')[1];
            if (count == 0) {
                $('#top-sorted').append(`<div class="container d-flex justify-content-center">
                <h2>No suggestions matched your search</h2>
                </div>
                `)
            }
        })
    });
    $('#removeImage').click(()=>{
        $('#imagePreview').attr('src','');
        $('#removeImage').hide();
        $('#post_suggestion').prop('disabled', false)
        $('.alert-danger').addClass('d-none').removeClass('d-block');
        $('#divImagePreview').toggleClass('d-flex').toggleClass('d-none');
        $('#file').val('')
    })
    $('#btnAddImage').click(()=>{
        $('#file').trigger('click');
    })
    $('.sNumbers').on('click', '.upvote', function () {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var voteCount = $('#upvoteCount').attr('title')
        var id = $(this).attr('id').split('-')[1];
        var _self = this;
        $.ajax({
            url: '/suggestions/' + id + '/vote',
            type: 'POST',
            data: { _token: CSRF_TOKEN },
            dataType: 'JSON',
            success: function (data) {
                voteCount = ++voteCount
                $('#upvoteCount').attr('title',voteCount);
                if (voteCount <= 1000) {
                    $('#upvoteCount').text(voteCount)
                } else {
                    $('#upvoteCount').text(Math.round( (voteCount/1000) * 10 ) / 10 + 'k')
                }
                $(_self).addClass('btn-votedup')
                    .addClass('downvote')
                    .removeClass('btn-success')
                    .removeClass('upvote')
                    .text('Voted up');
                    
            },
            error: function(res){
                if(res.status === 401)
                    window.location = '/login'
            }
        });
    });


    $('.sNumbers').on('click', ".downvote", function () {
        var id = $(this).attr('id').split('-')[1];
        var voteCount = $('#upvoteCount').attr('title')
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var _self = this;
        $.ajax({
            url: '/suggestions/' + id + '/vote',
            type: 'DELETE',
            data: { _token: CSRF_TOKEN },
            dataType: 'json',
            success: function (data) {
                voteCount = --voteCount
                $('#upvoteCount').attr('title',voteCount);
                if (voteCount <= 1000) {
                    $('#upvoteCount').text(voteCount)
                } else {
                    $('#upvoteCount').text(Math.round( (voteCount/1000) * 10 ) / 10 + 'k')
                }
                $(_self).addClass('btn-success')
                    .addClass('upvote')
                    .removeClass('btn-votedup')
                    .removeClass('downvote')
                    .text('Upvote');
            }
        });
    })

    $('.btn-approve').click(function() {
        var id = $(this).attr('id').split('-')[1];
        var name = $(this).attr('id').split('-')[2];
        var context = $(this).attr('context');
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var _self = this;
        $.ajax({
            url: '/suggestions/' + id + '/status',
            type: 'PUT',
            data: { _token: CSRF_TOKEN, status: 'under-consideration' },
            dataType: 'JSON',
            success: function (data) {
                if(context === 'list'){
                    $(_self).parent().parent()
                    .children("span").removeClass('sLabelPn')
                    .addClass("sLabelUC")
                    .text("under-consideration")
                    $(_self).parent().addClass('hidden');
                    $(_self).parent().parent().siblings().children("a").removeClass("disabled")
                    $(_self).parent().siblings("a").css("color","#007bff").attr("href", `/suggestions/${name}`)
                }else{
                    $(_self).parent().addClass('hidden');
                    $('#status-panel').addClass('d-inline-block').removeClass('d-none');
                    $('#add-comment').addClass('d-block').removeClass('d-none');
                    $('#status-panel').children('span').removeClass('sLabelPn').addClass('sLabelUC').text("under-consideration");
                    $('.upvote').prop('disabled', false);
                }
            }
        });
    });

    $('.btn-reject').click(function() {
        var id = $(this).attr('id').split('-')[1];
        var context = $(this).attr('context');
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var _self = this;
        
        $.ajax({
            url: '/suggestions/' + id,
            type: 'DELETE',
            data: { _token: CSRF_TOKEN },
            dataType: 'JSON',
            success: function (data) {
                if(context == 'list')
                $(_self).parent().parent().parent().remove();
                else{
                    window.location = "/suggestions"
                }
            }
        });
    });

    $('#set-status').change(function(e){
        var value = $("#set-status option:selected").val();
        let color;
        switch(value){
            case 'under-consideration': color = "#2c3136";break;
            case 'not-planned': color = "#e98015";break;
            case 'planned': color = "#4f8196";break;
            case 'done': color = "#28b538";break;
        }
        var id = $(this).attr('title');
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var _self = this;
        $.ajax({
            url: '/suggestions/' + id + '/status',
            type: 'PUT',
            data: { _token: CSRF_TOKEN, status: value },
            dataType: 'JSON',
            success: function (data) {
                $(_self).siblings("span").text(value).css("background-color",color)
            }
        });

    });
});    