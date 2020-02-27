const $ = require('jquery');
const moment = require('moment');
require('bootstrap');
$(document).ready(function () {
    let profileData = {};
    let postInfo = {};
    let follows = {
        'followers': [],
        'followings': [],
    };
    let followed = false;
    let current = 0;
    const userId = $('#actions-util').attr('data-user');
    const appUserId = $('#actions-util').attr('data-app-user');
    const likedSvg = '<svg aria-label="Unlike" class="_8-yf5 " fill="#ed4956" height="24" viewBox="0 0 48 48" width="24"><path clip-rule="evenodd" d="M35.3 35.6c-9.2 8.2-9.8 8.9-11.3 8.9s-2.1-.7-11.3-8.9C6.5 30.1.5 25.6.5 17.8.5 9.9 6.4 3.5 13.7 3.5 20.8 3.5 24 8.8 24 8.8s3.2-5.3 10.3-5.3c7.3 0 13.2 6.4 13.2 14.3 0 7.8-6.1 12.3-12.2 17.8z" fill-rule="evenodd"></path></svg>';
    const unlikedSvg = '<svg aria-label="Like" class="_8-yf5 " fill="#262626" height="24"\n viewBox="0 0 48 48" width="24"><path clip-rule="evenodd"d="M34.3 3.5C27.2 3.5 24 8.8 24 8.8s-3.2-5.3-10.3-5.3C6.4 3.5.5 9.9.5 17.8s6.1 12.4 12.2 17.8c9.2 8.2 9.8 8.9 11.3 8.9s2.1-.7 11.3-8.9c6.2-5.5 12.2-10 12.2-17.8 0-7.9-5.9-14.3-13.2-14.3zm-1 29.8c-5.4 4.8-8.3 7.5-9.3 8.1-1-.7-4.6-3.9-9.3-8.1-5.5-4.9-11.2-9-11.2-15.6 0-6.2 4.6-11.3 10.2-11.3 4.1 0 6.3 2 7.9 4.2 3.6 5.1 1.2 5.1 4.8 0 1.6-2.2 3.8-4.2 7.9-4.2 5.6 0 10.2 5.1 10.2 11.3 0 6.7-5.7 10.8-11.2 15.6z" fill-rule="evenodd"></path></svg>';
    $('.not-logged-in').click(function () {
        console.log('dsfdsf');
    });
    $('.box-text').each(function () {
        const index = $(this).attr('data-index');
        profileData[index] = {
            'id': ($(this).attr('id')),
            'src': ($(this).attr('data-src')),
            'first': ($(this).attr('data-first')),
            'last': ($(this).attr('data-last')),
            'picId': ($(this).attr('data-pic-id')),
            'caption': ($(this).attr('data-caption')),
            'tags': ($(this).attr('data-tags')),
        };
        postInfo[index] = {
            'id': ($(this).attr('id')),
            'picId': ($(this).attr('data-pic-id')),
            'likes': [],
        };
        const data = {
            'user_id': userId,
            'photo_id': ($(this).attr('data-pic-id'))
        };
        $.ajax({
            url: '/actions/likes',
            type: 'POST',
            dataType: 'json',
            data: data,

            success: function (res) {
                console.log(res);
                if (res.success) {
                    postInfo[index]['likes'] = res.likes;
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Ajax request failed.');
            }
        });
    });
    getFollows();
    $('#prev').css("top", $(window).height() / 2);
    $('#next').css("top", $(window).height() / 2);
    $('#comments_container').css("height", $(window).height() - 250 + 'px');
    $('.box-text').click(function () {
        const data = profileData[$(this).attr('data-index')];
        $('#actions-util').attr('data-photo', data['picId']);
        current = Number($(this).attr('data-index'));
        $('#modal-img').attr('src', data['src']);
        $('#tags').html(data['tags']);
        $('#caption').html(data['caption']);
        $('#postModal').modal('show');
        if (!data['first']) {
            $('#prev').show();
        } else {
            $('#prev').hide();
        }
        if (!data['last']) {
            $('#next').show();
        } else {
            $('#next').hide();
        }
        if (liked(current)) {
            $('#like').html(likedSvg);
        } else {
            $('#like').html(unlikedSvg);
        }
        getComments(data['picId']);
        $('#comments_container').html('');

    });
    $('#prev').click(function () {
        --current;
        if (current !== 0) {
            const data = profileData[current];
            $('#actions-util').attr('data-photo', data['picId']);
            $('#modal-img').attr('src', data['src']);
            $('#tags').html(data['tags']);
            $('#caption').html(data['caption']);
            if (data['first']) {
                $(this).hide();
            }
            $('#next').show();
            if (liked(current)) {
                $('#like').html(likedSvg);
            } else {
                $('#like').html(unlikedSvg);
            }
            getComments(data['picId']);
            $('#comments_container').html('');

        }

    });
    $('#next').click(function () {
        ++current;

        const data = profileData[current];
        $('#actions-util').attr('data-photo', data['picId']);
        $('#modal-img').attr('src', data['src']);
        $('#tags').html(data['tags']);
        $('#caption').html(data['caption']);
        if (data['last']) {
            $(this).hide();
        }
        $('#prev').show();
        if (liked(current)) {
            $('#like').html(likedSvg);
        } else {
            $('#like').html(unlikedSvg);
        }
        $('#comments_container').html('');
        getComments(data['picId']);
    });
    $('#like').click(function () {
        const like = liked(current);
        if (like) {
            const data = {
                'id': like,
                'photo_id': $('#actions-util').attr('data-photo')
            };
            $.ajax({
                url: '/actions/unlike',
                type: 'POST',
                dataType: 'json',
                data: data,

                success: function (res) {
                    console.log(res);
                    if (res.success) {
                        $('#like').html(unlikedSvg);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Ajax request failed.');
                }
            });
        } else {
            const data = {
                'user_id': appUserId,
                'photo_id': $('#actions-util').attr('data-photo')
            };
            $.ajax({
                url: '/actions/like',
                type: 'POST',
                dataType: 'json',
                data: data,

                success: function (res) {
                    console.log(res);
                    if (res.success) {
                        $('#like').html(likedSvg);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Ajax request failed.');
                }
            });
        }
    });
    $('#comment-action').click(function () {
        $('#comment_input').focus();
    });
    $('#comment_submit').click(function () {
        if ($('#comment_input').val() !== '') {
            const data = {
                'user_id': appUserId,
                'photo_id': $('#actions-util').attr('data-photo'),
                'text': $('#comment_input').val()
            };
            $.ajax({
                url: '/actions/comment',
                type: 'POST',
                dataType: 'json',
                data: data,

                success: function (res) {
                    console.log(res);
                    if (res.success) {
                        $('#comment_input').val('');
                        getComments($('#actions-util').attr('data-photo'));
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log('Ajax request failed.');
                }
            });
        }
    });

    function getComments(photo) {
        const data = {
            'photo_id': photo,
        };
        $.ajax({
            url: '/actions/comments',
            type: 'POST',
            dataType: 'json',
            data: data,

            success: function (res) {
                console.log(res);
                if (res.success) {
                    let commentsHtml = '';
                    res.comments.forEach(function (item) {
                        commentsHtml += '<li class="mb-1"><img src="' + item.pic + '" alt="profile-pic" class="rounded-circle  thumbnail-pic"><a href="/profile/' + item.userId + '" class="font-weight-bold break-all ml-1 mr-1">' + item.name + '</a><span>' + item.text + ' </span><br><span class="small text-secondary">' + moment(item.date).fromNow() + '</span></li>';
                    });
                    $('#comments_container').html(commentsHtml);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Ajax request failed.');
            }
        });
    }

    $('.follow').each(function () {
        $(this).click(function () {
            if (followed) {
                unfollow(null,function () {
                    followed = false;
                    $('.follow').each(function () {
                        $(this).html('follow');
                    });
                });
            } else {
                follow(null,function () {
                    followed = true;
                    $('.follow').each(function () {
                        $(this).html('following');
                    });
                })
            }
        })
    });
    $('.m-follow').each(function () {
        $(this).click(function () {
            const self = this;
            if ($(this).attr('data-following') && ($(this).attr('data-following') == true || $(this).attr('data-following') == 1)) {
                unfollow($(self).attr('data-user-id'), function () {
                    $(self).html('follow');
                    $(self).attr('data-following', false);
                });
            } else {
                follow($(self).attr('data-user-id'), function () {
                    $(self).html('following');
                    $(self).attr('data-following', true);
                })
            }
        })
    });

    function getFollows() {
        const data = {
            'user': userId,
        };
        $.ajax({
            url: '/actions/follows',
            type: 'POST',
            dataType: 'json',
            data: data,

            success: function (res) {
                console.log(res);
                if (res.success) {
                    follows['followers'] = res.followers;
                    follows['followings'] = res.followings;
                    followed = follows['followers'].find(function (item) {
                        return item.id === Number(appUserId);
                    });
                    if (followed) {
                        $('.follow').each(function () {
                            $(this).html('following');
                        });
                    } else {
                        $('.follow').each(function () {
                            $(this).html('follow');
                        });
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Ajax request failed.');
            }
        });
    }

    function follow(modalUserId, callback) {

        const data = {
            'user': modalUserId ? modalUserId : userId,
            'app_user': appUserId,
        };
        $.ajax({
            url: '/actions/follow',
            type: 'POST',
            dataType: 'json',
            data: data,

            success: function (res) {
                console.log(res);
                if (res.success) {
                    callback();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Ajax request failed.');
            }
        });
    }

    function unfollow(modalUserId,callback) {
        const data = {
            'user': modalUserId ? modalUserId : userId,
            'app_user': appUserId,
        };
        $.ajax({
            url: '/actions/unfollow',
            type: 'POST',
            dataType: 'json',
            data: data,

            success: function (res) {
                console.log(res);
                if (res.success) {
                    callback();
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Ajax request failed.');
            }
        });
    }

    function liked(index) {
        const like = postInfo[index]['likes'].find(function (item) {
            return Number(item.userId) === Number(appUserId);
        });
        return like ? like.id : null;
    }
});