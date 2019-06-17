import ImageGallery from 'react-image-gallery';
import PropTypes from 'prop-types';

import 'react-image-gallery/styles/css/image-gallery.css';

const {
  element: {
    Fragment,
  },
  i18n: {
    __,
  },
  editor: {
    BlockControls,
    MediaUpload,
    MediaPlaceholder,
    MediaUploadCheck,
  },
  components: {
    IconButton,
  },
} = wp;

const ALLOWED_MEDIA_TYPES = ['image'];

const GalleriesEdit = (props) => {
  const {
    attributes: {
      images,
    },
    setAttributes,
    className,
  } = props;

  const onSelectImages = (newImages) => {
    setAttributes({
      images: newImages.map((image) => {
        const imageProps = {
          original: image.url,
          alt: image.alt,
          caption: image.caption,
          id: image.id,
        };
        return imageProps;
      }),
    });
  };

  const hasImages = 0 !== images.length;

  const ids = images.map((image) => {
    return image.id;
  });

  const gallery = images.map((image) => {
    const imageProps = {
      original: image.original,
      originalAlt: image.alt,
      description: image.caption,
    };
    return imageProps;
  });

  return (
    <Fragment>
      <div className={className}>
        { hasImages ? (
          <Fragment>
            <BlockControls>
              <MediaUploadCheck>
                <MediaUpload
                  onSelect={onSelectImages}
                  allowedTypes={ALLOWED_MEDIA_TYPES}
                  value={ids}
                  multiple
                  gallery
                  render={({ open }) => {
                    return (
                      <IconButton
                        className="components-toolbar__control"
                        label={__('Edit gallery', 'cpr')}
                        icon="edit"
                        onClick={open}
                      />
                    );
                  }}
                />
              </MediaUploadCheck>
            </BlockControls>
            <div className="cpr-gallery-images">
              <ImageGallery
                useBrowserFullscreen={false}
                showFullscreenButton={false}
                showBullets={false}
                showThumbnails={false}
                showPlayButton={false}
                items={gallery}
              />
            </div>
          </Fragment>
        ) : (
          <Fragment>
            <MediaPlaceholder
              accept="image/*"
              allowedTypes={ALLOWED_MEDIA_TYPES}
              onSelect={onSelectImages}
              multiple
              labels={{
                title: __('CPR Galleries', 'cpr'),
                instructions: __('Select the images for this gallery/slideshow.', 'cpr'),
              }}
            />
          </Fragment>
        )}
      </div>
    </Fragment>
  );
};

GalleriesEdit.propTypes = {
  attributes: PropTypes.shape({
    images: PropTypes.arrayOf.isRequired,
  }).isRequired,
  setAttributes: PropTypes.func.isRequired,
  className: PropTypes.string.isRequired,
};

export default GalleriesEdit;
