/**
 *  FAQ Block.
 */

const { registerBlockType, createBlock } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('cpr/highlighted-content', {
  title: __('Highlighted Content', 'cpr'),
  description: __('Block to format a something.', 'cpr'),
  icon: 'text',
  category: 'common',
  transforms: {
    from: [
      {
        type: 'block',
        blocks: ['core/paragraph'],
        transform: ({ content }) =>
          createBlock('cpr/highlighted-content', { content }), // eslint-disable-line arrow-body-style
      },
    ],
    to: [
      {
        type: 'block',
        blocks: ['core/paragraph'],
        transform: ({ content }) =>
          createBlock('core/paragraph', { content }), // eslint-disable-line arrow-body-style
      },
    ],
  },
  attributes: {
    content: {
      type: 'string',
    },
  },
  edit: () => {
    return null;
  },
  save: () => {
    return null;
  },
});
