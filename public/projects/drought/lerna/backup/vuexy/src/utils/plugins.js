/**
 * Load a group of plugins.
 * @param {object} Vue
 * @param {object} Plugin definitions
 */
export const registerPlugins = (Vue, plugins = {}) => {
    for (const plugin in plugins) {
        if (plugin && plugins[plugin]) {
            Vue.use(plugins[plugin])
        }
    }
}

/**
 * Load a component.
 * @param {object} Vue
 * @param {string} Component name
 * @param {object} Component definition
 */
export const registerComponent = (Vue, name, def) => {
    if (Vue && name && def) {
        Vue.component(name, def)
    }
}

/**
 * Load a group of components.
 * @param {object} Vue
 * @param {object} Object of component definitions
 */
export const registerComponents = (Vue, components = {}) => {
    for (const component in components) {
        registerComponent(Vue, component, components[component])
    }
}

/**
 * Load a directive.
 * @param {object} Vue
 * @param {string} Directive name
 * @param {object} Directive definition
 */
export const registerDirective = (Vue, name, def) => {
    if (Vue && name && def) {
        // Ensure that any leading V is removed from the
        // name, as Vue adds it automatically
        Vue.directive(name.replace(/^VB/, 'B'), def)
    }
}

/**
 * Load a group of directives.
 * @param {object} Vue
 * @param {object} Object of directive definitions
 */
export const registerDirectives = (Vue, directives = {}) => {
    for (const directive in directives) {
        registerDirective(Vue, directive, directives[directive])
    }
}

