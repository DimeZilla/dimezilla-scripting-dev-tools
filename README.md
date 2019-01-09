# DimeZilla's WordPress Scripting Dev Tools
Wordpress has entered it's 5.0 Gutenberg error! Yay! With this new editor experience and the flood of new front end libraries available to wordpress developers like lodash and react, I decided that I as a developer need a little help figuring out all that's new. Thus this project. This is my own personal place to drop helper tools and functions that will help illuminate the new environment I am working in.

**WARNING** - Some of these tools like WP_ENQUEUED are pretty resource intensive and are only meant to be loaded in a development environment where performance is not really question. As I list the tools out below, I will mark the ones that should only be used in development

## WP_ENQUEUED
**Should only really be used in dev**

This plugin creates a new object that is available in development to see what scripts are being loaded on any given page. It does this by creating an object called `WP_ENQUEUED` and binds it to the window.

#### properties:
- `scripts` - an array of objects for all of the scripts being loaded
- `stlyels` - an array of objects for all of the styles being loaded
- `script_handles` - an array of just the script handles
- `style_handles` - an array of just the style handles

#### methods:
- `getScript(handle)` - takes a handle and looks up the load data for that script
- `getStyle(handle)` - takes a handle and looks up the load data for that style
- `getScriptHandles()` - returns `script_handles`
- `getStyleHandles()` - returns `style_handles`
- `searchDeps(handle)` - looks through all of the scripts and styles and returns and object keyed by script and style. `script` and `style` are arrays of load data that have the search handle as a dependency.
- `searchHandles(search)` - looks through all of the handles and matches any lookup string in the handle. Returns an object keyed by script and style. `script` and `style` are arrays of load data that matched the search.
- `searchSRCs(search)` - looks through all of the scripts and styles and returns and object keyed by script and style. `script` and `style` are arrays of load data that have srces that matched the search string.
- `setStyle(handle, data)` and `setScript(handle, data)` add data to our style and script properties respectively.
