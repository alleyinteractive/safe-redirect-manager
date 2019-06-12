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
    innerblocksContent,
  } = props;
  const {
    heading,
    icon,
    enableToggle,
  } = attributes;

  setAttributes({ innerblocksContent });

  return (
    <Fragment>
      <InspectorControls>
        <PanelBody>
          <ToggleControl
            label={__('Toggle content', 'cpr')}
            checked={!! enableToggle}
            onChange={(value) => {
              setAttributes({ enableToggle: value });
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
        value={heading}
        onChange={(value) => {
          setAttributes({ heading: value });
        }}
      />
      <InnerBlocks />
    </Fragment>
  );
};

HighlightedItemEdit.propTypes = {
  attributes: PropTypes.shape({
    heading: PropTypes.string.isRequired,
    enableToggle: PropTypes.bool.isRequired,
    icon: PropTypes.string.isRequired,
  }).isRequired,
  setAttributes: PropTypes.func.isRequired,
  innerblocksContent: PropTypes.string.isRequired,
};

export default HighlightedItemEdit;
