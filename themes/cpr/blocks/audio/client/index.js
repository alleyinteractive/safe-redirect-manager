import pick from 'lodash/fp/pick';

const {
  hooks: {
    addFilter,
  },
} = wp;

// Hook for modifying core audio block.
const onBlockRegistered = (block, name) => {
  if ('core/audio' === name) {
    const { attributes } = block;

    // Pick out only attribute and type from the attribute config.
    const newAttributes = Object.keys(attributes)
      .reduce((acc, curr) => {
        return {
          ...acc,
          [curr]: {
            ...pick(['attribute', 'type'], attributes[curr]),
          },
        };
      }, {});

    // Return new block with modified attributes and empty save (for dynamic block).
    return {
      ...block,
      attributes: newAttributes,
      save: () => {
        return null;
      },
    };
  }

  return block;
};

addFilter('blocks.registerBlockType', 'cpr/audio', onBlockRegistered);
