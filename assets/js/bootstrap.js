/**
 * load toastr, lodash. 
 */

window.toastr = require('toastr');

/**
 * load axios. 
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * load vue. 
 */

window.Vue = require('vue');