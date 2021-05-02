/*
 | Browser-sync config file
 | For up-to-date information about the options:
 |   http://www.browsersync.io/docs/options/
 | */
 module.exports = {
  "ui": false,
  "files": [
    './wp-content/**/*.php',
    './wp-content/**/*.js'
  ],
  "watchEvents": [
      "change"
  ],
  "ignore": ["node_modules/*"],
  "single": false,
  "watchOptions": {
      "ignoreInitial": true
  },
  "server": false,
  "proxy": 'localhost:8091',
  "port": 3001,
  "notify": false
};