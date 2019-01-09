/**
 * Creates a window object that makes it easy for us to store data
 * related to scripts and styles that Wordpress is loading.
 * This object is then used in setup's wp_print_scripts action hook to load
 * data into.
 * @see  [theme_dir]/app/setup.php
 */
const _ = window.lodash;

if (typeof window.WP_ENQUEUED === 'undefined') {
    class SCRIPT_LIBRARY {

        constructor() {
            this.scripts = {};
            this.styles = {};
            this.script_handles = [];
            this.style_handles = [];
        }

        getScript(handle) {
            return this.scripts[handle];
        }

        getStyle(handle) {
            return this.styles[handle];
        }

        getScriptHandles() {
            return Object.keys(this.scripts);
        }

        getStyleHandles() {
            return Object.keys(this.styles);
        }

        setScript(handle, data) {
            this.scripts[handle] = data;
            this.script_handles = this.getScriptHandles();
        }

        setStyle(handle, data) {
            this.styles[handle] = data;
            this.style_handles = this.getStyleHandles();
        }

        _findInDict(items, key, search) {
            var found = [];
            Object.keys(items).forEach(function (item) {
                var tmp = items[item],
                    searchValue = tmp[key];
                try {
                    if (
                        (typeof searchValue === 'string' && tmp[key].search(search) !== -1) ||
                        (typeof searchValue === 'boolean' && searchValue === search) ||
                        (_.isArray(searchValue) && searchValue.indexOf(search) !== -1)
                    ) {
                        found.push(tmp);
                    }
                }
                catch (error) {
                    console.warn(error, item, tmp);
                }
            });

            return found;
        }

        searchSRCs(search) {
            return {
                'scripts': this._findInDict(this.scripts, 'src', search),
                'styles': this._findInDict(this.styles, 'src', search),
            };
        }

        searchHandles(search) {
            return {
                'scripts': this._findInDict(this.scripts, 'handle', search),
                'styles': this._findInDict(this.styles, 'handle', search),
            };
        }

        searchDeps(search) {
            return {
                'scripts': this._findInDict(this.scripts, 'deps', search),
                'styles' : this._findInDict(this.styles, 'deps', search),
            };
        }
    }

    window.WP_ENQUEUED = new SCRIPT_LIBRARY;
}
