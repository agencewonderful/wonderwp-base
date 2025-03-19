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

/**
 * Detects the list of entry points to use with webpack.
 *
 * @see https://webpack.js.org/concepts/entry-points/
 *
 * @return {Object<string,string>} The list of entry points.
 */
function getStyleEntryPoints(entryGlobChunkPath, entryGlobPath) {
  const entryFiles = glob.sync(
    path.resolve(__dirname, path.join(entryGlobPath, "/**/*.scss"))
  );
  const entryPoints = {};

  entryFiles.forEach((entryFile) => {
    const relativePath = path.relative(
      path.resolve(__dirname, entryGlobPath),
      entryFile
    );
    const entryName = relativePath.replace(".scss", "");
    entryPoints[path.join(entryGlobChunkPath, entryName)] = entryFile;
  });

  if (Object.keys(entryPoints).length > 0) {
    return entryPoints;
  }
}

module.exports = {
  ...defaultConfig,
  output: {
    ...defaultConfig.output,
    chunkFormat: 'array-push',
    chunkLoading: 'jsonp',
    globalObject: 'this'
  },
  target: ['web', 'es5'],
  ...{
    entry: {
      ...defaultConfig.entry(),
      ...getStyleEntryPoints("css/blocks", "assets/raw/scss/block-stylesheets"),
    }
  }
};
