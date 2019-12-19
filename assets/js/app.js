/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single scss file (app.scss in this case)
require('../scss/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// eslint-disable-next-line no-unused-vars

// eslint-disable-next-line import/no-extraneous-dependencies
require('bootstrap');
// eslint-disable-next-line import/no-extraneous-dependencies

const toggleSwitch = document.querySelector('.theme-switch-wrapper input[type="checkbox"]');
const currentTheme = localStorage.getItem('theme');

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);

    if (currentTheme === 'dark') {
        toggleSwitch.checked = true;
    }
}

function switchTheme(e) {
    if (e.target.checked) {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }
    else {        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
    }
}

toggleSwitch.addEventListener('change', switchTheme, false);