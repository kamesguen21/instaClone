#!/usr/bin/env node

module.exports = MVC
MVC.settings = require('./internal/e_settings.json')
function MVC(app){
    var NODE_DOMAIN, fs, _
    // NODE_DOMAIN = require('domain').create()
    // NODE_DOMAIN.on('error', function (err) { console.log(err.message, err.stack) })
    // NODE_DOMAIN.run(function(){

    _ = require('lodash')
    _.chokidar = require('chokidar')
    fs = require('fs')

    _.extend(_, require('./internal/e_helpers_io.js'))
    _.extend(_, require('./internal/e_helpers_path.js'))
    _.extend(_, require('./internal/e_helpers_process.js'))
    _.extend(_, require('./internal/e_helpers_typecheck.js'))
    _.log = _.noop

    _.watch = function(file, cb){
        function reload(err, data){
            if (err) throw err
            _.log('file updated: '+file)
            cb(data)
        }
        _.chokidar
        .watch(file, {persistent: true})
        .on('change', function(){
            fs.readFile(file, reload)
        })
    }

    var easyioc = require('easyioc')
    var router = require('runway')

    function adapt(module){
        return (_.isString(module))
            ? function(){ return require(module) }
            : function(){ return module }
    }

    var server = require('http').createServer(router.listener)
    server.cert = function(cert){
        return require('https').createServer(cert, router.listener)
    }

    var dependencies = {
        '_':              adapt(_),
        'router':         adapt(router),
        'ioc':            adapt(easyioc),
        'fetchfiles':     adapt('filefetcher'),
        'RESTController': adapt('./internal/e_controllers.js'),
        'views':          require('./internal/e_views.js'),
        'settings':       MVC.settings,
        'server':         server,
        'main':           app,
    }

    Object.keys(dependencies).forEach(function(key){
        easyioc.add(key, dependencies[key])
    })
    easyioc.exec()
}
