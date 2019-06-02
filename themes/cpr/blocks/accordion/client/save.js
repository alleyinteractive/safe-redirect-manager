// External Dependencies.
import classnames from 'classnames/dedupe';

// Internal Dependencies.
import PropTypes from 'prop-types';

const AccordionSave = (props) => {
  const { InnerBlocks } = wp.editor;
  const {
    itemsCount,
    collapseOne,
  } = props.attributes;
  const className = classnames(
    'cpr-accordion',
    `cpr-accordion-${itemsCount}`,
    collapseOne ? 'cpr-accordion-collapse-one' : ''
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
    collapseOne: PropTypes.bool.isRequired,
  }).isRequired,
};

export default AccordionSave;
