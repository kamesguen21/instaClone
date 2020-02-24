
var _ = require('lodash')
var pth = require('path')

/**
 * @method formPath
 * @for Exports
 * @param path {String}
 * @param file {String}
 * @return {String}
 */
function formPath (path, file) { return pth.join( path, file ) }

/**
 * @method fileName
 * @for Exports
 * @param path {String}
 * @return {String}
 */
function fileName (path) { return pth.basename(path.replace(/\.\w*?$/g, '')) }

/**
 * @method fullPath
 * @for Exports
 * @param path {String}
 * @return {String}
 */
function fullPath (path) { return pth.resolve(path) }

/**
 * @method parentFolder
 * @for Exports
 * @param file {String}
 * @return {String}
 */
function parentFolder (file) { return /\/([^\/]+)\/[^\/]+$/g.exec(fullPath(file)) }

/**
 */
function isValidPath (path)
{
    if (typeof path === 'string')
        return ( /^[\w\d._~:\-\/?#@!$&'\[\]()*+,;=%]+$/g.test(path) )//&& !/\/{2,}/g.test(path) )
    else
        return false
}

/**
 */
function urlParse (regex, url, vars)
{
    failWhen( !_.isRegExp(regex) || !isValidPath(url), 'urlParse() received invalid arguments.', 1 )

    var args
    args = regex.exec(url)
    args = (args) ? args.slice(1) : []

    if ( _.isArray(vars) ) {
        var obj = {}

        if (vars.length === args.length) {
        for (var i in vars)
            obj[vars[i]] = args[i]

        return obj
        }
    }

    return args
}

/**
 */
function standardizePath (path)
{
    if (path.length > 1) {
        path = path.replace(/\/{2,}/g, '/')
        path = path.replace(/^\//, '') // Remove potential leading slash.
        if (/[^\/]$/.test( path )) path += '/'; // Include trailing slash.
    }
    else if ( !isValidPath(path) ) {
        failWhen(true, 'Path string is invalid.', 1)
        return false
    }

    return path
}

exports.isValidPath      = isValidPath
exports.urlParse         = urlParse
exports.standardizePath  = standardizePath
exports.formPath         = formPath
exports.fileName         = fileName
exports.fullPath         = fullPath
exports.parentFolder     = parentFolder
