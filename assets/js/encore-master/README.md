## Encore.js
working example: [rm-rf-etc/encore-docs-site](http://github.com/rm-rf-etc/encore-docs-site)

# No longer being maintained

4 other projects are used as modules in this project, and you have to use `npm link` to include them, if you want to get this project running. The projects you need to clone are rm-rf-etc/runway, rm-rf-etc/typeof, rm-rf-etc/easyioc, and rm-rf-etc/filefetcher. The following steps should get things working for you.
```
$ cd ~/projects
$ git clone git@github.com:rm-rf-etc/typeof
$ git clone git@github.com:rm-rf-etc/runway
$ git clone git@github.com:rm-rf-etc/easyioc
$ git clone git@github.com:rm-rf-etc/filefetcher
$ git clone git@github.com:rm-rf-etc/encore
```
Now you have to use `npm link` to use `typeof` in `runway`. If you're using `nvm` to manage node versions, make sure you stay on the same version while changing directories. Everything has been updated and tested in node version 6.8.1.
```
$ cd ~/projects/typeof && npm link
$ cd ~/projects/runway && npm install && npm link && npm link typeof
$ cd ~/projects/easyioc && npm install && npm link
$ cd ~/projects/filefetcher && npm link
$ cd ~/projects/encore
$ npm install
$ npm link typeof runway easyioc filefetcher
$ npm link
```
You should now be able to use `npm link encore` in your project, it will symlink encore under `node_modules`.


[![NPM](https://nodei.co/npm/encore.png?compact=true)](https://nodei.co/npm/encore/)

This is an MVC framework for node.js that I'm building. I've authored 3 modules specifically for this project,
and you can find them on npm and github:  

* Runway - the router  
* FileFetcher - project file loader  
* Easyioc - inversion of control module  

Current Features List:

* Inversion of control dependency loading  
* Cleanest routing syntax ever
* Route groups  
* Route wildcards  
* RESTful controllers  
* Filters on routes or controllers  
* View templating _(any 3rd party templating library)_  
* Performance optimized view rendering  
* Flexible recursive project loading

For examples, please visit:  
~~http://encore.jit.su~~ coming soon

More coming soon.

## License

The MIT License (MIT)

Copyright (c) 2013 Rob Christian

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
