/**
 * WordPress dependencies
 */
// Import the original config from the @wordpress/scripts package.
const defaultConfig = require("@wordpress/scripts/config/webpack.config");

/**
 * External dependencies
 */
const glob = require("glob");
const path = require("path");

module.exports = (env) => {
  return {
    ...defaultConfig,
    ...{
      entry: {
        ...defaultConfig.entry(),
      },
      output: {
        ...defaultConfig.output,
        chunkFormat: 'array-push',
      },
    },
  };
};
