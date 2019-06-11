import PropTypes from 'prop-types';
import IconPicker from './icons/index';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const {
  InnerBlocks,
  RichText,
  InspectorControls,
} = wp.editor;
const {
  PanelBody,
  ToggleControl,
} = wp.components;

const HighlightedItemEdit = (props) => {
  const {
    attributes,
    setAttributes,
  } = props;

  const {
    heading,
    icon,
    EnableToggle,
  } = attributes;

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody>
          <ToggleControl
            label={__('Toggle content', 'cpr')}
            checked={!! EnableToggle}
            onChange={(value) => {
              setAttributes({ EnableToggle: value });
            }}
          />
        </PanelBody>
      </InspectorControls>
      <IconPicker
        label={__('Icon', 'cpr')}
        value={icon}
        onChange={(value) => {
          setAttributes({ icon: value });
        }}
      />
      <RichText
        placeholder={__('Content Title', 'cpr')}
        value={heading}
        onChange={(value) => {
          setAttributes({ heading: value });
        }}
      />
      <InnerBlocks
        templateLock={false}
      />
    </Fragment>
  );
};

HighlightedItemEdit.propTypes = {
  attributes: PropTypes.shape({
    heading: PropTypes.array.isRequired,
    EnableToggle: PropTypes.bool.isRequired,
    icon: PropTypes.string.isRequired,
  }).isRequired,
  setAttributes: PropTypes.func.isRequired,
};

export default HighlightedItemEdit;
