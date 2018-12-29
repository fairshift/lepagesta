/* 
// Razzle.config.js — extending Webpack with "@loadable" package (only as example, as this boilerplate uses "react-loadable")

const LoadablePlugin = require('@loadable/webpack-plugin')

module.exports = {
  modify: (config, { target }) => {
    if (target === 'web') {
      return {
        ...config,
        plugins: [
          ...config.plugins,
          new LoadablePlugin()
        ],
      };
    }

    return config;
  },
};

*/



/* 
// Web worker loader configuration for github.com/webpack-contrib/worker-loader


//
// Webpack config code (use the above example as a guide to implementation)
//
module: [
  rules: [
    {
      test: /\.worker\.js$/,
      use: { loader: 'worker-loader' }
    }
  ]
],

//
// App.js
//
import Worker from './worker.js';

const worker = new Worker();

worker.postMessage({ a: 1 });
worker.onmessage = function (event) {};

worker.addEventListener("message", function (event) {});

*/