import classnames from 'classnames/dedupe';
import PropTypes from 'prop-types';

const AccordionSave = (props) => {
  const { InnerBlocks } = wp.editor;
  const {
    itemsCount,
    DisableToggle,
  } = props.attributes;
  const className = classnames(
    'cpr-accordion',
    `cpr-accordion-${itemsCount}`,
    DisableToggle ? 'cpr-accordion-disable-toggle' : ''
  );

  return (
    <div className={className}>
      <InnerBlocks.Content />
    </div>
  );
};

AccordionSave.propTypes = {
  attributes: PropTypes.shape({
    itemsCount: PropTypes.string.isRequired,
    DisableToggle: PropTypes.bool.isRequired,
  }).isRequired,
};

export default AccordionSave;
