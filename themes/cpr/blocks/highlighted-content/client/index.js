/**
 *  FAQ Block.
 */

import HighlightedEdit from './edit';
import HighlightedSave from './save';

const { registerBlockType, createBlock } = wp.blocks;
const { __ } = wp.i18n;

registerBlockType('cpr/highlighted-content', {
  title: __('Highlighted Content', 'cpr'),
  description: __('Block to format a something.', 'cpr'),
  icon: 'text',
  category: 'common',
  keywords: [
    __('section', 'cpr'),
    __('header', 'cpr'),
  ],
  attributes: {
    content: {
      type: 'string',
    },
  },
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
  edit: HighlightedEdit,
  save: HighlightedSave,
});
