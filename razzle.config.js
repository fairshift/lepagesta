/* Razzle.config.js - extending Webpack
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
}; */

/* Web worker loader - github.com/webpack-contrib/worker-loader

// Webpack config
module: [
  rules: [
    {
      test: /\.worker\.js$/,
      use: { loader: 'worker-loader' }
    }
  ]
],


// App.js
import Worker from './file.worker.js';

const worker = new Worker();

worker.postMessage({ a: 1 });
worker.onmessage = function (event) {};

worker.addEventListener("message", function (event) {});

*/