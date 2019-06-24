import camelCase from 'lodash/camelCase';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import classnames from 'classnames/dedupe';
import './iconPicker.scss';

const { useState } = wp.element;
const { __ } = wp.i18n;

const {
  Dropdown,
  Tooltip,
  BaseControl,
  TextControl,
} = wp.components;

library.add(fab, fas);
const allFabIcons = Object.values(fab);
const allFasIcons = Object.values(fas);
const allIconData = allFabIcons.concat(allFasIcons);

// Move question mark icons to the top.
allIconData.forEach((icon, idx) => {
  if (icon.iconName.includes('question')) {
    const thisIcon = allIconData.splice(idx, 1);
    allIconData.unshift(thisIcon[0]);
  }
});

const Icon = (props) => {
  const {
    className,
    iconData,
    onClick,
    active,
  } = props;

  // These icons cause problems, so let's not try to render them.
  // camelCase() transforms `fa-500px` to fa500Px, which does not work.
  // The fontawesome logo is huge, breaks the layout and why is it even there...
  if (
    iconData &&
    ('500px' === iconData.iconName || 'font-awesome-logo-full' === iconData.iconName)
  ) {
    return null;
  }

  const iconPrefix = iconData ? iconData.prefix : '';
  const iconName = iconData ? `fa-${iconData.iconName}` : '';
  const iconId = 'fas' === iconPrefix ? fas[camelCase(iconName)] : fab[camelCase(iconName)];

  return (
    <span
      className={classnames(
        className,
        'cpr-component-icon-picker-preview',
        'cpr-component-icon-picker-button',
        { 'cpr-component-icon-picker-preview-clickable': onClick },
        { 'cpr-component-icon-picker-button-active': active },
      )}
      onClick={onClick}
      onKeyPress={() => {}}
      role="button"
      tabIndex={0}
      data-iconName={camelCase(iconName)}
      data-iconPrefix={iconPrefix}
    >
      <FontAwesomeIcon icon={iconId} />
    </span>
  );
};

const IconPickerDropdown = (props) => {
  const {
    label,
    className,
    onChange,
    value,
    renderToggle,
  } = props;

  const [query, setQuery] = useState('');

  const dropdown = (
    <Dropdown
      className={className}
      renderToggle={renderToggle}
      renderContent={() => {
        const result = allIconData.map((icon) => {
          if (
            ! query ||
              (query && - 1 < icon.iconName.indexOf(query))
          ) {
            return (
              <Icon
                key={icon.iconName}
                active={icon.iconName === value}
                iconData={icon}
                onClick={() => {
                  onChange(icon);
                }}
              />
            );
          }

          return '';
        });

        return (
          <div className="cpr-component-icon-picker">
            <TextControl
              value={value}
              onChange={(newClass) => {
                onChange(newClass);
              }}
              placeholder={__('Icon class', 'cpr')}
              type="hidden"
            />
            <TextControl
              label={__('Search icon')}
              value={query}
              onChange={(searchVal) => {
                setQuery(searchVal);
              }}
              placeholder={__('Type to search...', 'cpr')}
            />
            <div className="cpr-component-icon-picker-list-wrap">
              <div className="cpr-component-icon-picker-list">
                { result }
              </div>
            </div>
          </div>
        );
      }}
    />
  );

  return label ? (
    <BaseControl
      label={label}
      className={className}
    >
      { dropdown }
    </BaseControl>
  ) : (
    dropdown
  );
};

const IconPicker = (props) => {
  const {
    value,
    onChange,
    label,
  } = props;

  return (
    <IconPickerDropdown
      label={label}
      className="cpr-component-icon-picker-wrapper"
      onChange={onChange}
      value={value}
      renderToggle={({ isOpen, onToggle }) => {
        return (
          <Tooltip text={__('Icon Picker', 'cpr')}>
            <Icon
              className="cpr-component-icon-picker-button"
              aria-expanded={isOpen}
              onClick={onToggle}
              iconData={value}
              alwaysRender
            />
          </Tooltip>
        );
      }}
    />
  );
};

export default IconPicker;
