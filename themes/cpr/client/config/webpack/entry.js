/**
 * Get a single entry point provided in the --env flag.
 *
 * @param {object} env - environmental variables, including specific entry to compile.
 */
const entry = {
  global: ['client/src/entries/global/index.js'],
  admin: ['client/src/admin/admin.js'],
  editor: ['client/src/admin/editor/index.js'],
};

function getSingleEntry(devEntry, env) {
  const selectedEntryKeys = (env && env.entry)
    ? env.entry.split(',')
    : [];

  if (selectedEntryKeys.length) {
    return Object.keys(devEntry)
      .reduce((acc, entryName) => (selectedEntryKeys.includes(entryName) ?
        Object.assign(acc, { [entryName]: devEntry[entryName] }) :
        acc), {});
  }

  return devEntry;
}

module.exports = function getEntry(mode, env) {
  switch (mode) {
    case 'development': {
      const devEntry = Object.keys(entry)
        .map((currentValue) => (
          [
            'webpack-dev-server/client?https://8080-httpsproxy.alley.test',
            'webpack/hot/only-dev-server',
          ].concat(entry[currentValue])
        ))
        .reduce((acc, entryArray, index) => (
          Object.assign(acc, {
            [Object.keys(entry)[index]]: entryArray,
          })
        ), {});
      return getSingleEntry(devEntry, env, mode);
    }

    case 'production':
    default:
      return entry;
  }
};
