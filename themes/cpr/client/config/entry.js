/* global ent */
// Entry points used for all but `admin` webpack build/config
const entry = {
  admin: ['client/src/admin/admin.js'],
};

/**
 * Get a single entry point provided in the --env flag.
 *
 * @param {object} env - environmental variables, including specific entry to compile.
 */
function getSingleEntry(env) {
  const selectedEntryKeys = env.entry ? env.entry.split(',') : [];

  if (selectedEntryKeys.length) {
    return Object.keys(entry)
      .reduce((acc, entryName) => {
        return selectedEntryKeys.includes(entryName) ?
          Object.assign(acc, { [entryName]: entry[entryName] }) :
          acc;
        }, {});
  }

  return entry;
}

/**
 * Consolidate entry points for HMR.
 *
 * @param {object} env - environmental variables, including specific entry to compile.
 */
function getHotEntry(env) {
  const currentEntries = getSingleEntry(env);
  const entryNames = Object.keys(currentEntries);

  // Process HMR entry points
  return [].concat(entryNames.reduce(
    (acc, curr) => acc.concat(currentEntries[curr]),
    []
  ));
}

module.exports = {
  entry,
  admin,
  getSingleEntry,
  getHotEntry,
};
