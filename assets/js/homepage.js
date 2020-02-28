const $ = require('jquery');
const infiniteScroll = require('infinite-scroll');
require('bootstrap');
$(document).ready(function () {
    const appUserId = $('#actions-util').attr('data-app-user');
    const likedSvg = '<svg aria-label="Unlike" class="_8-yf5 " fill="#ed4956" height="24" viewBox="0 0 48 48" width="24"><path clip-rule="evenodd" d="M35.3 35.6c-9.2 8.2-9.8 8.9-11.3 8.9s-2.1-.7-11.3-8.9C6.5 30.1.5 25.6.5 17.8.5 9.9 6.4 3.5 13.7 3.5 20.8 3.5 24 8.8 24 8.8s3.2-5.3 10.3-5.3c7.3 0 13.2 6.4 13.2 14.3 0 7.8-6.1 12.3-12.2 17.8z" fill-rule="evenodd"></path></svg>';
    const unlikedSvg = '<svg aria-label="Like" class="_8-yf5 " fill="#262626" height="24"\n viewBox="0 0 48 48" width="24"><path clip-rule="evenodd"d="M34.3 3.5C27.2 3.5 24 8.8 24 8.8s-3.2-5.3-10.3-5.3C6.4 3.5.5 9.9.5 17.8s6.1 12.4 12.2 17.8c9.2 8.2 9.8 8.9 11.3 8.9s2.1-.7 11.3-8.9c6.2-5.5 12.2-10 12.2-17.8 0-7.9-5.9-14.3-13.2-14.3zm-1 29.8c-5.4 4.8-8.3 7.5-9.3 8.1-1-.7-4.6-3.9-9.3-8.1-5.5-4.9-11.2-9-11.2-15.6 0-6.2 4.6-11.3 10.2-11.3 4.1 0 6.3 2 7.9 4.2 3.6 5.1 1.2 5.1 4.8 0 1.6-2.2 3.8-4.2 7.9-4.2 5.6 0 10.2 5.1 10.2 11.3 0 6.7-5.7 10.8-11.2 15.6z" fill-rule="evenodd"></path></svg>';
    var elem = document.getElementById('pagination-container');
    var infScroll = new infiniteScroll(elem, {
        // options
        path: 'homepage/{{#}}',
        append: '.paginated-post',
        checkLastPage: '.pagination__next',
        history: false,
        status: '.page-load-status'
    });
    infScroll.on('append', function (response, path, items) {
        like();
        commentAction();
        commentSubmit();
    });
    like();
    commentAction();
    commentSubmit();
    function commentAction() {
        $('.comment-action').off();
        $('.comment-action').on('click', function () {
            $('#' + $(this).attr('data-input-id')).focus();
        });
    }

    function commentSubmit() {
        $('.comment_submit').off();
        $('.comment_submit').on('click', function () {
            const comment_input = $('#' + $(this).attr('data-input-id'));
            const comments_container = $('#' + $(this).attr('data-comment-container-id'));
            if ($(comment_input).val() !== '') {
                const data = {
                    'user_id': appUserId,
                    'photo_id': $(this).attr('data-pic-id'),
                    'text': $(comment_input).val()
                };
                $.ajax({
                    url: '/actions/comment',
                    type: 'POST',
                    dataType: 'json',
                    data: data,

                    success: function (res) {
                        console.log(res);
                        if (res.success) {
                            $(comment_input).val('');
                            addComment($(comments_container), data)

                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log('Ajax request failed.');
                    }
                });
            }
        });
    }

    function addComment(elm, data) {
        const html = '     <a class="text-dark" href="/profile/' + appUserId + '">\n' +
            '                                                <img src="' + $('#app_user_photo').attr('src') + '" class="rounded-circle post-user-pic" alt="">\n' +
            '                                                <span class="h6 font-weight-bolder">' + $('#app_user_name').attr('data-name') + '</span>\n' +
            '                                            </a>\n' +
            '                                            <span>' + data['text'] + '</span>\n' +
            '                                            <span class="text-secondary text-capitalize">just now</span>';
        $(elm).append(html);
    }

    function like() {
        $('.p-like').off();
        $('.p-like').on('click', function () {
            const like = $(this).attr('data-like');
            const self = $(this);
            if (like) {
                const data = {
                    'id': like,
                    'photo_id': $(this).attr('data-pic-id')
                };
                $.ajax({
                    url: '/actions/unlike',
                    type: 'POST',
                    dataType: 'json',
                    data: data,

                    success: function (res) {
                        console.log(res);
                        if (res.success) {
                            $(self).html(unlikedSvg);
                            $(self).attr('data-like', null);
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log('Ajax request failed.');
                    }
                });
            } else {
                const data = {
                    'user_id': appUserId,
                    'photo_id': $(this).attr('data-pic-id')
                };
                $.ajax({
                    url: '/actions/like',
                    type: 'POST',
                    dataType: 'json',
                    data: data,

                    success: function (res) {
                        console.log(res);
                        if (res.success) {
                            $(self).html(likedSvg);
                            $(self).attr('data-like', res.id);

                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log('Ajax request failed.');
                    }
                });
            }
        })
    }
});