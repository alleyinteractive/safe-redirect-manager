import GalleriesEdit from './edit';

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
  icon: 'format-gallery',
  category: 'common',
  attributes: {
    images: {
      type: 'array',
      default: [],
    },
  },
  supports: {
    align: ['full'],
  },
  edit: GalleriesEdit,
  save: () => {
    return null;
  },
});
