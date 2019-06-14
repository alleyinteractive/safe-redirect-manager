import GalleryEdit from './edit';

const {
  i18n: {
    __,
  },
  blocks: {
    registerBlockType,
  },
} = wp;

registerBlockType('cpr/galleries', {
  title: __('CPR Galleries', 'cpr'),
  description: __('Galleries for CPR', 'cpr'),
  category: 'common',
  attributes: {
    images: {
      type: 'array',
      default: [],
    },
    ids: {
      type: 'array',
      default: [],
    },
  },
  supports: {
    align: ['full'],
  },
  edit: GalleryEdit,
  save: () => {
    return null;
  },
});
