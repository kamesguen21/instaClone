const $ = require('jquery');
const infiniteScroll = require('infinite-scroll');
require('bootstrap');
$(document).ready(function () {
    var elem = document.querySelector('.container');
    var infScroll = new infiniteScroll( elem, {
        // options
        path: '.pagination__next',
        append: '.post',
        history: false,
    });
});