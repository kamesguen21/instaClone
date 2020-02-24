
var _ = require('lodash')
var pth = require('path')
var fs = require('fs')



/**
 */
function removeItem (string, array)
{
    if ( _.contains(array, string) ) {
        var i = array.indexOf(string)

        if (i !== -1) {
            var result = array
            result.splice(i,1)
            return result
        }
    }
    return array
}

/**
 */
function findProperty (thing, path)
{
    return _.reduce(path.split('.'), function (obj, prop) {
        if (_.has(obj, prop))
            return obj[prop]
        else
            return null
    }, thing)
}

/**
 */
function nameOf (thing)
{
    if ( _.isFunction(thing) ) {
        if ( /^function\s\(/g.test(thing.toString()) )
            return 'Anonymous Function'
        else
            return /^function\s(\w+?)\(\).*/g.exec( thing.toString() )[1]
    }

    if (thing !== null && thing !== undefined )
        return thing.constructor.name

    else
        return (thing === null) ? 'Null' : 'Undefined'
}

/**
 */
function typeOf (thing)
{
    switch (typeof thing) {
        case 'undefined':
            return 'undefined'
        case 'number':
            if (isNaN(thing)) return 'NaN'
            else return 'number'
        case 'function': return 'function'
        case 'string': return 'string'
        case 'boolean': return 'boolean'
        case 'date': return 'date'
        default:
            return Object.prototype.toString.apply(thing).toLowerCase().replace(/(^\[object\s|\]$)/g, '')
    }
}

/**
 */
function isNumeric (thing) { return !_.isNaN(parseFloat(thing)) && _.isFinite(thing) }

/**
 */
function isAnObject (thing) { return (typeof thing === 'object') }

/**
 */
function U (/*typeof*/ thing) { return (thing === 'undefined') }
function E (/*typeof*/ thing) { return (thing !== 'undefined') }



exports.removeItem    = removeItem
exports.findProperty  = findProperty
exports.nameOf        = nameOf
exports.typeOf        = typeOf
exports.isNumeric     = isNumeric
exports.isAnObject    = isAnObject
exports.U             = U
exports.E             = E
