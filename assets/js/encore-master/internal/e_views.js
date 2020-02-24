
module.exports = function(_, settings){

    var fs = require('fs')
    var pth = require('path')
    var views = Object.create(null)

    var views_home_regex = new RegExp('^.*?'+settings.views_home)
    var compile = require(settings.templates).compile


    // Public \\
    function exported(name, data){
        if (_.isString(name))
            return (_(views).has(name)) ? views[name](data || {}) : undefined
        else
            return undefined
    }
    exported.add = add
    exported.makeView = makeView

    /**
     */
    function makeView(source, cb){
        if (_.isAnObject(source))
            cb(JSON.stringify(source), 'utf8', 'application/json')
            // cb(new ContentObject(JSON.stringify(source), 'utf8', 'application/json'))
        else {
            fs.readFile(source, function(err, data){
                if (err) throw err
                else cb(''+data)
            })
        }

        return exported
    }

    /**
     */
    function add(source, home){
        home = home || new RegExp('^.*?views'+pth.sep, 'ig')
        var name = source
            .replace(home, '')
            .replace(/\.[\w]*$/, '') // remove the suffix
            .replace(pth.sep, '.') // replace '/' with '.'
            .replace(/^\./g, '') // remove prepended periods from last step

        if (!require.resolve(source))
            throw new Error('view file "'+source+'" could not be found.')

        // views[name] = compile(''+fs.readFileSync(source))

        function load(err, data){
            if (err) throw err
            _.log('view added: '+name+': '+source)
            views[name] = compile(''+data)
        }
        fs.readFile(source, load)

        _.watch(source, function(data){
            views[name] = compile(''+data)
        })

        return exported
    }

    return exported
}
