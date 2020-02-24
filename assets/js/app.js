/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../scss/app.scss';
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
const $ = require('jquery');

require('bootstrap');
$(document).ready(function () {
    $(document).on("shown.bs.popover",'[data-toggle="popover"]', function(){
        $(this).attr('someattr','1');
    });
    $(document).on("hidden.bs.popover",'[data-toggle="popover"]', function(){
        $(this).attr('someattr','0');
    });
    $(document).on('click', function (e) {
        $('[data-toggle="popover"],[data-original-title]').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                if($(this).attr('someattr')=="1"){
                    $('#search_input').popover("hide");
                }
            }
        });
    });

    $('#search_input').keypress(function () {
        const data = {
            'term': $('#search_input').val()
        };
        $.ajax({
            url: '/actions/search',
            type: 'POST',
            dataType: 'json',
            data: data,

            success: function (res) {
                console.log(res);
                $('#search_input').popover({
                    content: prepareContent(res.users),
                    trigger: 'manual',
                    animation:true
                });
                $('#search_input').popover('show');
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log('Ajax request failed.');
            }
        });
    });

    function prepareContent(users) {
        let html = '<ul class="no-bull p-0 pop-width">';
        users.forEach(function (user) {
            html += '<li class="search-li mb-2 mt-2"><a href="/profile/' + user.id + '" class=""><img src="' + user.pic + '" alt="user_pic" class="rounded-circle  thumbnail-pic"> <span class="text-dark font-weight-bold ml-2">' + user.name + '</span></a></li>'
        });
        html += '</ul>';
        return html;
    }
});
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
