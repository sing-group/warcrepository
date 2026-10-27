/**
 * Bootstrap 3 needs jQuery as a global before its plugins load.
 */

import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;

export default jQuery;
