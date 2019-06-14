import PropTypes from 'prop-types';

const {
  element: {
    Fragment,
    Component,
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

class GalleryEdit extends Component {
  static propTypes = {
    attributes: PropTypes.shape({
      images: PropTypes.array,
      ids: PropTypes.array,
    }).isRequired,
    setAttributes: PropTypes.func.isRequired,
    className: PropTypes.string.isRequired,
  };

  constructor() {
    // eslint-disable-next-line prefer-rest-params
    super(...arguments);
    this.onSelectImages = this.onSelectImages.bind(this);
  }

  onSelectImages(images) {
    const {
      setAttributes,
    } = this.props;

    const imagesArray = Object.keys(images).map((key) => {
      return images[key];
    });

    setAttributes({
      images: imagesArray.map((image) => {
        const imageProps = {
          url: image.url,
          alt: image.alt,
          caption: image.caption,
          id: image.id,
        };
        return imageProps;
      }),
      ids: imagesArray.map((image) => {
        return image.id;
      }),
    });
  }

  render() {
    const {
      attributes: {
        images,
        ids,
      },
      className,
    } = this.props;

    const hasImages = 0 !== images.length;

    return (
      <Fragment>
        <div className={className}>
          {hasImages ? (
            <Fragment>
              <BlockControls>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={this.onSelectImages}
                    allowedTypes={ALLOWED_MEDIA_TYPES}
                    value={ids}
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
                { images.map((img) => {
                  return (
                    <div className="slick-slider-block-slide">
                      <img
                        className="slick-slider-image"
                        alt={img.alt}
                        data-id={img.id}
                        src={img.url}
                      />
                    </div>
                  );
                }) }
              </div>
            </Fragment>
          ) : (
            <Fragment>
              <MediaPlaceholder
                accept="image/*"
                allowedTypes={ALLOWED_MEDIA_TYPES}
                onSelect={this.onSelectImages}
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
  }
}

export default GalleryEdit;
