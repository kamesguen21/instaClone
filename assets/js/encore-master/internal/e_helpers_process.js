
var _ = require('lodash')

/**
 */
var systemLogger = (function systemLogger (){

    // private
    var func = handler

    function handler (err) {
        console.log('Level: '+err.lvl, err, err.stack)
        throw err
    }

    // public
    /**
     * Call this when everything goes really wrong.
     * @method fail
     * @for systemLogger
     * @param msg {String} text that will go into the error.
     * @param lvl {Number} level value that will go into the error.
     * @param typ {Error} optional, include if you don't want a ReferenceError.
     * @param req {Error} optional, for future integration with node server.
     */
    function fail (msg, lvl, typ, req) {
        var msg = msg || 'No error message was defined for this condition.'

        var err
        if ( !_.isUndefined(typ) )
            err = new typ(msg)
        else
            err = new ReferenceError(msg)

        err.lvl = lvl || 1

        func(err)
    }

    /**
     * @method use
     * @for systemLogger
     * @param alternate {Function} Set a custom error handler. Will receive an Error object (pass Null to reset).
     */
    function use (alternate) {
        if ( _.isFunction(alternate) )
            func = alternate
        else if ( !alternate )
            func = handler
    }

    return {
        fail: fail,
        use: use
    }
})()

/**
 */
function failWhen (condition, msg, lvl, typ, req)
{
    var msg = msg || undefined
    var lvl = lvl || undefined
    var typ = typ || undefined

    if ( condition ) {
        systemLogger.fail(msg, lvl, typ)
        return false
    }
    else
        return true
}

/**
 * Die and dump.
 */
function dd () { console.log.apply(null, arguments); process.exit() }

/**
 * This is a fail-fast system protection method. Always use with the typeof
 * operator, like this: "ƒ(typeof [var])".
 *
 * OS X: alt+f. Windows: alt+159.
 *
 * @method ƒ(typeof var)
 * @for Exports
 * @param enemy {Variable}
 * @param msg {String}
 * @param lvl {Number}
 * @return {Boolean}
 */
function ƒ (/*typeof*/ enemy, msg, lvl, typ, req)
{
    var msg = msg || undefined
    var lvl = lvl || undefined
    var typ = typ || undefined

    if (enemy === 'undefined') {
        systemLogger.epicFail(msg, lvl, typ)
        return false
    }
    else
        return true
}

exports.systemLogger  = systemLogger
exports.failWhen      = failWhen
exports.dd            = dd
exports.ƒ             = ƒ
